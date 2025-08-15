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

public function destroyDay(Private_trip $trip, $dayId)
{
    // جلب اليوم للتأكد إنه موجود
    $day = $trip->days()->where('id', $dayId)->first();

    if (!$day) {
        return response()->json(['message' => 'اليوم غير موجود لهذه الرحلة'], 404);
    }

    // حذف اليوم
    $day->delete();

    // إعادة ترتيب باقي الأيام
    $days = $trip->days()->orderBy('date')->get();
    $tripStartDate = \Carbon\Carbon::parse($trip->trip_date_start);

    foreach ($days as $index => $d) {
        // إعادة التسمية Day1, Day2, ...
        $d->name = 'Day' . ($index + 1);

        // تعديل التاريخ حسب الترتيب الجديد
        $d->date = $tripStartDate->copy()->addDays($index);

        $d->save();
    }

    return response()->json(['message' => 'تم حذف اليوم وإعادة ترتيب الأيام بنجاح']);
}

}
