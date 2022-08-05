<?php

header('Content-Type: text/html; charset=utf-8');

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../core/db.php');

use App\Common\Bot;

$token = '1777921299:AAGH1udGiETUgjn0F5N3pn8dgqk6I96O3uU';

try {
    $bot = new Bot($token, $db);

    $bot->command('start', function ($message) use ($bot) {
        $bot->replyKeyboard($message);
    });
    
    $bot->on(function (\TelegramBot\Api\Types\Update $update) use ($bot) {
        
        $message = $update->getMessage();
        $id = $message->getChat()->getId();
        $text = $message->getText();

        if (mb_stripos($text, 'Выбрать город') !== false) {
            $bot->inlineKeyboard($message, $bot->printPlaces(), 'places');
        }

        if (mb_stripos($text, 'Курсы валют') !== false) {
            $bot->sendMessage($message->getChat()->getId(), $bot->printRates());
        }

        if (mb_stripos($text, 'Сайт компании') !== false) {
            $bot->replyKeyboard($message);
        }    

    }, function () {
        return true;
    });
    
    $bot->run();

} catch (\TelegramBot\Api\Exception $e) {
    $e->getMessage();
}
