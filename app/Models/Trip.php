<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TripImage;

class Trip extends Model
{
   protected $fillable = [
		'name',
        //'transport',
        'description',
		'start_date',
		'image',
		'hotel_id',
		'category_id',
        'price',
        'count_days',
        'governorate_id',
        'guide_id',
        'room_id',
        'transportation_id'

	];

    public function hotel() {
    return $this->belongsTo(Hotel::class);
}

 public function images() {
       return $this->hasMany(TripImage::class, 'trip_id');
 }
    public function category() {
        return $this->belongsTo(Category::class);
    }

public function governorate()
{
    return $this->belongsTo(governorates::class);
}

public function days()
{
    return $this->morphMany(Day::class, 'tripable');
}

public function TourGuide()
{
    return $this->belongsTo(TourGuide::class, 'guide_id');
}

public function transportation()
{
    return $this->belongsTo(Transportation::class);
}

public function favorites()
{
    return $this->morphMany(Favorite::class, 'favorable');
}
public function room()
    {
        return $this->belongsTo(Hotel_Room::class, 'room_id');
    }
    public function tripsbooking()
 {
    return $this->hasMany(TripsBooking::class);
}
}
