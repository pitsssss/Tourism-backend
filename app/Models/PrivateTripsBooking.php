<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrivateTripsBooking extends Model
{

    protected $table = 'private_trips_bookings';
    protected $fillable = [
        'private_trip_id',
        'user_id',
        // 'tickets_count',
        'total_price',
        'status', // done / cancelled
    ];


    public function private_trip()
    {
        return $this->belongsTo(Private_Trip::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }




}
