<?php

namespace App\Models;

use \Illuminate\Database\Eloquent\Model;

class Curr extends Model
{
    protected $table = 'currs';

    protected $fillable = [
        'short_name',
        'sign',
        'step_size',
        'step_size_format',
        'is_actual',
    ];

    public function actual()
    {
        return self::orderBy('id', 'ASC')
            ->where('is_main', false)
            ->where('is_actual', true)
            ->get();
    }

    public function isExists($short_name)
    {
        $count = self::where('short_name', $short_name)->count();
        return $count > 0;
    }
}