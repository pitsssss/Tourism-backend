<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\private_trip;
use App\Models\Transportation;
use Illuminate\Http\Request;
use App\Models\TourGuide;
use App\Models\Hotel_Room;
use App\Models\Day;

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

public function getTourGuides()
{
    $guides = TourGuide::select('id', 'name', 'phone', 'rating', 'image')->get();

    $guides->prepend([
        'id' => null,
        'name' => 'بدون دليل سياحي',
        'phone' => null,
        'rating' => null,
        'image' => null,
    ]);

    return response()->json($guides);
}

public function chooseTourGuide(Request $request, Private_trip $privateTrip)
{
    $request->validate([
        'tour_guide_id' => 'nullable|exists:tour_guides,id', 
    ]);

    $privateTrip->update([
        'tour_guide_id' => $request->tour_guide_id, 
    ]);

    return response()->json(['message' => 'Tour guide selected successfully']);
}

 public function chooseRoom(Request $request, Private_trip $privateTrip)
    {
        $request->validate([
            'hotel_room_id' => 'required|exists:hotel__rooms,id',
        ]);

        $room = Hotel_Room::findOrFail($request->hotel_room_id);

        if ($room->available_rooms < 1) {
            return response()->json(['message' => 'No available rooms'], 400);
        }
        
        $privateTrip->update([
            'hotel_room_id' => $room->id
        ]);

        return response()->json([
            'message' => 'Room selected successfully',
            'private_trip' => $privateTrip
        ]);
    }

     public function showDay($dayId)
    {
        $day = Day::with([
            'activities:id,name,start_time,end_time,description,image',
            'places:id,name,location,description,image',
            'restaurants:id,name,location,phone_number,rating,image'
        ])->findOrFail($dayId);

        return response()->json($day);
    }

    // إضافة عنصر جديد (Activity / Place / Restaurant) إلى يوم محدد
    public function addElement(Request $request, $dayId)
    {
        $day = Day::findOrFail($dayId);

        $request->validate([
            'type' => 'required|in:activity,place,restaurant',
            'element_id' => 'required|integer'
        ]);

        switch ($request->type) {
            case 'activity':
                $day->activities()->attach($request->element_id);
                break;
            case 'place':
                $day->places()->attach($request->element_id);
                break;
            case 'restaurant':
                $day->restaurants()->attach($request->element_id);
                break;
        }

        return response()->json(['message' => 'تم إضافة العنصر إلى اليوم بنجاح']);
    }

}
