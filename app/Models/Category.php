<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    protected $fillable = ['name'];
    public function places()
    {
        return $this->hasMany(Place::class);
    }

    public function hotels() {
        return $this->hasMany(Hotel::class);
    }

    public function restaurants() {
        return $this->hasMany(Restaurant::class);
    }


}
