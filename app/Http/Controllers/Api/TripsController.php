<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TripsResource;
use App\Models\Trips;
use Illuminate\Http\Request;

class TripsController extends Controller
{
    public function index()
    {
        $trips = Trips::all();
        return new TripsResource($trips);
    }
    public function show($id)
    {
        $trips = Trips::findOrFail($id);
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
    $query = Trips::query();

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
}
