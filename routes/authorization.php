<?php

use OAuthServer\Models\Application;
use OAuthServer\Models\Authorization;

use Slim\Http\Request;
use Slim\Http\Response;

$app->get("/authorize", function (Request $request, Response $response, array $arguments): Response {

    # https://authorization-server.com/auth?response_type=code&client_id=CLIENT_ID&redirect_uri=REDIRECT_URI&scope=photos&state=1234zyx

    //TODO: check arguments
    die(print_r($arguments, true));


    return $response->withStatus(200)->write("Get /authorize");
});
