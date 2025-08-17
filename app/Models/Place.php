<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    protected $fillable = ['name', 'location', 'description', 'category_id','image','governorate_id'];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function days()
    {
        return $this->belongsToMany(Day::class, 'day_place');
    }
 
    public function governorate()
    {
        return $this->belongsTo(governorates::class,'governorate_id');
    }

public function favorites()
{
    return $this->morphMany(Favorite::class, 'favorable');
}

}
