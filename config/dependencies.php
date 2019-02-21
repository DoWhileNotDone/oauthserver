<?php

use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\NullHandler;
use Monolog\Formatter\LineFormatter;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager;

use Rakit\Validation\Validator;

$dbsettings = [
    'driver' => getenv("DB_DRIVER"),
    'host' => getenv("DB_HOST"),
    'database' => getenv("DB_NAME"),
    'username' => getenv("DB_USER"),
    'password' => getenv("DB_PASSWORD"),
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
];

$container = $app->getContainer();

$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($dbsettings);
$capsule->setAsGlobal();
$capsule->bootEloquent();

// Service factory for the ORM
$container['db'] = function ($container) use ($capsule) {
    return $capsule;
};

$container['oauthserver'] = function ($container) {
    // Use the existing database connection, in PDO form, for initialising the
    // OAuth server
    $storage= $container->get('db')->getConnection()->getPdo();

    $server = new OAuth2\Server($storage);

    //FIXME: Sort out Oauth Server Configuration
    $server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage)); // or any grant type you like!
    //$server->handleTokenRequest(OAuth2\Request::createFromGlobals())->send();

    return $server;
};

$container["logger"] = function ($container) {
    $logname = getenv("LOG_NAME");
    $logger = new Logger($logname);

    $formatter = new LineFormatter(
        "[%datetime%] [%level_name%]: %message% %context%\n",
        null,
        true,
        true
    );

    $rotating = new RotatingFileHandler(__DIR__ . "/../logs/{$logname}.log", 0, Logger::DEBUG);
    $rotating->setFormatter($formatter);
    $logger->pushHandler($rotating);

    return $logger;
};

#Views and Templating
$container['view'] = new \Slim\Views\PhpRenderer('../src/templates/');

//Add Validation
$container['validator'] = function ($container) {
    return new Validator;
};
