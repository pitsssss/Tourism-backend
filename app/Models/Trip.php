<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TripImage;

class Trip extends Model
{
    protected $guard=[];
    public function days()
    {
        return $this->hasMany(Day::class);
    }

    public function hotel() {
    return $this->belongsTo(Hotel::class);
}

 public function images() {
       return $this->hasMany(TripImage::class, 'trip_id');
 }
    public function category() {
        return $this->belongsTo(Category::class);
    }

}
