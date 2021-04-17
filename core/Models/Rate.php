<?php

namespace App\Models;

use  \Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    protected $table = 'rates';
    
    public function curr()
    {
        return $this->belongsTo(Curr::class, 'curr_id');
    }

    public function place()
    {
        return $this->belongsTo(Place::class, 'place_id');
    }

    public function actualByPlace($id)
    {
        return self::orderBy('curr_id', 'ASC')
            ->where('place_id', $id)
            ->where('created_at', self::max('created_at'))
            ->get();
    }

    public function actualByCurr($id)
    {
        return self::orderBy('place_id', 'ASC')
            ->where('curr_id', $id)
            ->where('created_at', self::max('created_at'))
            ->get();
    }

    public static function ratesToStr($rates)
    {
        $s = sprintf("\n%-10s  %' 10s  %' 10s","Валюта","Покупка","Продажа");
        foreach ($rates as $rate) {
            $s .= sprintf(
            "\n%-10s %' 9s  %' 9s", 
            \App\Models\Curr::iconed($rate->curr),
            number_format($rate['rate_buy'], $rate->curr->precision_size), 
            number_format($rate['rate_sale'], $rate->curr->precision_size)
            );
        }

        return $s;
    }
    
}