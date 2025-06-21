<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hotel;
use App\Models\Restaurant;
use App\Models\Activity;
use App\Models\Place;

class ExploreController extends Controller
{
    
    public function getAllHotels()
    {
        $hotels = Hotel::with('category')->get();
        return response()->json($hotels);
    }

   
    public function getAllRestaurants()
    {
        $restaurants = Restaurant::with('category')->get();
        return response()->json($restaurants);
    }

   
    public function getAllActivities()
    {
        $activities = Activity::with('images')->get();
        return response()->json($activities);
    }

   
    public function getAllPlaces()
    {
        $places = Place::all();
        return response()->json($places);
    }
}


