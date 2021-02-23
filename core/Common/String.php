<?php

namespace App\Common;

class String
{
    public static function cleanParams($params, $filtered, $chanks)
    {
        $str = '';

        foreach($params as $key => $value) {
            if (in_array($key, $filtered)) continue;
            // if (($key == 'csrf_name') || ($key == 'csrf_value')) continue; 
            $str .= $value . '|';
        }

        $arr = explode('|', mb_substr($str, 0, -1));
        return array_chunk($arr, $chanks);

    }

    public static function setTD($item, $align='')
    {
        $al = $align ? ' align=' . $align : ''; 
        return '<td'. $al .'>' . $item . '</td>';
    }

    public static function setTR($items = '')
    {
        return '<tr>' . $items . '</tr>';
    }

    public static function setTB($items)
    {
        return '<table border="1">' . $items . '</table>';
    }
}


