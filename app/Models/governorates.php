<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class governorates extends Model
{
    protected $fillable = ['name'];

    public function user_trips()
    {
        return $this->hasMany(user_trips::class);
    }
    public function places()
    {
        return $this->hasMany(Place::class, 'governorate_id');
    }
   public function hotels()
    {
        return $this->hasMany(Hotel::class, 'governorate_id');
    }
     public function restaurants()
    {
        return $this->hasMany(Restaurant::class,'governorate_id');
    }
     public function activities()
    {
        return $this->hasMany(Activity::class,'governorate_id');
    }
}
