<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use Auth;


class BookingController extends Controller
{
    public function index()
    {
        return Booking::with('user', 'hotel')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'hotel_id' => 'required|exists:hotels,id',
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
        ]);

        $booking = Booking::create($request->all());
        return response()->json($booking);
    }


    // public function getUserBookings()
    // {
    //     $bookings = Booking::where('user_id', Auth::id())->latest()->get();

    //     $formatted = $bookings->map(function ($booking) {
    //         $details = json_decode($booking->flight_details, true);
    //         $passengers = json_decode($booking->passenger_details, true);

    //         $segments = [];
    //         foreach ($details['flightOffers'][0]['itineraries'] as $itinerary) {
    //             foreach ($itinerary['segments'] as $segment) {
    //                 $segments[] = [
    //                     'from' => $segment['departure']['iataCode'],
    //                     'to' => $segment['arrival']['iataCode'],
    //                     'departure_time' => $segment['departure']['at'],
    //                     'arrival_time' => $segment['arrival']['at'],
    //                     'flight_number' => $segment['carrierCode'] . $segment['number'],
    //                 ];
    //             }
    //         }

    //         return [
    //             'booking_id' => $details['id'] ?? null,
    //             'total_amount' => $booking->amount,
    //             'currency' => $booking->currency,
    //             'passenger_count' => count($passengers),
    //             'itinerary' => $segments,
    //             'booked_at' => $booking->created_at->toDateTimeString(),
    //         ];
    //     });

    //     return response()->json([
    //         'success' => true,
    //         'bookings' => $formatted,
    //     ]);
    // }
}
