<?php

header('Content-Type: text/html; charset=utf-8');

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../core/db.php');

use App\Common\Bot;

$token = '1777201537:AAFqqrlkkuLjtCstVKfbAfoxI8YeDM136xU';

$bot = new Bot($token, $db);

$bot->command('getchat', function ($message) use ($bot) {
    $bot->sendMessage($message->getChat()->getId(), $message->getChat()->getId());
});

$bot->run();