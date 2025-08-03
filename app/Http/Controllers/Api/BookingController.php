<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use App\Models\Booking;

class BookingController extends Controller
{

  // عرض جميع حجوزات المستخدم
//   public function index(Request $request)
//   {
//       $userId = auth()->id();

//       $bookings = Booking::where('user_id', $userId)
//           ->orderBy('created_at', 'desc')
//           ->get();

//       return response()->json([
//           'success' => true,
//           'data' => $bookings,
//       ]);
//   }

  public function getUserBookings()
{
    $bookings = Booking::where('user_id', Auth::id())->latest()->get();

    $formatted = $bookings->map(function ($booking) {
        $details = json_decode($booking->flight_details, true);
        $passengers = json_decode($booking->passenger_details, true);

        $segments = [];
        foreach ($details['flightOffers'][0]['itineraries'] as $itinerary) {
            foreach ($itinerary['segments'] as $segment) {
                $segments[] = [
                    'from' => $segment['departure']['iataCode'],
                    'to' => $segment['arrival']['iataCode'],
                    'departure_time' => $segment['departure']['at'],
                    'arrival_time' => $segment['arrival']['at'],
                    'flight_number' => $segment['carrierCode'] . $segment['number'],
                ];
            }
        }

        return [
            'booking_id' => $details['id'] ?? null,
            'total_amount' => $booking->amount,
            'currency' => $booking->currency,
            'passenger_count' => count($passengers),
            'itinerary' => $segments,
            'booked_at' => $booking->created_at->toDateTimeString(),
        ];
    });

    return response()->json([
        'success' => true,
        'bookings' => $formatted,
    ]);
}



}
