<?php declare(strict_types=1);

use OAuthServer\Models\Application;
use Slim\Http\Request;
use Slim\Http\Response;

$app->get("/register", function (Request $request, Response $response, array $arguments): Response {
      // TODO: Look into ways to create form builder using slim, twig and eloquent orm
      $response = $this->view->render($response, 'register/new.html', $arguments);
      return $response;
});

$app->post("/register", function (Request $request, Response $response, array $arguments): Response {

    $parsedBody = $request->getParsedBody();

    $application = new Application();

    //TODO: Parse the input
    $application->application_name = $parsedBody['application_name'];
    $application->application_description = $parsedBody['application_description'];
    $application->homepage_url = $parsedBody['homepage_url'];
    $application->callback_url = $parsedBody['callback_url'];

    //TODO Validate the request...
    // $validation = $this->validator->validate($application->toArray(), $application->getRules());
    //TODO: Return Form with errors
    // if($validation->fails()) {
    //   $this->logger->warning("Invalid POST data sent, not creating", $album->toArray());
    //   return $response->withStatus(400);
    // }

    //https://www.oauth.com/oauth2-servers/client-registration/client-id-secret/
    $application->client_id = bin2hex(random_bytes(32));
    $application->client_secret = bin2hex(random_bytes(32));

    $application->save();

    $location = "application/{$application->application_id}";

    //Redirect to location
    return $response->withStatus(302)->withHeader('Location', $location);
});
