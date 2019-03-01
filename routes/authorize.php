<?php

use OAuthServer\Models\Application;
use OAuthServer\Models\Authorization;
use OAuthServer\Models\User;

use Slim\Http\Request;
use Slim\Http\Response;

$app->get("/authorize", function (Request $request, Response $response, array $arguments): Response {

    unset($_SESSION['client_id']);
    unset($_SESSION['state']);

    $params = $request->getQueryParams();

    //TODO: Check Params are set, and valid
    $client_id = $params['client_id'];
    //TODO: Currently we are not handling multiple response types, or the scope
    $response_type = $params['response_type'];
    $scope = $params['scope'];
    $callback_uri = $params['callback_uri'];
    $state = $params['state'];

    $application = Application::where('client_id', $client_id)->first();
    if (!$application) {
        return $response->withStatus(404);
    }

    //Check Response URI Matches
    if ($callback_uri !== $application->callback_uri) {
        return $response->withStatus(403);
    }

    $_SESSION['client_id'] = $client_id;
    $_SESSION['state'] = $state;

    $arguments["application_name"] = $application->application_name;

    //FIXME: Display form to confirm authorization
    $response = $this->view->render($response, 'authorize/confirm.html', $arguments);
    return $response;
});


//Redirect to Oauth Server with required details to receive Authorization code
$app->post("/authorize", function (Request $request, Response $response, array $arguments): Response {

    $client_id = $_SESSION['client_id'];
    $state = $_SESSION['state'];

    unset($_SESSION['client_id']);
    unset($_SESSION['state']);

    $application = Application::where('client_id', $client_id)->first();
    if (!$application) {
        return $response->withStatus(404);
    }

    $authorization = new Authorization();

    $authorization->application_id = $application->application_id;
    // TODO: Associate the authorization with the user currently authenticated on this provider server
    $authorization->user()->associate(User::where('user_email', 'davegthemighty@hotmail.com')->first());
    $authorization->scope = ""; //TBC
    $authorization->state = $state;
    $authorization->auth_code = bin2hex(random_bytes(16));
    $authorization->auth_code_expiration = date('Y-m-d H:i:s', strtotime('+1 minute'));

    $authorization->save();

    $params = array(
       'authorization_code' => $authorization->auth_code,
       'state' => $state,
    );

    //TODO: Log the request
    $location = $application->callback_uri.'?'.http_build_query($params);

    //Redirect user to Oauth Server to authenticate with.
    return $response->withStatus(302)->withHeader('Location', $location);
});
