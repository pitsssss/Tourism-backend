<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trips extends Model
{
    protected $guard=[];
    public function days()
    {
        return $this->hasMany(Day::class);
    }
    public function category() {
        return $this->belongsTo(Category::class);
    }

}
