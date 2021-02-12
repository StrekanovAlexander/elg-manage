<?php

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../core/db.php');

$app = new \Slim\App([
    'settings' => [
       'displayErrorDetails' => true,
       'db' => $db,
    ]
]);

$c = $app->getContainer();

$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($c['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$c['db'] = function() use ($capsule) {
  return $capsule;
};

$c['view'] = function($c) {
    $view = new \Slim\Views\Twig(__DIR__ . '/../core/views', [
        'cache' => false,
    ]);
    $view->addExtension(new \Slim\Views\TwigExtension(
      $c->router,
      $c->request->getUri()
    ));
    $view->getEnvironment()->addGlobal('flash', $c->flash);
    return $view;
};

$c['csrf'] = function() {
    return new \Slim\Csrf\Guard;
};

$c['flash'] = function() {
    return new \Slim\Flash\Messages;
};

$c['BaseRateController'] = function($c) {
    return new App\Controllers\BaseRateController($c);
};

$c['HomeController'] = function($c) {
    return new App\Controllers\HomeController($c);
};

$c['PlaceController'] = function($c) {
    return new App\Controllers\PlaceController($c);
};

$app->add(new \App\Middleware\CsrfMiddleware($c));
$app->add($c->csrf);