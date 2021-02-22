<?php

header('Content-Type: text/html; charset=utf-8');

require(__DIR__ . '/../../vendor/autoload.php');
require(__DIR__ . '/../../core/db.php');

use App\Common\Bot;

$token = '1406578567:AAFvfbiwcxE78FeMGdVxUvPF3MMCRXdo7Z4';
$placeId = 2;
$place = 'Запорожье';

$bot = new Bot($token, $placeId, $db);

$bot->command('start', function ($message) use ($bot) {
    $bot->replyKeyboard($message);
});

$bot->on(function($update) use ($bot) {
	$message = $update->getMessage();
	$text = $message->getText();
	if (mb_stripos($text, 'Сайт компании') !== false) {
        $bot->inlineKeyboard($message, $bot->getSiteButton(), 'site');
    }
    if (mb_stripos($text, 'Курсы валют') !== false) {
        $bot->sendMessage($message->getChat()->getId(), $bot->printRates());
    }}, function($message) use ($name) {
        return true;
    }
);

$bot->run();
