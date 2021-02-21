<?php

namespace App\Common;

class Settings
{
    public static $global = [
        'brand' => 'Евроломбард', 
        'mainButtons' => [
            [
                ['text' => 'Курсы валют'], 
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
            'site' => 'Сайт компании', 
        ],
    ];
}