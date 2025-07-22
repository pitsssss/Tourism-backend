<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    protected $fillable = ['name', 'location', 'description', 'category_id','image'];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

 
    public function governorate()
    {
        return $this->belongsTo(governorates::class,'governorate_id');
    }


}
