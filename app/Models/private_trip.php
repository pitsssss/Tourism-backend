<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Private_Trip extends Model
{
    protected $table = 'private_trips';
    protected $fillable = ['user_id', 'governorate_id', 'trip_date_start','transportation_id','tour_guide_id','hotel_room_id','hotel_id','price'];

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

    public function privateTripsBooking()
 {
    return $this->hasMany(PrivateTripsBooking::class);
}

protected static function booted()
    {
        static::saving(function ($trip) {
            $trip->price = 0;

            $daysCount = $trip->days()->count();
            $daysCount = max(1, $daysCount);


            if ($trip->hotel_room_id) {
                $room = Hotel_Room::find($trip->hotel_room_id);
                if ($room) {
                    $trip->price += $room->price * $daysCount;
                }
            }


            if ($trip->transportation_id) {
                $transport = Transportation::find($trip->transportation_id);
                if ($transport) {
                    $trip->price += $transport->price_per_day * $daysCount;
                }
            }
        });
    }
}
