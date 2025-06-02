<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TripImage extends Model
{
    
    protected $fillable = ['trip_id', 'image'];

     public function trip()
    {
       return $this->belongsTo(Trip::class, 'trip_id');
    }
}
