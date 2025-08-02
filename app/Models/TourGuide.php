<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TourGuide extends Model
{

      protected $fillable = [
		'name',
        'phone',
        'rating',
		'image',
		//'trip_id',
	
	];
  public function trips()
    {
        return $this->hasMany(Trip::class);
    }

}
