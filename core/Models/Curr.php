<?php

namespace App\Models;

use \Illuminate\Database\Eloquent\Model;

class Curr extends Model
{
    protected $table = 'currs';

    public function actual()
    {
        return self::orderBy('id', 'ASC')
            ->where('is_main', false)
            ->where('is_actual', true)
            ->get();
    }
}