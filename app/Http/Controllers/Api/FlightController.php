<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Flight;
use App\Models\FlightsBooking;
use Illuminate\Http\Request;
use App\Services\AmadeusService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;


class FlightController extends Controller
{
    protected $amadeusService;

public function __construct(AmadeusService $amadeusService)
{
    $this->amadeusService = $amadeusService;
}


    public function getFlights(Request $request, AmadeusService $amadeus)
    {
        $request->validate([
            'origin' => 'required|string|size:3',
            'destination' => 'required|string|size:3',
            'departureDate' => 'required|date',
            'returnDate' => 'nullable|date|after_or_equal:departureDate',
            'adults' => 'required|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'travelClass' => 'nullable|string|in:ECONOMY,PREMIUM_ECONOMY,BUSINESS,FIRST'
        ]);

        try {
            $params = $request->only([
                'origin', 'destination', 'departureDate', 'returnDate',
                'adults', 'children','travelClass'
            ]);

            $origin = $request->input('origin');
            $destination = $request->input('destination');
            // $departureDate = $request->input('departureDate');
            // $returnDate = $request->input('returnDate');
            // $adults = $request->input('adults');
            // $children = $request->input('children');
            // $infants = $request->input('infants');
            // $travelClass = $request->input('travelClass');

            $syrianAirports = ['DAM', 'ALP', 'LTK'];

            if (!in_array($origin, $syrianAirports) && !in_array($destination, $syrianAirports)) {
                return response()->json([
                    'success' => false,
                    'message' => 'At least one of origin or destination must be in Syria.'
                ], 400);
            }




            $flights = $amadeus->searchFlights($params);

            return response()->json([
                'success' => true,
                'data' => $flights
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function price(Request $request)
    {
        $flightOfferId = $request->input('flight_offer_id');

        try {
            $result = $this->amadeusService->priceFlightOffer($flightOfferId);

            return response()->json([
                'success' => true,
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }


    public function submitPassenger(Request $request)
    {
        $request->validate([
            'flight_offer_id' => 'required|string',
            'passengers' => 'required|array|min:1',
            'passengers.*.firstName' => 'required|string',
            'passengers.*.lastName' => 'required|string',
            'passengers.*.gender' => 'required|in:MALE,FEMALE',
            'passengers.*.dateOfBirth' => 'required|date',
            'passengers.*.email' => 'required|email',
            'passengers.*.phone' => 'required|string',
        ]);
        $flightOfferId = $request->flight_offer_id;
        $cacheKey = 'passenger_' . $flightOfferId;

        if (Cache::has($cacheKey)) {
            return response()->json([
                'success' => false,
                'message' => 'Passenger info already submitted for this flight.'
            ]);
        }

        // خزن بيانات الركاب في الكاش لمدة ساعة مثلاً
        Cache::put($cacheKey, [
            'passengers' => $request->passengers,
        ], now()->addHour());

        return response()->json([
            'success' => true,
            'message' => 'Passenger info saved successfully.',
        ]);
    }



    public function payAndBook(Request $request)
{
    $request->validate([
        'flight_offer_id' => 'required|string'
        // 'payment_method_id' => 'required|string',
    ]);

    $flightOfferId = $request->flight_offer_id;
    // $paymentMethodId = $request->payment_method_id;


    $alreadyBooked = FlightsBooking::where('user_id', Auth::id())
    ->where('flight_offer_id', $flightOfferId)
    ->exists();

if ($alreadyBooked) {
    return response()->json([
        'success' => false,
        'message' => 'You have already booked this flight.',
    ]);
}

    // استرجع بيانات الراكب المؤقتة
    $passengerData = Cache::get('passenger_' . $flightOfferId);
    if (!$passengerData) {
        return response()->json(['success' => false, 'message' => 'Passenger info not found.']);
    }
    try {
        // احصل على السعر
        $priceResult = $this->amadeusService->priceFlightOffer($flightOfferId);
        $amount = (float) str_replace(',', '', $priceResult['pricing']['total']['amount']);

        // Stripe
        Stripe::setApiKey(env('STRIPE_SECRET'));
        if ($amount < 0.5) {
            return response()->json(['message' => 'The amount is less than the minimum allowed (0.5 USD).
            '], 400);
        }

        $paymentIntent = PaymentIntent::create([
            'amount' => intval($amount * 100),
            'currency' => 'usd',
        ]);

        return response()->json([
            'client_secret' => $paymentIntent->client_secret,
            // 'payment_intent_id' => $paymentIntent->id,
            'total_price' => $amount,
        ]);

    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()]);
    }
}
            // 'payment_method' => $paymentMethodId,
            // 'payment_method_types' => ['card'],
            // 'confirmation_method' => 'manual',
            // 'confirm' => true


        // if ($intent->status !== 'succeeded') {
        //     return response()->json(['success' => false, 'message' => 'Payment failed.']);
        // }

    //     // ✅ بعد نجاح الدفع → احجز في Amadeus
    //     $bookingResult = $this->amadeusService->createBooking($passengerData['passengers'], $flightOfferId);

    //     // خزن في قاعدة البيانات
    //     $saved = Booking::create([
    //         'user_id' => Auth::id(),
    //         'amadeus_booking_id' => $bookingResult['id'] ?? null,
    //         'flight_offer_id' => $flightOfferId,
    //         'passenger_details' => json_encode($passengerData['passengers']),
    //         'flight_details' => json_encode($bookingResult),
    //         'payment_intent_id' => $intent->id,
    //         'amount' => $amount,
    //         'currency' => 'USD',
    //     ]);

    //     // تبسيط الرسبونس
    //     $segments = [];
    //     foreach ($bookingResult['flightOffers'][0]['itineraries'] as $itinerary) {
    //         foreach ($itinerary['segments'] as $segment) {
    //             $segments[] = [
    //                 'from' => $segment['departure']['iataCode'],
    //                 'to' => $segment['arrival']['iataCode'],
    //                 'departure_time' => $segment['departure']['at'],
    //                 'arrival_time' => $segment['arrival']['at'],
    //                 'flight_number' => $segment['carrierCode'] . $segment['number']
    //             ];
    //         }
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Payment & booking successful.',
    //         'booking' => [
    //             'booking_id' => $bookingResult['id'] ?? null,
    //             'total_amount' => $amount,
    //             'currency' => 'USD',
    //             'passenger_count' => count($passengerData['passengers']),
    //             'itinerary' => $segments
    //         ]
    //     ]);



public function confirmBooking(Request $request)
{
    $request->validate([
        'flight_offer_id' => 'required|string',
        'payment_intent_id' => 'required|string'
    ]);

    // $flight = Flight::findOrFail($request->flight_offer_id);
    $flightOfferId = $request->flight_offer_id;
    // $amount = $request->amount;
    $passengerData = Cache::get('passenger_' . $flightOfferId);

    Stripe::setApiKey(env('STRIPE_SECRET'));
    $payment = PaymentIntent::retrieve($request->payment_intent_id);
    $amount = $payment->amount / 100;
     if ($payment->status !== 'succeeded') {
        return response()->json(['message' => 'Payment was not completed successfully'], 400);
    }

    $booking = FlightsBooking::create([
        'user_id'            => Auth::id(),
        'flight_offer_id'    => $flightOfferId,
        'passenger_details'  => $passengerData ,
        'payment_intent_id'  => $request->payment_intent_id,
        'amount'             => $amount,
        'currency'           => 'USD',
        'status'             => 'done',
    ]);


    return response()->json([
        'message' => 'Payment completed successfully',
        'booking' => $booking
    ]);
}



public function cancelBooking(string $bookingId)
{
    try {
        $booking = FlightsBooking::where('user_id', Auth::id())
            ->where('amadeus_booking_id', $bookingId)
            ->first();

        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Booking not found.'], 404);
        }

        if ($booking->status === 'cancelled') {
            return response()->json(['success' => false, 'message' => 'Booking is already cancelled.'], 400);
        }

        // الغاء الحجز من Amadeus
        $this->amadeusService->cancelBooking($bookingId);

        Stripe::setApiKey(env('STRIPE_SECRET'));
        $payment =PaymentIntent::retrieve($booking->payment_intent_id);
            if ($payment->status !== 'succeeded') {
            return response()->json(['success' => false, 'message' => 'Payment not successful, cannot refund.'], 400);
         }


        // إرجاع المال من Stripe
        \Stripe\Refund::create([
            'payment_intent' => $booking->payment_intent_id
        ]);

        // تحديث الحجز في قاعدة البيانات
        $booking->update(['status' => 'cancelled']);

        return response()->json([
            'success' => true,
            'message' => 'Booking cancelled and refunded successfully.'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Cancellation failed: ' . $e->getMessage()
        ], 500);
    }
}



}
