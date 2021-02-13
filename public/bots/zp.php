<?php

header('Content-Type: text/html; charset=utf-8');

require(__DIR__ . '/../../vendor/autoload.php');

use App\Common\Bot;

$token = '1406578567:AAFvfbiwcxE78FeMGdVxUvPF3MMCRXdo7Z4';
$place = 'Запорожье';

$bot = new Bot($token, $place);

$bot->command('start', function ($message) use ($bot) {
    $bot->replyKeyboard($message, [
        [
            ['text' => 'Курсы валют'], 
            ['text' => 'Сайт компании'],
        ],
    ], 'Евроломбард-' . $bot->getPlace());
});

$bot->on(function($update) use ($bot) {
	$message = $update->getMessage();
	$text = $message->getText();
	if (mb_stripos($text, 'Сайт компании') !== false) {
        $bot->inlineKeyboard($message, [
            [
                ['url' => 'https://elg.co.ua', 'text' => 'Перейти на сайт'],
            ]
        ], 'Сайт компании');
    }
    if (mb_stripos($text, 'Курсы валют') !== false) {
        $bot->sendMessage($message->getChat()->getId(), 'Курсы валют в ' . $bot->getPlace());
    }}, function($message) use ($name) {
        return true;
    }
);

$bot->run();
