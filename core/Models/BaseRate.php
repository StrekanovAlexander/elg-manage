<?php

namespace App\Models;

use  \Illuminate\Database\Eloquent\Model;

class BaseRate extends Model
{
    protected $table = 'base_rates';
    protected $fillable = [
        'curr_id', 
        'rate_buy',
        'steps',
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

}
