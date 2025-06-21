<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class activity_images extends Model
{
    public function activity()
{
    return $this->belongsTo(Activity::class);
}
}
