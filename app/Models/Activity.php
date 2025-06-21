<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $guard=[];
    public function days()
    {
        return $this->belongsTo(Trip::class);
    }

      public function day()
    {
        return $this->belongsTo(Day::class);
    }

    public function images()
{
    return $this->hasMany(activity_images::class);
}

}
