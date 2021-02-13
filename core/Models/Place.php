<?php

namespace App\Models;

use  \Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    protected $table = 'places';

    public function byId($id)
    {
        return self::where('is_actual', true)->find($id);
    }
}