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


// TODO: Add OAUTH Storage Provider
$container['oauthserver'] = function ($container) {

    $storage= $container->get('db')->getConnection()->getPdo();

    $server = new OAuth2\Server($storage);

    //FIXME: Sort out Oauth Server Configuration
    $server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage)); // or any grant type you like!
    $server->handleTokenRequest(OAuth2\Request::createFromGlobals())->send();

    return $server;
};


//TODO: The Loggername and filename can be moved to env
$container["logger"] = function ($container) {
    $logger = new Logger("oauthserver");

    $formatter = new LineFormatter(
        "[%datetime%] [%level_name%]: %message% %context%\n",
        null,
        true,
        true
    );

    /* Log to timestamped files */
    $rotating = new RotatingFileHandler(__DIR__ . "/../logs/oauthserver.log", 0, Logger::DEBUG);
    $rotating->setFormatter($formatter);
    $logger->pushHandler($rotating);

    return $logger;
};

//Add Validation
$container['validator'] = function ($container) {
    return new Validator;
};
