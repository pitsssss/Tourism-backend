<?php

namespace App\Services;

use DateInterval;
use Exception;
use Illuminate\Support\Facades\Http;
use Log;
use Mockery\Matcher\Type;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Stripe\Stripe;

class AmadeusService
{
    public function getAccessToken(): string
    {
        // تحقق من الكاش أولاً
        if (Cache::has('amadeus_access_token')) {
            return Cache::get('amadeus_access_token');
        }

        $clientId = env('AMADEUS_API_KEY');
        $clientSecret = env('AMADEUS_API_SECRET');

        // أرسل طلب التوكن
        $response = Http::asForm()->post('https://test.api.amadeus.com/v1/security/oauth2/token', [
            'grant_type'    => 'client_credentials',
            'client_id'     => $clientId,
            'client_secret' => $clientSecret,
        ]);

        if ($response->failed()) {
            throw new \Exception("Amadeus token request failed: " . $response->body());
        }

        $data = $response->json();

        // تحقق من وجود التوكن
        if (empty($data['access_token']) || empty($data['expires_in'])) {
            throw new \Exception("Invalid token response: " . json_encode($data));
        }

        // خزنه بالكاش لمدة أقل بدقيقة واحدة من انتهاء الصلاحية
        $ttl = max(60, $data['expires_in'] - 60); // أقل مدة تخزين 1 دقيقة

        Cache::put('amadeus_access_token', $data['access_token'], now()->addSeconds($ttl));

        return $data['access_token'];
    }




    public function searchFlights($params)
    {

        $token = $this->getAccessToken();

        // Validate and set travel class
        $validClasses = ['ECONOMY', 'PREMIUM_ECONOMY', 'BUSINESS', 'FIRST'];
        $travelClass = $params['travelClass'] ?? 'ECONOMY';

        if (!in_array(strtoupper($travelClass), $validClasses)) {
            $travelClass = 'ECONOMY'; // Fallback to economy if invalid class provided
        }

        $query = [
            'originLocationCode' => strtoupper($params['origin']),
            'destinationLocationCode' => strtoupper($params['destination']),
            'departureDate' => $params['departureDate'],
            'adults' => $params['adults'] ?? 1,
            'children' => $params['children'] ?? 0,
            'travelClass' => $travelClass, // Now properly validated
            'currencyCode' => 'USD',
            'max' => 10,
        ];

        if (!empty($params['returnDate'])) {
            $query['returnDate'] = $params['returnDate'];
        }

        $response = Http::withToken($token)
            ->get('https://test.api.amadeus.com/v2/shopping/flight-offers', $query);

        if ($response->failed()) {
            throw new \Exception("Amadeus API error: " . $response->body());
        }

        $allRaw = $response->json();
        $offers = $allRaw['data'] ?? [];

        $flights = [];

        foreach ($offers as $index => $offer) {
            $uuid = (string) Str::uuid();
            Cache::put("flight_offer_{$uuid}", $offer, now()->addMinutes(15));

            $departureItinerary = $offer['itineraries'][0];
            $returnItinerary = $offer['itineraries'][1] ?? null;
            $depSegments = $departureItinerary['segments'];
            $firstDep = $depSegments[0];
            $lastDep = end($depSegments);

            $flightData = [
                'flight_offer_id' => $uuid,
                'price' => $offer['price']['total'],
                'currency' => $offer['price']['currency'] ?? 'USD',
                'airline' => $firstDep['carrierCode'],
                'flight_number' => $firstDep['number'] ?? '',
                'departure_airport' => $firstDep['departure']['iataCode'],
                'arrival_airport' => $lastDep['arrival']['iataCode'],
                'departure_time' => $this->formatDateTime($firstDep['departure']['at']),
                'arrival_time' => $this->formatDateTime($lastDep['arrival']['at']),
                'duration' => $this->formatDuration($departureItinerary['duration']),
                'travel_class' => $firstDep['cabin'] ?? $travelClass, // Use actual cabin or fallback to requested class
                'bookable_seats' => $offer['numberOfBookableSeats'] > 0,
                'validating_airline' => $offer['validatingAirlineCodes'][0] ?? null,
            ];

            if ($returnItinerary) {
                $retSegments = $returnItinerary['segments'];
                $firstRet = $retSegments[0];
                $lastRet = end($retSegments);

                $flightData['return_departure_airport'] = $firstRet['departure']['iataCode'];
                $flightData['return_arrival_airport'] = $lastRet['arrival']['iataCode'];
                $flightData['return_departure_time'] = $this->formatDateTime($firstRet['departure']['at']);
                $flightData['return_arrival_time'] = $this->formatDateTime($lastRet['arrival']['at']);
                $flightData['return_duration'] = $this->formatDuration($returnItinerary['duration']);
            }

            $flights[] = $flightData;
        }

        return $flights;
    }

    private function formatDuration($iso)
    {
        $interval = new \DateInterval($iso);
        $hours = $interval->h + ($interval->d * 24); // دعم الأيام في بعض الرحلات الطويلة
        $minutes = $interval->i;
        $parts = [];

        if ($hours > 0) {
            $parts[] = "{$hours}h";
        }

        if ($minutes > 0) {
            $parts[] = "{$minutes}m";
        }

        return implode(' ', $parts);
    }
    private function formatDateTime($isoString)
{
    return Carbon::parse($isoString)->format('d M Y - h:i A');
}



public function priceFlightOffer(string $flightOfferUuid)
{
    // 1. جلب بيانات العرض الأصلية من الكاش باستخدام UUID
    $cachedOffer = Cache::get("flight_offer_{$flightOfferUuid}");

    if (!$cachedOffer) {
        throw new \Exception("Flight offer expired or not found. Please search again.");
    }

    // 2. تحضير البيانات المطلوبة لـ API التسعير
    $requestData = [
        'data' => [
            'type' => 'flight-offers-pricing',
            'flightOffers' => [$cachedOffer] // يجب أن يكون مصفوفة
        ]
    ];

    // 3. الحصول على التوكن
    $token = $this->getAccessToken();

    // 4. إرسال الطلب إلى Amadeus
    $response = Http::withToken($token)
        ->withHeaders(['Content-Type' => 'application/json'])
        ->post('https://test.api.amadeus.com/v1/shopping/flight-offers/pricing', $requestData);

    if ($response->failed()) {
        Log::error("Amadeus Pricing API Error: " . $response->body());
        throw new \Exception("Failed to price flight offer: " . $response->json()['errors'][0]['detail'] ?? 'Unknown error');
    }

    $pricingData = $response->json();

    // 5. معالجة البيانات وإرجاع النتيجة المبسطة
    return $this->formatPricingResponse($pricingData, $flightOfferUuid);
}

private function formatPricingResponse(array $pricingData, string $offerUuid): array
{
    $offer = $pricingData['data']['flightOffers'][0] ?? [];
    $priceDetails = $offer['price'] ?? [];
    $cachedOffer = Cache::get("flight_offer_{$offerUuid}", []);
    $itinerary = $cachedOffer['itineraries'][0] ?? [];
    $segments = $itinerary['segments'] ?? [];
    $travelerPricings = $offer['travelerPricings'] ?? [];

    // Passenger counting (adults and children only)
    $passengerCounts = ['adults' => 0, 'children' => 0];
    foreach ($travelerPricings as $traveler) {
        $type = strtoupper($traveler['travelerType'] ?? 'ADULT');
        if ($type === 'ADULT') $passengerCounts['adults']++;
        if ($type === 'CHILD') $passengerCounts['children']++;
    }

    // Flight segment details
    $firstSegment = $segments[0] ?? [];
    $lastSegment = end($segments) ?: [];
    $departure = $firstSegment['departure'] ?? [];
    $arrival = $lastSegment['arrival'] ?? [];

    return [
        'booking' => [
            'passengers' => [
                'total' => array_sum($passengerCounts),
                'details' => array_filter([
                    'adults' => $passengerCounts['adults'] ?: null,
                    'children' => $passengerCounts['children'] ?: null
                ]) ?: (object)[]
            ],
            'flight' => [
                'number' => ($firstSegment['carrierCode'] ?? '') . ' ' . ($firstSegment['number'] ?? ''),
                'class' => $travelerPricings[0]['fareDetailsBySegment'][0]['cabin'] ?? 'ECONOMY',
                'aircraft' => $firstSegment['aircraft']['code'] ?? null,
                'operated_by' => $firstSegment['operating']['carrierCode'] ?? null
            ]
        ],
        'pricing' => [
            'total' => [
                'amount' => number_format($priceDetails['total'] ?? 0, 2),
                'currency' => $priceDetails['currency'] ?? 'USD'
            ],
            'details' => [
                'base_fare' => number_format($priceDetails['base'] ?? 0, 2),
                'taxes' => number_format(($priceDetails['total'] ?? 0) - ($priceDetails['base'] ?? 0), 2)
            ]
        ],
        'services' => [
            'baggage' => [
                'cabin' => '1 × 7kg',
                'checked' => ($offer['travelerPricings'][0]['fareDetailsBySegment'][0]['includedCheckedBags']['quantity'] ?? 0) . ' × 23kg'
            ],
            'meals' => isset($offer['travelerPricings'][0]['fareDetailsBySegment'][0]['amenities']) &&
                      in_array('MEAL', $offer['travelerPricings'][0]['fareDetailsBySegment'][0]['amenities'])
                      ? 'Included' : 'Not included'
        ],
        'schedule' => [
            'departure' => [
                'date' => isset($departure['at']) ? Carbon::parse($departure['at'])->format('M d, Y') : null,
                'time' => isset($departure['at']) ? Carbon::parse($departure['at'])->format('h:i A') : null,
                'airport' => ($departure['iataCode'] ?? '') . (isset($departure['terminal']) ? ' T' . $departure['terminal'] : '')
            ],
            'arrival' => [
                'date' => isset($arrival['at']) ? Carbon::parse($arrival['at'])->format('M d, Y') : null,
                'time' => isset($arrival['at']) ? Carbon::parse($arrival['at'])->format('h:i A') : null,
                'airport' => ($arrival['iataCode'] ?? '') . (isset($arrival['terminal']) ? ' T' . $arrival['terminal'] : '')
            ],
            'duration' => $this->formatDuration1($itinerary['duration'] ?? ''),
            'stops' => max(0, count($segments) - 1)
        ]
    ];
}

private function formatDuration1(string $isoDuration): string
{
    try {
        $interval = new DateInterval($isoDuration);
        $parts = [];
        if ($interval->d) $parts[] = $interval->d . 'd';
        if ($interval->h) $parts[] = $interval->h . 'h';
        if ($interval->i) $parts[] = $interval->i . 'm';
        return implode(' ', $parts) ?: 'Non-stop';
    } catch (Exception $e) {
        return 'Duration not available';
    }
}
public function createBooking(array $passengers, string $flightOfferId)
{
    $token = $this->getAccessToken();
    $flightOffer = Cache::get("flight_offer_{$flightOfferId}");

    if (!$flightOffer) {
        throw new \Exception("Flight offer not found or expired.");
    }

    // ✅ تحقق من عدد الركاب
    $expectedCount = count($flightOffer['travelerPricings']);
    if (count($passengers) !== $expectedCount) {
        throw new \Exception("Number of passengers does not match the priced flight offer.");
    }

    $travelerArray = [];
    foreach ($passengers as $index => $p) {
        $travelerArray[] = [
            'id' => (string)($index + 1),
            'dateOfBirth' => $p['dateOfBirth'],
            'name' => [
                'firstName' => $p['firstName'],
                'lastName' => $p['lastName'],
            ],
            'gender' => $p['gender'],
            'contact' => [
                'emailAddress' => $p['email'],
                'phones' => [
                    [
                        'deviceType' => 'MOBILE',
                        'countryCallingCode' => '963',
                        'number' => $p['phone'],
                    ]
                ]
            ],
            'documents' => [
                [
                    'documentType' => 'PASSPORT',
                    'number' => '00000000',
                    'expiryDate' => '2030-01-01',
                    'issuanceCountry' => 'SY',
                    'nationality' => 'SY',
                    'holder' => true
                ]
            ]
        ];
    }

    $data = [
        'data' => [
            'type' => 'flight-order',
            'flightOffers' => [$flightOffer],
            'travelers' => $travelerArray
        ]
    ];

    $response = Http::withToken($token)
        ->withHeaders(['Content-Type' => 'application/json'])
        ->post('https://test.api.amadeus.com/v1/booking/flight-orders', $data);

    if ($response->failed()) {
        throw new \Exception("Amadeus Booking Failed: " . $response->body());
    }

    return $response->json()['data'] ?? [];
}



public function cancelBooking(string $amadeusBookingId)
{
    $token = $this->getAccessToken();

    $response = Http::withToken($token)
        ->delete("https://test.api.amadeus.com/v1/booking/flight-orders/{$amadeusBookingId}");

    if ($response->successful()) {
        return true;
    }

    throw new \Exception("Failed to cancel booking with Amadeus: " . $response->body());
}




// public function cancelBooking(string $bookingId)
//     {
//         $token = $this->getAccessToken();

//         $response = Http::withToken($token)
//             ->delete("https://test.api.amadeus.com/v1/booking/flight-orders/{$bookingId}");

//         if ($response->failed()) {
//             Log::error('Amadeus Cancel Booking Failed', [
//                 'error' => $response->body(),
//                 'bookingId' => $bookingId
//             ]);
//             throw new \Exception("Failed to cancel booking: " . ($response->json()['errors'][0]['detail'] ?? 'Unknown error'));
//         }

//         return true;
//     }


//     public function getBookingStatus(string $bookingId)
//     {
//         $token = $this->getAccessToken();

//         $response = Http::withToken($token)
//             ->get("https://test.api.amadeus.com/v1/booking/flight-orders/{$bookingId}");

//         if ($response->failed()) {
//             Log::error('Amadeus Booking Status Failed', [
//                 'error' => $response->body(),
//                 'bookingId' => $bookingId
//             ]);
//             throw new \Exception("Failed to get booking status: " . ($response->json()['errors'][0]['detail'] ?? 'Unknown error'));
//         }

//         return $response->json();
//     }




}
