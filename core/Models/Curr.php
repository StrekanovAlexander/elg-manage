<?php

namespace App\Models;

use \Illuminate\Database\Eloquent\Model;

class Curr extends Model
{
    protected $table = 'currs';

    protected $fillable = [
        'base_curr_id',
        'rel_curr_id',
        'color_id',
        'short_name',
        'sign',
        'step_size',
        'step_size_format',
        'oper_cross',
        'is_cross',
        'is_actual',
    ];

    public function color()
    {
        return $this->belongsTo(ColorCss::class, 'color_id');
    }

    public function base()
    {
        return $this->belongsTo(self::class, 'base_curr_id');
    }

    public function rel()
    {
        return $this->belongsTo(self::class, 'rel_curr_id');
    }

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

    public function crossShortName($base_id, $rel_id)
    {
        $base = self::find($base_id);
        $rel = self::find($rel_id);
        return $base->short_name . '/' . $rel->short_name;
    }

}