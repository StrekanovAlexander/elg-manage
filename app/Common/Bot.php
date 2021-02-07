<?php

namespace App\Common;

class Bot extends \TelegramBot\Api\Client
{

    private $prefix;

    public function getCity()
    {
        if ($this->prefix == 'zp') {
            $city = 'Запорожье';
        }
        return $city; 
    }
    
    public function __construct($token, $prefix)
    {
        parent::__construct($token);
        $this->prefix = $prefix;
    }

    public function sendMsg($message, $text)
    {
        $this->sendMessage($message->getChat()->getId(), $text);
    }

}