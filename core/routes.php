<?php

$app->group('', function() {
    $this->get('/', 'HomeController:index')->setName('home.index');

    $this->get('/base-rates/create', 'BaseRateController:create')->setName('base-rate.create');
    $this->get('/base-rates', 'BaseRateController:index')->setName('base-rate.index');
});    