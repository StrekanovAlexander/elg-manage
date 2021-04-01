<?php

$app->group('', function() {
    $this->get('/', 'HomeController:index')->setName('home.index');
    // $this->get('/', 'RuleController:getRules')->setName('home.index');
   
    $this->get('/base/create', 'BaseRateController:create')->setName('base.create');
    $this->post('/base/create', 'BaseRateController:store');
    $this->get('/base/cross/create', 'BaseRateController:createCross')->setName('base.cross.create');
    $this->post('/base/cross/create', 'BaseRateController:storeCross');
    
    $this->get('/base', 'BaseRateController:index')->setName('base.index');
    $this->get('/base/index2', 'BaseRateController:index2')->setName('base.index2');
    $this->post('/base/index2', 'BaseRateController:storeBaseAndRules');

    $this->get('/currs', 'CurrController:index')->setName('curr.index');
    $this->get('/currs/create', 'CurrController:create')->setName('curr.create');
    $this->post('/currs/create', 'CurrController:store');
        
    $this->get('/currs/edit[/{id}]', 'CurrController:edit')->setName('curr.edit');
    $this->post('/currs/edit', 'CurrController:update');

    $this->get('/currs/cross', 'CurrController:indexCross')->setName('curr.cross.index');
    $this->get('/currs/cross/create', 'CurrController:createCross')->setName('curr.cross.create');
    $this->post('/currs/cross/create', 'CurrController:storeCross');

    $this->get('/currs/cross/edit[/{id}]', 'CurrController:editCross')->setName('curr.cross.edit');
    $this->post('/currs/cross/edit', 'CurrController:updateCross');

    $this->get('/deps', 'DepController:index')->setName('dep.index');
    
    $this->get('/deps/create', 'DepController:create')->setName('dep.create');
    $this->post('/deps/create', 'DepController:store');
    
    $this->get('/deps/edit[/{id}]', 'DepController:edit')->setName('dep.edit');
    $this->post('/deps/edit', 'DepController:update');

    $this->get('/places', 'PlaceController:index')->setName('place.index');

    $this->get('/rates/places/{id}', 'RateController:details')->setName('rate.details');
    $this->get('/send', 'RateController:send')->setName('rate.send');

    $this->get('/rules/place/edit[/{id}]', 'RuleController:edit')->setName('rule.edit');
    $this->post('/rules/place/edit', 'RuleController:update');

    $this->get('/rules/set', 'RuleController:getRules')->setName('rules.set');
    $this->post('/rules/set', 'RuleController:storeRules');

    $this->get('/rules/place/{id}', 'RuleController:details')->setName('rule.details');

    $this->get('/user', 'UserController:index')->setName('user.index');
    $this->get('/user/logout', 'UserController:logout')->setName('user.logout');
    $this->get('/user/edit', 'UserController:edit')->setName('user.edit');
    $this->post('/user/edit', 'UserController:update');
    
})->add(new \App\Middleware\AuthMiddleware($c));

$app->group('', function() {
    $this->get('/user/login', 'UserController:login')->setName('user.login');
    $this->post('/user/login', 'UserController:postLogin');
})->add(new \App\Middleware\AdminMiddleware($c));    

$app->get('/rates/json/place/{id}', 'RateController:json')->setName('rate.json');
$app->get('/rates/json', 'RateController:json2')->setName('rate.json2');