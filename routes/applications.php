<?php

use OAuthServer\Models\Application;

$app->get("/applications", function ($request, $response, $arguments) {

    $applications = Applications::all();

    return $response->withStatus(200)
        ->withHeader("Content-Type", "application/json")
        ->write(json_encode($users, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
});
