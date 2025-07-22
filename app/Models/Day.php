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
    // public function avtivity()
    // {
    //     return $this->hasMany(Activity::class);
    // }

    public function activities()
{
    return $this->hasMany(Activity::class);
}
public function tripable()
{
    return $this->morphTo();
}


}
