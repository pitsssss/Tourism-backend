<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hotel_Room extends Model
{
     protected $fillable = [
        'hotel_id',
        'room_type',
        'capacity',
        'price',
        'available_rooms',
        'total_rooms',];

        public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
}
