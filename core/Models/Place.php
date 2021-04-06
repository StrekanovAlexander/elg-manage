<?php

namespace App\Models;

use  \Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    protected $table = 'places';
    
    protected $fillable = [
        'full_name',
        'sign',
        'is_actual',
    ];

    public function byId($id)
    {
        return self::where('is_actual', true)->find($id);
    }

    public function actual()
    {
        return self::where('is_actual', true)
            ->orderBy('id')
            ->get();
    }
}