<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['user_id', 'content'];
    public function user() {
        return $this->belongsTo(User::class);
    }

    // One review is for either a hotel or a restaurant
    public function reviewable() {
        return $this->morphTo();
    }

}
