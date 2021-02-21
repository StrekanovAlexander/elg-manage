<?php

namespace App\Common;

use App\Common\Settings;

class Bot extends \TelegramBot\Api\Client
{

    private $place;
    private $settings;

    public function __construct($token, $place)
    {
        parent::__construct($token);
        $this->place = $place;
        $this->settings = Settings::$global;
    }

    public function replyKeyboard($message)
    {
        $keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(
            $this->settings['mainButtons'], 
            false, 
            true
        );

        $this->sendMessage(
            $message->getChat()->getId(), 
            $this->settings['brand'] . ' - ' . $this->place,
            false, 
            null, 
            null, 
            $keyboard
        );
    }

    public function inlineKeyboard($message, $buttons, $title)
    {
        $caption = $this->getCaption($title);
        $keyboard = new \TelegramBot\Api\Types\Inline\InlineKeyboardMarkup($buttons, $caption);
        $this->sendMessage($message->getChat()->getId(), $caption, false, null, null, $keyboard);
    }

    public function message($message, $title)
    {
        $this->sendMessage($message->getChat()->getId(), $this->getCaption($title));
    }

    public function getCaption($title)
    {
        return $this->settings['titles'][$title]; 
    }

    public function getSiteButton()
    {
        return $this->settings['siteButton'];
    }

}