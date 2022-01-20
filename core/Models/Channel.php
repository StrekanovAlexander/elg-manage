<?php

namespace App\Models;

use  \Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    protected $table = 'channels';
    protected $fillable = [
      'place_id',
      'full_name', 
      'url', 
      'chat_id',
      'is_main',
      'is_actual',
    ];

    public function place()
    {
        return $this->belongsTo(Place::class, 'place_id');
    }

    public static function availablePlaces()
    {
      $channels = self::get();
      $arr = [];

      foreach ($channels as $channel) {
          $arr[] = $channel->place_id;
      }

      return Place::orderBy('full_name')
        ->where('is_actual', true)
        ->whereNotIn('id', $arr)
        ->get();
    }
    
}