<?php

namespace App\Models;

use  \Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    protected $table = 'channels';
    protected $fillable = [
      'full_name', 
      'url', 
      'chat_id',
      'is_actual',
    ];
    
}