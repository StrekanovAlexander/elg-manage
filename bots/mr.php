<?php

header('Content-Type: text/html; charset=utf-8');

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../core/db.php');

use App\Common\Bot;

$token = '1664354561:AAH12Zk6QCoY5Oa-hluK3YUD8x1kMK8hePg';

$bot = new Bot($token, $db);

$bot->command('start', function ($message) use ($bot) {
    $bot->replyKeyboard($message);
});

$bot->on(function($update) use ($bot) {
	$message = $update->getMessage();
	$text = $message->getText();
	if (mb_stripos($text, 'Сайт компании') !== false) {
        $bot->inlineKeyboard($message, $bot->getSiteButton(), 'site');
    }
	if (mb_stripos($text, 'Выбрать город') !== false) {
        $bot->inlineKeyboard($message, $bot->printPlaces(), 'places');
    }
    if (mb_stripos($text, 'Курсы валют') !== false) {
        $bot->sendMessage($message->getChat()->getId(), $bot->printRates());
    }}, function($message) use ($name) {
        return true;
    }
);

$bot->run();
