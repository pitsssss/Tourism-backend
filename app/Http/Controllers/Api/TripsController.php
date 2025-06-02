<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TripsResource;
use App\Models\Trip;
use Illuminate\Http\Request;

class TripsController extends Controller
{
    // public function index()
    // {
    //     $trips = Trips::all();
    //     return new TripsResource($trips);
    // }
    public function show($id)
    {
        $trips = Trip::findOrFail($id);
        return new TripsResource($trips);
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

    $trips = $query->with('category')->get();

    return response()->json($trips);
}

public function getTripsByCategory($categoryId)
{
    $trips = trip::where('category_id', $categoryId)
                ->select('id', 'name', 'image', 'price', 'start_date', 'description')
                ->get();

    return response()->json($trips);
}


public function getTripDetails($id)
{
    $trip = Trip::with([
        'images',
        'hotel:id,name',
        'days.activities'
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


}
