<?php

namespace App\Models;

use  \Illuminate\Database\Eloquent\Model;

class BaseRate extends Model
{
    protected $table = 'base_rates';

    public function curr()
    {
        return $this->belongsTo(Curr::class, 'curr_id');
    }
}
