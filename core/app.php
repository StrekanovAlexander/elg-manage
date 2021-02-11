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
    return $view;
};

$c['HomeController'] = function($c) {
    return new App\Controllers\HomeController($c);
};