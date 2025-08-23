<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
   protected $fillable = ['name', 'TripDay', 'tripable_id', 'tripable_type', 'date'];


   protected static function booted(): void
   {
       static::created(function ($day) {
           $trip = $day->tripable;
           if ($trip instanceof Private_Trip) {
               $trip->save();
           }
       });

       static::deleted(function ($day) {
           $trip = $day->tripable;
           if ($trip instanceof Private_Trip) {
               $trip->save();
           }
       });
   }


    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

  public function activities()
{
    return $this->belongsToMany(Activity::class, 'day_activities');
}
public function tripable()
{
    return $this->morphTo();
}
public function places()
{
    return $this->belongsToMany(Place::class, 'day_places');
}

public function restaurants()
{
    return $this->belongsToMany(Restaurant::class, 'day_restaurants');
}

}
