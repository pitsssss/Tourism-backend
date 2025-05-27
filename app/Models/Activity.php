<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $guard=[];
    public function days()
    {
        return $this->belongsTo(Trips::class);
    }
}
