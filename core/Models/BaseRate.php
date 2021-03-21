<?php

namespace App\Models;

use  \Illuminate\Database\Eloquent\Model;

class BaseRate extends Model
{
    protected $table = 'base_rates';
    protected $fillable = [
        'curr_id', 
        'rate_buy',
        'rate_sale',
        'rate_cross',
        'steps',
        'steps_cross_buy',
        'steps_cross_sale',
        'created_at',
    ];

    public function curr()
    {
        return $this->belongsTo(Curr::class, 'curr_id');
    }


    public function existsActual()
    {
        $count = self::where('created_at', self::max('created_at'))
            ->count();
        return $count > 0;    
    }

    public function actual()
    {
        return self::orderBy('curr_id', 'ASC')
            ->where('created_at', self::max('created_at'))
            ->get();
    }

    public function actualBase()
    {
        $currs = Curr::orderBy('id')
            ->where('is_actual', true)
            ->where('is_main', false)
            ->where('is_cross', false)
            ->get();
        foreach($currs as $curr) {
            $rate = self::where('curr_id', $curr->id)
                ->where('created_at', self::max('created_at'))->get();
            $curr['rate_buy'] = $rate[0]['rate_buy'];    
            $curr['rate_sale'] = $rate[0]['rate_sale'];    
        }    
        return $currs;
    }

    public function actualById($id)
    {
        $currs = Curr::where('id', $id)->get();
        $rates = self::where('curr_id', $id)
            ->where('created_at', self::max('created_at'))
            ->get(); 
        $currs['rate_buy'] = $rates[0]['rate_buy'];    
        $currs['rate_sale'] = $rates[0]['rate_sale'];    
        return $currs;
    }

}
