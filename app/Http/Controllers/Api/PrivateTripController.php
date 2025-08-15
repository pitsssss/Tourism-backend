<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\private_trip;
use App\Models\Transportation;
use Illuminate\Http\Request;


class PrivateTripController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'governorate_id' => 'required|exists:governorates,id',
            'trip_date_start' => 'required|date',
        ]);

        $trip = Private_trip::create([
            'user_id' => auth()->id(),
            'governorate_id' => $request->governorate_id,
            'trip_date_start' => $request->trip_date_start,
        ]);

        return response()->json([
            'message' => 'تم إنشاء الرحلة الخاصة بنجاح',
            'data' => $trip
        ], 201);
    }

    public function show(private_trip $private_trip)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(private_trip $private_trip)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, private_trip $private_trip)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(private_trip $private_trip)
    {
        //
    }
    public function getHotels(Private_trip $trip)
{
    $hotels = $trip->governorate->hotels;

     if ($hotels->isEmpty()) {
        return response()->json([
            'message' => 'لا توجد فنادق متاحة في هذه المحافظة حالياً.',
            'hotels' => []
        ]);
    }


    return response()->json([
        'message' => 'تم جلب الفنادق بنجاح.',
        'hotels' => $hotels
    ]);
}

 public function getTripGovernorateDetails(Private_trip $trip)
    {
        if (!$trip->governorate) {
            return response()->json([
                'message' => 'لا توجد محافظة مرتبطة بهذه الرحلة الخاصة.'
            ], 404);
        }

        $governorate = $trip->governorate;

        $restaurants = $governorate->restaurants;
        $activities  = $governorate->activities;
        $places      = $governorate->places;

        return response()->json([
            'message'     => 'تم جلب البيانات بنجاح.',
            'restaurants' => $restaurants->isNotEmpty() ? $restaurants : 'لا توجد مطاعم حالياً.',
            'activities'  => $activities->isNotEmpty()  ? $activities  : 'لا توجد أنشطة حالياً.',
            'places'      => $places->isNotEmpty()      ? $places      : 'لا توجد أماكن سياحية حالياً.',
        ]);
    }

    public function getTransportations()
{
    return response()->json(Transportation::all());
}

public function chooseTransportation(Request $request, Private_trip $privateTrip)
{
    $request->validate([
        'transportation_id' => 'required|exists:transportations,id',
    ]);

    $privateTrip->update([
        'transportation_id' => $request->transportation_id,
    ]);

    return response()->json(['message' => 'Transportation selected successfully']);
}

}
