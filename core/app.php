<?php

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../core/db.php');

$app = new \Slim\App([
    'settings' => [
       'displayErrorDetails' => false,
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
    $view->getEnvironment()->addGlobal('auth', [
        'check' => $c->auth->check(),
    ]);
     
    return $view;
};

$c['csrf'] = function() {
    return new \Slim\Csrf\Guard;
};

$c['flash'] = function() {
    return new \Slim\Flash\Messages;
};

$c['auth'] = function() {
    return new App\Common\Auth;
};

$c['BaseRateController'] = function($c) {
    return new App\Controllers\BaseRateController($c);
};

$c['ChannelController'] = function($c) {
    return new App\Controllers\ChannelController($c);
};

$c['CurrController'] = function($c) {
    return new App\Controllers\CurrController($c);
};

$c['DepController'] = function($c) {
    return new App\Controllers\DepController($c);
};

$c['HomeController'] = function($c) {
    return new App\Controllers\HomeController($c);
};

$c['MessageController'] = function($c) {
    return new App\Controllers\MessageController($c);
};

$c['PlaceController'] = function($c) {
    return new App\Controllers\PlaceController($c);
};

$c['RateController'] = function($c) {
    return new App\Controllers\RateController($c);
};

$c['RetailRateController'] = function($c) {
    return new App\Controllers\RetailRateController($c);
};

$c['RuleController'] = function($c) {
    return new App\Controllers\RuleController($c);
};

$c['ServiceController'] = function($c) {
    return new App\Controllers\ServiceController($c);
};

$c['SettingController'] = function($c) {
    return new App\Controllers\SettingController($c);
};

$c['UserController'] = function($c) {
    return new App\Controllers\UserController($c);
};

$c['errorHandler'] = function() {
    return function ($req, $res) {
        return $res->withStatus(500)
            ->withHeader('Content-Type', 'text/html')
            ->write('500. Server error');
    };
};
  
$c['notFoundHandler'] = function() {
    return function($req, $res) {
        return $res->withStatus(404)
        ->withHeader('Content-Type', 'text/html')
        ->write('404. Page not found');
    };
};

$app->add(new \App\Middleware\CsrfMiddleware($c));
$app->add($c->csrf);