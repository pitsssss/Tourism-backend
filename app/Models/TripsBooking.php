<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TripsBooking extends Model
{
    protected $fillable = [
        'trip_id',
        'user_id',
        'tickets_count',
        'total_price',
        'status', // done / cancelled
    ];


    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
