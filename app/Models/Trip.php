<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TripImage;

class Trip extends Model
{
   protected $fillable = [
		'name',
        'transport',
        'description',
		'start_date',
		'image',
		'hotel_id',
		'category_id',
        'price',
        'count_days',
        'governorate_id',
        'guide_id'
        
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

public function guide()
{
    return $this->belongsTo(TourGuide::class, 'guide_id');
}

}
