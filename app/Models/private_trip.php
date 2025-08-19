<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class private_trip extends Model
{
    protected $fillable = ['user_id', 'governorate_id', 'trip_date_start','transportation_id','tour_guide_id','hotel_room_id','hotel_id'];

    public function governorate()
    {
        return $this->belongsTo(governorates::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function days()
{
    return $this->morphMany(Day::class, 'tripable');
}
public function transportations()
{
    return $this->belongsTo(Transportation::class);
}

 public function tourGuide()
    {
        return $this->belongsTo(TourGuide::class, 'tour_guide_id');
    }
public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
     public function hotelRoom()
    {
        return $this->belongsTo(Hotel_Room::class);
    }
}