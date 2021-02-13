<?php

namespace App\Models;

use  \Illuminate\Database\Eloquent\Model;

class CurrRule extends Model
{
    protected $table = 'curr_rules';
    protected $fillable = [
        'diff_buy',
        'diff_sale',
    ];

    public function curr()
    {
        return $this->belongsTo(Curr::class, 'curr_id');
    }

    public function place()
    {
        return $this->belongsTo(Place::class, 'place_id');
    }

    public function byPlace($id)
    {
        return self::where('is_actual', true)
            ->where('place_id', $id)
            ->get();
    }
    
}