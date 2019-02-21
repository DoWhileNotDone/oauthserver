<?php

use OAuthServer\Models\User;

$app->get("/users", function ($request, $response, $arguments) {

    $users = User::all();

    return $response->withStatus(200)
        ->withHeader("Content-Type", "application/json")
        ->write(json_encode($users, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
});
