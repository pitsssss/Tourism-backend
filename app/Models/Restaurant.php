<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
        protected $fillable = ['name', 'description', 'location', 'phone_number', 'rating', 'image', 'governorate_id'];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
{
    return $this->morphMany(Review::class, 'reviewable');
}

    
      public function governorate()
    {
        return $this->belongsTo(governorates::class,'governorate_id');
    }
public function favorites()
{
    return $this->morphMany(Favorite::class, 'favorable');
}

 public function days()
    {
        return $this->belongsToMany(Day::class, 'day_restaurant');
    }
}
