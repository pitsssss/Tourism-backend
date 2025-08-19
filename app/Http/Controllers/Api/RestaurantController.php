<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;

class RestaurantController extends Controller
{
    public function index()
    {
        return Restaurant::all();
    }

    public function show_details(Restaurant $restaurant)
    {
        return response()->json([
            'id' => $restaurant->id,
            'name' => $restaurant->name,
            'location' => $restaurant->location,
            'description' => $restaurant->description,
            'phone_number' => $restaurant->phone_number,
            'rating' => $restaurant->rating,
            'governorate' => $restaurant->governorate ? $restaurant->governorate->name : null,
            'image' => $restaurant->image ? asset('imgs/restaurant.img/' . $restaurant->image) : null,
            'additional_images' => $restaurant->images->map(function($img){
                return asset('imgs/restaurant.img/' . $img->image);
            }),
        ]);
    }
}
