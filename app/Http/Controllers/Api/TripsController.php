<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TripsResource;
use App\Models\Trip;
use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\TourGuide;
class TripsController extends Controller
{


  public function getAllTrips()
{
      $trips = Trip::select('id', 'image', 'name', 'description', 'price', 'start_date')
            ->get();
    return response()->json($trips);
}


public function getTripsByCategory($categoryId)
{
   $trips = Trip::where('category_id', $categoryId)
                    ->select('id', 'name', 'image', 'price', 'start_date', 'description')
                    ->get();


    return response()->json($trips);
}
public function getTripDetails($id)
{
    $trip = Trip::with([
        'images:id,trip_id,image',
        'hotel:id,name',
        'transportation:id,name', 
        'tourGuide:id,name,image,phone,rating',
         'days:id,tripable_id,tripable_type,name,date',
        'days.activities:id,name,start_time,end_time,description,image',
        'days.places:id,name,location,description,image',
        'days.restaurants:id,name,description,location,phone_number,rating,image',
    ])
    ->select('id', 'image', 'name', 'price', 'start_date', 'hotel_id', 'transportation_id', 'guide_id', 'description')
    ->findOrFail($id);

    $tripArray = $trip->toArray();

    $tripArray['transportation'] = $trip->transportation->name ?? null;

    return response()->json($tripArray);
}
    public function showTripsSorted(Request $request)
{
    $query = Trip::query();

    // Sort by price
    if ($request->has('sort_by')) {
        $sortField = $request->get('sort_by');
        $sortOrder = $request->get('order', 'asc'); // default: ascending

        if (in_array($sortField, ['price', 'created_at'])) {
            $query->orderBy($sortField, $sortOrder);
        }

        // Custom sort: by number of days
        if ($sortField == 'days') {
            $query->orderBy('count_days', $sortOrder);
        }
    }

    $trips = $query->with('category')->get();

    return response()->json($trips);
}



public function showCategoryTripsSorted(Request $request, $categoryId)
{
    $query = Trip::where('category_id', $categoryId); 

    if ($request->has('sort_by')) {
        $sortField = $request->get('sort_by');
        $sortOrder = $request->get('order', 'asc');

        if (in_array($sortField, ['price', 'created_at'])) {
            $query->orderBy($sortField, $sortOrder);
        }

        if ($sortField == 'days') {
            $query->orderBy('count_days', $sortOrder);
        }
    }

    $trips = $query->with('category')->get();

    return response()->json($trips);
}

public function show_activity_details($id)
{
    $activity = Activity::with('images')->findOrFail($id);

    return response()->json([
        'name' => $activity->name,
        'description' => $activity->description,
        'start_time' => $activity->start_time,
        'end_time' => $activity->end_time,
        'image' => $activity->image,
        'gallery' => $activity->images->pluck('image_path'), 
    ]);
}


}
