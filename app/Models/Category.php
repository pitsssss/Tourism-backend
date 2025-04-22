<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{


    public function hotels() {
        return $this->hasMany(Hotel::class);
    }

    public function restaurants() {
        return $this->hasMany(Restaurant::class);
    }


}
