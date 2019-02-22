<?php

use OAuthServer\Models\Application;
use OAuthServer\Models\Authorization;

use Slim\Http\Request;
use Slim\Http\Response;

$app->get("/authorize", function (Request $request, Response $response, array $arguments): Response {
    //TODO
    return $response->withStatus(200)->write("Get /authorize");
});
