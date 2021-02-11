<?php

require(__DIR__ . '/../vendor/autoload.php');

$app = new \Slim\App([
    'settings' => [
       'displayErrorDetails' => true,
    ]
]);

$c = $app->getContainer();

$c['view'] = function($c) {
    $view = new \Slim\Views\Twig(__DIR__ . '/../core/views', [
        'cache' => false,
    ]);
    $view->addExtension(new \Slim\Views\TwigExtension(
      $c->router,
      $c->request->getUri()
    ));
    return $view;
};

$c['HomeController'] = function($c) {
    return new App\Controllers\HomeController($c);
};