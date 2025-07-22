<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\user_trips;
use Illuminate\Http\Request;

class UserTripsController extends Controller
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
    public function store(Request $request)//تثبيت الحجز
    {
    
  
}


    

    /**
     * Display the specified resource.
     */
    public function show(user_trips $user_trips)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(user_trips $user_trips)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, user_trips $user_trips)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(user_trips $user_trips)
    {
        //
    }



     public function bookTrip(Request $request)//تثبيت الحجز
    {
    
    $request->validate([
        'trip_id' => 'nullable|exists:trips,id',
        'private_trip_id' => 'nullable|exists:private_trips,id',
        'governorate_id' => 'nullable|exists:governorates,id',
    ]);

    $user = Auth::user();

    $type = $request->trip_id ? 'ready' : 'custom';

    $trip = user_trips::create([
        'user_id' => $user->id,
        'trip_id' => $request->trip_id,
        'private_trip_id' => $request->private_trip_id,
        'governorate_id' => $request->governorate_id,
        'type' => $type,
        'status' => 'upcoming', 
    ]);

    return response()->json([
        'message' => 'تم تثبيت الرحلة بنجاح',
        'data' => $trip
    ], 201);
}


   public function upcomingTrips()
{
    $userId = auth()->id(); 

    if (!$userId) {
        return response()->json([
            'message' => 'يجب تسجيل الدخول لعرض الرحلات القادمة.'
        ], 401);
    }

    $trips = user_trips::where('user_id', $userId)
                       ->where('status', 'upcoming')
                       ->get();

    if ($trips->isEmpty()) {
        return response()->json([
            'message' => 'لا توجد رحلات قادمة حالياً.',
            'trips' => []
        ]);
    }

    return response()->json([
        'message' => 'تم جلب الرحلات القادمة بنجاح.',
        'trips' => $trips
    ]);
}


}
