<?php

use OAuthServer\Models\Application;
use OAuthServer\Models\Authorization;

use Slim\Http\Request;
use Slim\Http\Response;

$app->get("/token", function (Request $request, Response $response, array $arguments): Response {

    $params = $request->getQueryParams();

    //TODO: Check Params are set, and valid
    $grant_type = $params['grant_type'] ?? null;
    $client_id = $params['client_id'] ?? null;
    $client_secret = $params['client_secret'] ?? null;
    $callback_uri = $params['callback_uri'] ?? null;
    $authorization_code = $params['authorization_code'] ?? null;

    $authorization = Authorization::where('auth_code', $authorization_code)->first();

    if (!$authorization) {
        return $response->withStatus(404);
    }

    $application = $authorization->application;

    //Check that application client id matches supplied
    if ($client_id !== $application->client_id) {
        return $response->withStatus(403);
    }

    //Check that application client secret matches supplied
    if ($client_secret !== $application->client_secret) {
        return $response->withStatus(403);
    }

    //Check Response URI Matches
    if ($callback_uri !== $application->callback_uri) {
        return $response->withStatus(403);
    }

    $authorization->access_token = bin2hex(random_bytes(16));
    $authorization->token_expiration =  date('Y-m-d H:i:s', strtotime('+60 minutes'));

    $authorization->save();

    //TODO: Set other information that is to be returned to the user, e.g. expiry, token type
    return $response->withStatus(200)
        ->withHeader("Content-Type", "application/json")
        ->write(json_encode(['access_token' => $authorization->access_token], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
});
