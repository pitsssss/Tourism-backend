<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
   protected $fillable = ['name', 'TripDay', 'tripable_id', 'tripable_type', 'date'];

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
