<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = ['user_id', 'hotel_id', 'check_in', 'check_out', 'guests'];

    public function hotel()
{
    return $this->belongsTo(Hotel::class);
}
public function user()
{
    return $this->belongsTo(User::class);
}

}
