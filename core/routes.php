<?php

$app->group('', function() {
    $this->get('/', 'HomeController:index')->setName('home.index');

    $this->get('/base-rates/create', 'BaseRateController:create')->setName('base-rate.create');
    $this->post('/base-rates/create', 'BaseRateController:store');
    $this->get('/base-rates', 'BaseRateController:index')->setName('base-rate.index');

    $this->get('/places', 'PlaceController:index')->setName('place.index');
    
    $this->get('/rates/places/{id}', 'RateController:details')->setName('rate.details');

    $this->get('/rules/place/edit[/{id}]', 'RuleController:edit')->setName('rule.edit');
    $this->post('/rules/place/edit', 'RuleController:update');

    $this->get('/rules/place/{id}', 'RuleController:details')->setName('rule.details');

    $this->get('/user/logout', 'UserController:logout')->setName('user.logout');
    
})->add(new \App\Middleware\AuthMiddleware($c));

$app->group('', function() {
    $this->get('/user/login', 'UserController:login')->setName('user.login');
    $this->post('/user/login', 'UserController:postLogin');
})->add(new \App\Middleware\AdminMiddleware($c));    