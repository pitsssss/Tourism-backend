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
      $trips = Trip::with('TourGuide:id,name,phone,rating,image')
                ->select('id', 'image', 'name', 'price', 'description', 'start_date', 'guide_id')
                ->get();
    return response()->json($trips);
}

   
    public function show($id)
   {
    $trip = Trip::with('TourGuide:id,name,phone,rating,image')->findOrFail($id); 
    return new TripsResource($trip);
}
    // public function search(Request $request)
    // {
    //     $keyword = $request->input('keyword');

    //     $results = Trips::where('name', 'like', "%$keyword%")
    //         ->orWhere('location', 'like', "%$keyword%")
    //         ->get();

    //     return response()->json($results);
    // }

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

    $trips = $query->with('category','guide')->get();

    return response()->json($trips);
}

public function getTripsByCategory($categoryId)
{
   $trips = Trip::where('category_id', $categoryId)
                    ->with('guide:id,name,phone,rating,image')
                    ->select('id', 'name', 'image', 'price', 'start_date', 'description', 'guide_id')
                    ->get();


    return response()->json($trips);
}


public function getTripDetails($id)
{
    $trip = Trip::with([
        'images',
        'hotel:id,name',
        'days.activities',
         'guide',
    ])->findOrFail($id);

    $result = [
        'id' => $trip->id,
        'name' => $trip->name,
        'transport' => $trip->transport,
        'price' => $trip->price,
        'start_date' => $trip->start_date,
        'count_days' => $trip->count_days,
        'hotel_name' => $trip->hotel->name ?? null,
        'images' => $trip->images->pluck('image'),

 'tour_guide' => $trip->guide  ? [
            'name' => $trip->guide->name,
            'phone' => $trip->guide->phone,
            'rating' => $trip->guide->rating,
            'image' => $trip->guide->image,
        ] : null,

        'days' => $trip->days->map(function ($day) {
            return [
                'id' => $day->id,
                'name' => $day->name,
                'date' => $day->date,
                'activities' => $day->activities->map(function ($activity) {
                    return [
                        'id' => $activity->id,
                        'name' => $activity->name,
                        'start_time' => $activity->start_time,
                        'end_time' => $activity->end_time,
                        'description' => $activity->description,
                        'image' => $activity->image,
                    ];
                }),
            ];
        }),
    ];

    return response()->json($result);
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

    $trips = $query->with('category','guide')->get();

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
        'main_image' => $activity->image,
        'gallery' => $activity->images->pluck('image_path'), 
    ]);
}


}
