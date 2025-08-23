<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Hotel extends Model {

     protected $fillable = [
         'name', 'description', 'rating', 'image', 'location', 'phone_number','governorate_id','extra_images','facilities'
     ];
     protected $casts = [
        'extra_images' => 'array',
        'facilities' => 'array',
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function bookings() {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
{
    return $this->morphMany(Review::class, 'reviewable');
}

public function governorate()
{
    return $this->belongsTo(Governorates::class);
}

public function favorites()
{
    return $this->morphMany(Favorite::class, 'favorable');
}

 public function rooms()
    {
        return $this->hasMany(Hotel_Room::class);
    }
    public function privateTrips()
    {
        return $this->hasMany(Private_Trip::class);
    }
}
