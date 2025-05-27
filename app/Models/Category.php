<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    protected $guard=[];
    public function trips()
    {
        return $this->hasMany(Trips::class);
    }

    public function hotels() {
        return $this->hasMany(Hotel::class);
    }

    public function restaurants() {
        return $this->hasMany(Restaurant::class);
    }


}
