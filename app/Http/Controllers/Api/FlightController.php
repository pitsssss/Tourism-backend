<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
    public function book(Request $request)
    {
         Auth::user();
        $request->validate([
            'flight_offer_id' => 'required|string',
            'travelers' => 'required|array',
            'payment_method_id' => 'required|string',
            'amount' => 'required|numeric',
        ]);

        $flightOfferId = $request->input('flight_offer_id');
        $travelers = $request->input('travelers');
        $paymentMethodId = $request->input('payment_method_id');
        $amount = $request->input('amount');

        $pricedFlightOffer = Cache::get("priced_flight_offer_{$flightOfferId}");

        if (!$pricedFlightOffer) {
            return response()->json(['success' => false, 'message' => 'No priced offer found'], 404);
        }

        try {
            // Stripe الدفع
            Stripe::setApiKey(config('STRIPE_SECRET'));

            $paymentIntent = PaymentIntent::create([
                'amount' => intval($amount * 100), // USD cents
                'currency' => 'usd',
                'payment_method' => $paymentMethodId,
                'confirmation_method' => 'manual',
                'confirm' => true,
            ]);

            if ($paymentIntent->status !== 'succeeded') {
                return response()->json(['success' => false, 'message' => 'Payment failed'], 400);
            }

            // Amadeus الحجز عبر
            $amadeusService = app(AmadeusService::class);
            $bookingResponse = $amadeusService->bookFlightOffer($pricedFlightOffer, $travelers);

            // حفظ الحجز في قاعدة البيانات
            $booking = Booking::create([
                'user_id' => auth()->id(),
                'flight_offer_id' => $flightOfferId,
                'travelers' => $travelers,
                'amadeus_response' => $bookingResponse,
                'payment_intent_id' => $paymentIntent->id,
                'amount' => $amount,
                'status' => 'succeeded',
            ]);

            return response()->json(['success' => true, 'data' => $booking]);

        } catch (\Exception $e) {
            // حفظ الحجز كـ failed
            Booking::create([
                'user_id' => auth()->id(),
                'flight_offer_id' => $flightOfferId,
                'travelers' => $travelers,
                'payment_intent_id' => $paymentIntent->id ?? null,
                'amount' => $amount,
                'status' => 'failed',
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Booking failed: ' . $e->getMessage(),
            ], 500);
        }
    }


}
