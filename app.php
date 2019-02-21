<?php

date_default_timezone_set("UTC");

require __DIR__ . "/vendor/autoload.php";

error_reporting(E_ALL);
ini_set("display_errors", 1);

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

$app = new \Slim\App([
    "settings" => [
        "displayErrorDetails" => true,
        "addContentLengthHeader" => false,
    ]
]);

require __DIR__ . "/config/dependencies.php";
#require __DIR__ . "/config/middleware.php";

$app->get("/", function ($request, $response, $arguments) {
    $response->getBody()->write("OAUTH Server");
    return $response->withStatus(200);
});

require __DIR__ . "/routes/users.php";
require __DIR__ . "/routes/register.php";

$app->run();
