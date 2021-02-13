<?php

$app->group('', function() {
    $this->get('/', 'HomeController:index')->setName('home.index');

    $this->get('/base-rates/create', 'BaseRateController:create')->setName('base-rate.create');
    $this->post('/base-rates/create', 'BaseRateController:store');
    $this->get('/base-rates', 'BaseRateController:index')->setName('base-rate.index');

    $this->get('/curr-rules/places/{id}', 'CurrRuleController:details')->setName('curr-rule.details');
    $this->get('/rates/places/{id}', 'RateController:details')->setName('rate.details');

    $this->get('/places', 'PlaceController:index')->setName('place.index');
});    