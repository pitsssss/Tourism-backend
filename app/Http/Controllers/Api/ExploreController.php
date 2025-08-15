<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Trip;
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

      public function search(Request $request)
    {
        $query = $request->input('q'); // الكلمة اللي بدك تبحثي فيها

        // البحث في الفنادق
        $hotels = Hotel::where('name', 'LIKE', "%{$query}%")
                        ->orWhere('description', 'LIKE', "%{$query}%")
                        ->get();

        // البحث في الرحلات
        $trips = Trip::where('name', 'LIKE', "%{$query}%")
                     ->orWhere('description', 'LIKE', "%{$query}%")
                     ->get();

        // البحث في الأنشطة
        $activities = Activity::where('name', 'LIKE', "%{$query}%")
                              ->orWhere('description', 'LIKE', "%{$query}%")
                              ->get();

        // نفس الشي للمطاعم والأماكن
        $restaurants = Restaurant::where('name', 'LIKE', "%{$query}%")
                                 ->orWhere('description', 'LIKE', "%{$query}%")
                                 ->get();

        $places = Place::where('name', 'LIKE', "%{$query}%")
                       ->orWhere('description', 'LIKE', "%{$query}%")
                       ->get();

        return response()->json([
            'hotels' => $hotels,
            'trips' => $trips,
            'activities' => $activities,
            'restaurants' => $restaurants,
            'places' => $places,
        ]);
    }
}


