<?php

header('Content-Type: text/html; charset=utf-8');

require(__DIR__ . '/../../vendor/autoload.php');

use App\Common\Bot;

$token = '1406578567:AAFvfbiwcxE78FeMGdVxUvPF3MMCRXdo7Z4';
$prefix = 'zp';

$bot = new Bot($token, $prefix);

$bot->command('start', function ($message) use ($bot) {
    $bot->sendMsg($message, 'Добро пожаловать в Евроломбард - '. $bot->getCity() .'!');
});

$bot->run();
