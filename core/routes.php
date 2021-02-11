<?php

$app->group('', function() {
    $this->get('/', 'HomeController:index')->setName('home.index');
});    