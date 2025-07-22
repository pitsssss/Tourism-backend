<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class user_trips extends Model
{

    protected $fillable = [
        'user_id',
        'trip_id',
        'type',
        'governorate_id',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function privateTrip()
    {
        return $this->belongsTo(Private_trip::class, 'trip_id'); 
    }

    public function governorate()
    {
        return $this->belongsTo(governorates::class);
    }

    // لتسهيل جلب الرحلة المرتبطة حسب النوع
    public function realTrip()
    {
        return $this->type === 'ready'
            ? $this->trip
            : $this->privateTrip;
    }

}
