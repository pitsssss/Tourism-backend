<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
    protected $guard=[];
    public function trip()
    {
        return $this->belongsTo(Trips::class);
    }
    public function avtivity()
    {
        return $this->hasMany(Activity::class);
    }
}
