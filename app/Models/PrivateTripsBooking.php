<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrivateTripsBooking extends Model
{
    protected $fillable = [
        'private_trip_id',
        'user_id',
        'tickets_count',
        'total_price',
        'status', // done / cancelled
    ];


    public function private_trip()
    {
        return $this->belongsTo(private_trip::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
