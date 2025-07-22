<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class private_trip extends Model
{
    protected $fillable = ['user_id', 'governorate_id', 'trip_date_start'];

    public function governorate()
    {
        return $this->belongsTo(governorates::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function days()
{
    return $this->morphMany(Day::class, 'tripable');
}

}