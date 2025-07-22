<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\private_trip;
class DayController extends Controller
{
    

public function store(Request $request, Private_trip $trip)
{
    
    $existingDaysCount = $trip->days()->count();


    $dayName = 'Day' . ($existingDaysCount + 1);

    
    $tripStartDate = Carbon::parse($trip->trip_date_start);
 
    $dayDate = $tripStartDate->copy()->addDays($existingDaysCount);

    $day = $trip->days()->create([
        'name' => $dayName,
        'date' => $dayDate,
        'TripDay' => $request->input('TripDay'),
    ]);

    return response()->json(['message' => 'تمت إضافة اليوم بنجاح', 'day' => $day], 201);
}

}
