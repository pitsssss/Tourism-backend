<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hotel extends Model {
    protected $guard=[];
    // protected $fillable = [
    //     'name', 'description', 'address', 'price', 'rating', 'image', 'location', 'phone_number'
    // ];

    // public function destination() {
    //     return $this->belongsTo(Destination::class);
    // }

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
    public function favorites() {
        return $this->hasMany(Favorite::class);
    }

public function hotels()
{
    return $this->hasMany(Hotel::class, 'governorate_id');
}

}
