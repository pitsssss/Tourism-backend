<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    // public function destination() {
    //     return $this->belongsTo(Destination::class);
    // }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
{
    return $this->morphMany(Review::class, 'reviewable');
}

    public function favorites() {
        return $this->hasMany(Favorite::class);
    }

}
