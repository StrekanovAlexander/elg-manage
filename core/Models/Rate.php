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
    
}