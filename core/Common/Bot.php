<?php

namespace App\Common;

class Bot extends \TelegramBot\Api\Client
{

    private $place;

    public function __construct($token, $place)
    {
        parent::__construct($token);
        $this->place = $place;
    }

    public function getPlace()
    {
        return $this->place;
    }

    public function replyKeyboard($message, $buttons, $text)
    {
        $keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup($buttons, false, true);
        $this->sendMessage($message->getChat()->getId(), $text, false, null, null, $keyboard);
    }

    public function inlineKeyboard($message, $buttons, $text)
    {
        $keyboard = new \TelegramBot\Api\Types\Inline\InlineKeyboardMarkup($buttons, $text);
        $this->sendMessage($message->getChat()->getId(), $text, false, null, null, $keyboard);
    }

}