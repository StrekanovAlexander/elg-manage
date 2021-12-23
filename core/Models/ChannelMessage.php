<?php

namespace App\Models;

use  \Illuminate\Database\Eloquent\Model;

class ChannelMessage extends Model
{
    protected $table = 'channel_messages';
    
    protected $fillable = [
      'channel_id',
      'content',
      'position',
      'is_actual',
    ];
    
}