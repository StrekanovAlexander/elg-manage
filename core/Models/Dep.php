<?php

namespace App\Models;

use  \Illuminate\Database\Eloquent\Model;

class Dep extends Model
{
    protected $table = 'deps';
    protected $fillable = [
        'full_name', 
        'place_id', 
        'email',
        'chat_id',
        'is_mail',
        'is_chat',
        'is_actual',
    ];

    public function place()
    {
        return $this->belongsTo(Place::class, 'place_id');
    }
}