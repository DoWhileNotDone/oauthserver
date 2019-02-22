<?php

use OAuthServer\Models\Application;
use Slim\Http\Request;
use Slim\Http\Response;

$app->get("/applications", function ($request, $response, $arguments) {

    $applications = Application::all();

    return $response->withStatus(200)
        ->withHeader("Content-Type", "application/json")
        ->write(json_encode($applications, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
});

$app->get("/application/{id:[0-9]+}", function (Request $request, Response $response, array $arguments): Response {

    $application = Application::find($arguments['id']);

    $arguments['application'] = $application;

    if (!$application) {
        return $response->withStatus(404);
    }

    $response = $this->view->render($response, 'application/view.html', $arguments);

    return $response;
});
