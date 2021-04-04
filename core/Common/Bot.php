<?php

namespace App\Common;

use App\Common\Settings;

class Bot extends \TelegramBot\Api\Client
{
    private $db;
    private $settings;
    private $token;

    public function __construct($token, $db)
    {
        parent::__construct($token);
        $this->db = new \PDO(
            'mysql:host=' . $db['host'] . 
            ';dbname=' . $db['database'], 
            $db['username'], 
            $db['password']
        );
        $this->settings = Settings::$global;
        $this->token = $token;
    }

    private function maxRateTimestamp()
    {
        $stmt = $this->db->prepare('SELECT max(created_at) as created_at FROM elg_rates');
        $stmt->execute();    
        return $stmt->fetch(\PDO::FETCH_OBJ)->created_at;  
    }

    private function placeId()
    {
        $stmt = $this->db->prepare('SELECT id FROM elg_places WHERE bot_token = ?');
        $stmt->execute([$this->token]);    
        return $stmt->fetch(\PDO::FETCH_OBJ)->id;  
    }

    private function placeName()
    {
        $stmt = $this->db->prepare('SELECT full_name FROM elg_places WHERE id = ?');
        $stmt->execute([$this->placeId()]);    
        return $stmt->fetch(\PDO::FETCH_OBJ)->full_name;  
    }

    public function places()
    {
        $placeId = $this->placeId();
        $stmt = $this->db->prepare(
            'SELECT * 
            FROM elg_places 
            WHERE is_actual = ? AND is_bot_active = ? AND id <> ?  
            ORDER BY full_name'
        );
        $stmt->execute([true, true, $placeId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC); 
    }

    private function rates()
    {
        $created_at = $this->maxRateTimestamp();
        $stmt = $this->db->prepare(
            'SELECT r.*, c.short_name, c.icon 
            FROM elg_rates r INNER JOIN elg_currs c ON r.curr_id = c.id 
            WHERE r.place_id = ? AND r.created_at = ? 
            ORDER BY c.id'
        );

        $stmt->execute([$this->placeId(), $created_at]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC); 
    }

    public function printPlaces($parts = 2)
    {
        $arr = [];
        $data = $this->places();
        foreach ($data as $place){
            $arr[] = ['url' => 'https://t.me/' . $place['bot_name'] , 'text' => $place['full_name']];
        }
        $arr = array_chunk($arr, $parts);
        return $arr;
    }

    public function printRates()
    {
        $rates = $this->rates();
        $s = $this->placeName();
        $s .= sprintf("\n%-10s  %' 10s  %' 10s","Валюта","Покупка","Продажа"); 
        foreach ($rates as $rate){
            $s .= sprintf("\n%-10s %' 9s  %' 9s", hex2bin($rate['icon']) . $rate['short_name'], $rate['rate_buy'], $rate['rate_sale']);
        }
        return $s;
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
            $this->settings['brand'] . ' - ' . $this->placeName(),
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

    public function getCaption($title)
    {
        return $this->settings['titles'][$title]; 
    }

    public function getSiteButton()
    {
        return $this->settings['siteButton'];
    }

    public function getPlacesButtons()
    {
        return $this->settings['placesButtons'];
    }

}