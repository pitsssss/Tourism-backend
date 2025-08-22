<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\PrivateTripsBooking;
use Illuminate\Support\Facades\Auth;
use App\Models\private_trip;
use App\Models\Transportation;
use Illuminate\Http\Request;
use App\Models\TourGuide;
use App\Models\Hotel_Room;
use App\Models\Day;
use Stripe\Stripe;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;

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

  public function show($id)
{
    $trip = Private_trip::with([
        'user:id,name,email',
        'governorate:id,name',
        'transportations:id,name',
        'tourGuide:id,name,phone,image',
        'hotel:id,name,location',
        'hotelRoom:id,hotel_id,room_type,price'
    ])->findOrFail($id);

    return response()->json($trip);
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

    $room = Hotel_Room::with('hotel')->findOrFail($request->hotel_room_id);

    if ($room->available_rooms < 1) {
        return response()->json(['message' => 'No available rooms'], 400);
    }

    $privateTrip->update([
        'hotel_room_id' => $room->id,
        'hotel_id'      => $room->hotel_id, // ← هون بنحط الفندق كمان
    ]);

    return response()->json([
        'message' => 'Room selected successfully',
        'private_trip' => $privateTrip->load('hotel', 'hotelRoom')
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

    $alreadyExists = false;

    switch ($request->type) {
        case 'activity':
           $alreadyExists = $day->activities()
    ->where('activities.id', $request->element_id)
    ->exists();
            if (!$alreadyExists) {
                $day->activities()->attach($request->element_id);
            }
            break;

        case 'place':
            $alreadyExists = $day->places()
        ->where('places.id', $request->element_id)
        ->exists();
            if (!$alreadyExists) {
                $day->places()->attach($request->element_id);
            }
            break;

        case 'restaurant':
           $alreadyExists = $day->restaurants()
        ->where('restaurants.id', $request->element_id)
        ->exists();
            if (!$alreadyExists) {
                $day->restaurants()->attach($request->element_id);
            }
            break;
    }

    if ($alreadyExists) {
        return response()->json([
            'message' => 'العنصر موجود مسبقاً في هذا اليوم'
        ], 400);
    }

    return response()->json(['message' => 'تمت إضافة العنصر لليوم بنجاح']);
}

public function bookTrip(Request $request)
    {
        $request->validate([
            'private_trip_id' => 'required|exists:private_trips,id',
            'tickets_count' => 'required|integer|min:1|max:10',

        ]);

        $trip = private_trip::findOrFail($request->private_trip_id);
        $ticketsCount = $request->tickets_count;

        $totalPrice = $trip->price * $ticketsCount;

        Stripe::setApiKey(env('STRIPE_SECRET'));
        $paymentIntent = PaymentIntent::create([
            'amount' => intval($totalPrice * 100),
            'currency' => 'usd',
        ]);

        return response()->json([
            'client_secret' => $paymentIntent->client_secret,
            // 'payment_intent_id' => $paymentIntent->id,
            'total_price' => $totalPrice,
        ]);
    }


    public function confirmBooking(Request $request)
    {
        $request->validate([
            'private_trip_id' => 'required|exists:trips,id',
            'tickets_count' => 'required|integer|min:1',
            'payment_intent_id' => 'required|string'
        ]);

        $trip = private_trip::findOrFail($request->trip_id);
        $ticketsCount = $request->tickets_count;

        Stripe::setApiKey(env('STRIPE_SECRET'));
        $payment = PaymentIntent::retrieve($request->payment_intent_id);

        if ($payment->status !== 'succeeded') {
            return response()->json(['message' => 'الدفع لم يتم بنجاح'], 400);
        }

        $booking = PrivateTripsBooking::create([
            'private_trip_id' => $trip->id,
            'user_id' => Auth::id(),
            'tickets_count' => $ticketsCount,
            'total_price' => $trip->price * $ticketsCount,
            'status' => 'done',
        ]);

        return response()->json([
            'message' => 'تم الحجز بنجاح',
            'booking' => $booking
        ]);
    }



    public function myBookings()
    {
        $user = Auth::user();

        $bookings = PrivateTripsBooking::with('private_trip') // جلب بيانات الرحلة المرتبطة
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $bookings
        ], 200);
    }




}
