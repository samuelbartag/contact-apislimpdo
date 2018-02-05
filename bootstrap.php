<?php

require './vendor/autoload.php';

$app_config = [
    'settings' => [
        'displayErrorDetails' => true,
        'debug'               => true,
    ],
];

$container = new \Slim\Container($app_config);

$container['database'] = function($container) {
    // $myDatabase = new \PDO('sqlite:db/db.sqlite');
    $myDatabase = new \Slim\PDO\Database('sqlite:db/db.sqlite');

    return $myDatabase;
};

$app = new \Slim\App($container);
