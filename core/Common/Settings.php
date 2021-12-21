<?php

namespace App\Common;

class Settings
{
    public static $global = [
        'info_bot_token' => '1777201537:AAFqqrlkkuLjtCstVKfbAfoxI8YeDM136xU',
        'main_bot_token' => '1647327669:AAHS4UGQmxhsVNZmkZH_ecsTwrX7gWwTsWE',
        'alert_chat_id' => '-1001268528953',
        'main_chat_id' => '-1001378695678',
        'mail' => [
            'general_email' => '8899897@gmail.com',
            'smtp' => 'smtp.gmail.com',
            'port' => 465,
            'encrypt' => 'ssl',
            'usr' => 'fin.lombard@gmail.com',
            'pwd' => 'Aksafefy',
        ],
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
                ['url' => 'https://obmen1.zp.ua', 'text' => 'Перейти на сайт'],
            ]
        ],
        'titles' => [
            'rates' => 'Курсы валют', 
            'places' => 'Отделения в городах', 
            'site' => 'Сайт компании', 
        ],
    ];
}