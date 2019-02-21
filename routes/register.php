<?php

use Slim\Http\Request;
use Slim\Http\Response;

$app->get("/register", function (Request $request, Response $response) {
   // return $response->withStatus(200)->write("get /register");
    $response = $this->view->render($response, 'register/new.phtml');
    return $response;
});

$app->post("/register", function (Request $request, Response $response, $arguments) {

    return $response->withStatus(200)->write("post /register");

    //FIXME: Validate, Return Form with errors, or redirect to get registration
});
