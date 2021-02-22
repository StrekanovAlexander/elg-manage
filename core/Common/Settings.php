<?php

namespace App\Common;

class Settings
{
    public static $global = [
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
        'placesButtons' => [
            [
                ['callback_data' => 'data_test', 'text' => 'Акимовка'],
                ['callback_data' => 'data_test', 'text' => 'Апостолово'],
				['callback_data' => 'data_test', 'text' => 'Бердянск'],
            ],
            [
                ['callback_data' => 'data_test', 'text' => 'Васильевка'],
                ['callback_data' => 'data_test', 'text' => 'Веселое'],
                ['callback_data' => 'data_test', 'text' => 'Вольнянск'],				
            ],
            [
                ['callback_data' => 'data_test', 'text' => 'Днепрорудное'],
				['url' => 'https://t.me/elgtestbot', 'text' => 'Запорожье'],
				['callback_data' => 'data_test', 'text' => 'Каменка'],
            ],
            [
                ['callback_data' => 'data_test', 'text' => 'Кирилловка'],
				['callback_data' => 'data_test', 'text' => 'Константиновка'],
				['callback_data' => 'data_test', 'text' => 'Краматорск'],
            ],
            [
                ['callback_data' => 'data_test', 'text' => 'Курахово'],
				['callback_data' => 'data_test', 'text' => 'Марганец'],
				['callback_data' => 'data_test', 'text' => 'Мариуполь'],
            ],
            [
                ['callback_data' => 'data_test', 'text' => 'Мелитополь'],
				['callback_data' => 'data_test', 'text' => 'Михайловка'],
				['callback_data' => 'data_test', 'text' => 'Никополь'],
            ],
            [
                ['callback_data' => 'data_test', 'text' => 'Орехов'],
				['callback_data' => 'data_test', 'text' => 'Покров'],
				['callback_data' => 'data_test', 'text' => 'Пологи'],
            ],
            [
                ['callback_data' => 'data_test', 'text' => 'Приморск'],
				['callback_data' => 'data_test', 'text' => 'Токмак'],
				['callback_data' => 'data_test', 'text' => 'Херсон'],
            ],
            [
                ['callback_data' => 'data_test', 'text' => 'Энергодар'],				
            ],
        ],
        'titles' => [
            'rates' => 'Курсы валют', 
            'places' => 'Отделения в городах', 
            'site' => 'Сайт компании', 
        ],
    ];
}