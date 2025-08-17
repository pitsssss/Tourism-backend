<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
       protected $fillable = ['name', 'start_time', 'end_time', 'description', 'image', 'governorate_id'];

    // public function days()
    // {
    //     return $this->belongsTo(Trip::class);
    // }

    //   public function day()
    // {
    //     return $this->belongsTo(Day::class);
    // }
    public function days()
{
    return $this->belongsToMany(Day::class, 'day_activities');
}


    public function images()
{
    return $this->hasMany(activity_images::class);
}
  public function governorate()
    {
        return $this->belongsTo(governorates::class,'governorate_id');
    }

}
