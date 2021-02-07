<?php

require(__DIR__ . '/../vendor/autoload.php');

$app = new \Slim\App([
    'settings' => [
       'displayErrorDetails' => false,
    ]
]);

$app->get('/', function () {
    return 'App is running';
});

$app->run();