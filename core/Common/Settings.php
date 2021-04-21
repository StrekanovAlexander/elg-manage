<?php

namespace App\Common;

class Settings
{
    public static $global = [
        'info_bot_token' => '1777201537:AAFqqrlkkuLjtCstVKfbAfoxI8YeDM136xU',
        'alert_chat_id' => '-1001268528953',
        'brand' => 'Евроломбард', 
        'mainButtons' => [
            [
                ['text' => 'Курсы валют'], 
                ['text' => 'Выбрать город'], 
                ['text' => 'Сайт компании'],
            ],
        ],
        'siteButton' => [
            [
                ['url' => 'https://elg.co.ua', 'text' => 'Перейти на сайт'],
            ]
        ],
        'titles' => [
            'rates' => 'Курсы валют', 
            'places' => 'Отделения в городах', 
            'site' => 'Сайт компании', 
        ],
    ];
}