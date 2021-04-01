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
        'rate_base_buy',
        'rate_base_sale',
        'rate_cross',
        'rate_cross_buy',
        'rate_cross_sale',
        'steps',
        'steps_cross_buy',
        'steps_cross_sale',
        'is_cross',
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

    public function bases()
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
            $curr['rate_cross'] = $rate[0]['rate_cross'];   
            $curr['rate_cross_buy'] = $rate[0]['rate_cross_buy'];    
            $curr['rate_cross_sale'] = $rate[0]['rate_cross_sale']; 
            $curr['steps_cross_buy'] = $rate[0]['steps_cross_buy'];    
            $curr['steps_cross_sale'] = $rate[0]['steps_cross_sale']; 
            $curr['is_cross'] = $rate[0]['is_cross'];   
        }    
        return $currs;
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
            $curr['rate_cross'] = $rate[0]['rate_cross'];   
            $curr['rate_cross_buy'] = $rate[0]['rate_cross_buy'];    
            $curr['rate_cross_sale'] = $rate[0]['rate_cross_sale']; 
            $curr['steps_cross_buy'] = $rate[0]['steps_cross_buy'];    
            $curr['steps_cross_sale'] = $rate[0]['steps_cross_sale']; 
            $curr['is_cross'] = $rate[0]['is_cross'];   
        }    
        return $currs;
    }

    public function actualBaseCross()
    {
        $currs = Curr::orderBy('id')
            ->where('is_actual', true)
            ->where('is_cross', true)
            ->get();
        foreach($currs as $curr) {
            $rate = self::where('curr_id', $curr->id)
                ->where('created_at', self::max('created_at'))->get();
            $curr['rate_buy'] = $rate[0]['rate_buy'] ? $rate[0]['rate_buy'] : 0 ;    
            $curr['rate_sale'] = $rate[0]['rate_sale'] ? $rate[0]['rate_sale'] : 0; 
            $curr['rate_cross'] = $rate[0]['rate_cross'] ? $rate[0]['rate_cross']: 0;   
            $curr['rate_cross_buy'] = $rate[0]['rate_cross_buy'] ? $rate[0]['rate_cross_buy'] : 0;    
            $curr['rate_cross_sale'] = $rate[0]['rate_cross_sale'] ? $rate[0]['rate_cross_sale'] : 0; 
            $curr['steps_cross_buy'] = $rate[0]['steps_cross_buy'] ? $rate[0]['steps_cross_buy'] : 0;    
            $curr['steps_cross_sale'] = $rate[0]['steps_cross_sale'] ? $rate[0]['steps_cross_sale'] : 0; 
            $curr['is_cross'] = $rate[0]['is_cross'];   
        }    
        return $currs;
    }

    public function actualById($id)
    {
        $currs = Curr::find($id);
        $rates = self::where('curr_id', $id)
            ->where('created_at', self::max('created_at'))
            ->get(); 
        $currs['rate_buy'] = $rates[0]['rate_buy'];    
        $currs['rate_sale'] = $rates[0]['rate_sale'];    
        return $currs;
    }

    public static function drop($id, $created_at)
    {
        self::where('curr_id', $id)
            ->where('created_at', $created_at)
            -> delete();
        return 0;
    }

}
