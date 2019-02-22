<?php declare(strict_types=1);

use Slim\Http\Request;
use Slim\Http\Response;

$app->get("/register", function (Request $request, Response $response, array $arguments): Response {
    $response = $this->view->render($response, 'register/new.html', $arguments);
    return $response;
});

$app->post("/register", function (Request $request, Response $response, array $arguments): Response {

    return $response->withStatus(200)->write("post /register");

    //FIXME: Validate, Return Form with errors, or redirect to get application details
});
