<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transportation extends Model
{
  protected $fillable = ['name', 'image', 'price_per_day', 'rating', 'capacity'];

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    public function privateTrips()
    {
        return $this->hasMany(Private_trip::class);
    }
}

