<?php

namespace App\Models;

use \Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';
    
    protected $fillable = [
        'prefix',
        'content',
        'position',
        'is_actual',
    ];
    
}