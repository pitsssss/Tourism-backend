<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Services\AmadeusService;
use Illuminate\Support\Facades\Log;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingConfirmationMail;
use Carbon\Carbon;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    protected $amadeus;

    public function __construct(AmadeusService $amadeus)
    {
        $this->amadeus = $amadeus;
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    /**
     * Create Stripe checkout session
     */
    public function createCheckoutSession(Request $request)
{
    $request->validate([
        'offer_id' => 'required|string',
        'travelers' => 'required|array',
        'contact' => 'required|array',
        'total_amount' => 'required|numeric|min:0.01'
    ]);

    try {
        $offer = Cache::get("flight_offer_{$request->offer_id}");
        if (!$offer) {
            throw new \Exception('Flight offer expired or not found');
        }

        // 1. التعديل الأساسي: استخدام رابط ngrok مباشرة
        $successUrl = 'https://c6f6ef001b36.ngrok-free.app/payment/success?session_id={CHECKOUT_SESSION_ID}';
        $cancelUrl = 'https://c6f6ef001b36.ngrok-free.app/payment/cancel';

        // 2. إضافة تسجيل للرابط للتأكد من صحته
        Log::debug('Generated Success URL', ['url' => $successUrl]);

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => $offer['price']['currency'] ?? 'USD',
                    'product_data' => [
                        'name' => $this->generateFlightDescription($offer),
                    ],
                    'unit_amount' => (int)round($request->total_amount * 100),
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $successUrl, // تم التعديل هنا
            'cancel_url' => $cancelUrl,   // تم التعديل هنا
            'customer_email' => $request->contact['email'],
            'metadata' => [
                'offer_id' => $request->offer_id,
                'user_id' => Auth::id(),
                'contact_email' => $request->contact['email']
            ],
            'expires_at' => now()->addHours(2)->timestamp,
        ]);

        // 3. زيادة مدة تخزين الكاش
        Cache::put("booking_{$session->id}", [
            'offer_id' => $request->offer_id,
            'travelers' => $request->travelers,
            'contact' => $request->contact,
            'user_id' => Auth::id(),
            'total_amount' => $request->total_amount,
            'currency' => $offer['price']['currency'] ?? 'USD'
        ], now()->addHours(24)); // زدنا المدة إلى 24 ساعة

        return response()->json([
            'success' => true,
            'session_id' => $session->id,
            'payment_url' => $session->url,
            'expires_at' => now()->addHours(2)->toDateTimeString()
        ]);

    } catch (\Exception $e) {
        Log::error('Stripe Session Error', [
            'error' => $e->getMessage(),
            'user_id' => Auth::id()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Failed to create payment session',
            'error_details' => env('APP_DEBUG') ? $e->getMessage() : null
        ], 500);
    }
}
    /**
     * Handle successful payment
     */
    public function handleSuccess(Request $request)
{
    Log::info('==== PAYMENT SUCCESS CALLBACK STARTED ====', [
        'session_id' => $request->session_id,
        'ip' => $request->ip(),
        'user_agent' => $request->userAgent()
    ]);

    try {
        // 1. التحقق الأساسي من session_id
        if (empty($request->session_id)) {
            throw new \Exception('Session ID is required');
        }

        // 2. استرجاع جلسة Stripe مع تعزيز التسجيل
        $session = Session::retrieve($request->session_id);
        Log::debug('STRIPE SESSION DATA', [
            'id' => $session->id,
            'payment_status' => $session->payment_status,
            'amount_total' => $session->amount_total,
            'metadata' => $session->metadata
        ]);

        // 3. التحقق من حالة الدفع
        if ($session->payment_status !== 'paid') {
            throw new \Exception('Payment not marked as paid. Status: ' . $session->payment_status);
        }

        // 4. استرجاع بيانات الحجز مع تسجيل محتوى الكاش
        $bookingData = Cache::get("booking_{$session->id}");
        Log::debug('CACHE CONTENTS', [
            'cache_key' => "booking_{$session->id}",
            'exists' => !is_null($bookingData),
            'user_id_match' => $bookingData['user_id'] ?? null == $session->metadata->user_id ?? null
        ]);

        if (!$bookingData) {
            throw new \Exception('Booking data expired or not found in cache');
        }

        // 5. التحقق من تطابق المستخدم
        if (!isset($session->metadata->user_id) || $session->metadata->user_id != $bookingData['user_id']) {
            throw new \Exception(sprintf(
                'User ID mismatch. Session: %s, Cache: %s',
                $session->metadata->user_id ?? 'null',
                $bookingData['user_id'] ?? 'null'
            ));
        }

        // 6. إنشاء الحجز في Amadeus مع تحسين التسجيل
        Log::info('CREATING AMADEUS BOOKING', [
            'offer_id' => $bookingData['offer_id'],
            'travelers_count' => count($bookingData['travelers'])
        ]);

        $amadeusResponse = $this->amadeus->createBooking($bookingData, $bookingData['offer_id']);

        Log::info('AMADEUS BOOKING CREATED', [
            'amadeus_id' => $amadeusResponse['data']['id'] ?? null,
            'reference' => $amadeusResponse['data']['associatedRecords'][0]['reference'] ?? null
        ]);

        // 7. حفظ الحجز في قاعدة البيانات
        $booking = Booking::create([
            'user_id' => $bookingData['user_id'],
            'amadeus_booking_id' => $amadeusResponse['data']['id'],
            'reference_number' => $amadeusResponse['data']['associatedRecords'][0]['reference'],
            'flight_details' => $amadeusResponse['data']['flightOffers'][0],
            'travelers' => $bookingData['travelers'],
            'contact_info' => $bookingData['contact'],
            'payment_status' => 'paid',
            'payment_amount' => $session->amount_total / 100,
            'payment_currency' => $session->currency,
            'stripe_session_id' => $session->id,
            'payment_details' => [
                'stripe_payment_intent' => $session->payment_intent,
                'payment_method' => $session->payment_method_types[0] ?? 'unknown'
            ]
        ]);

        Log::info('BOOKING SAVED TO DATABASE', ['booking_id' => $booking->id]);

        // 8. إرسال إيميل التأكيد
        try {
            $this->sendBookingConfirmation($booking);
            Log::info('CONFIRMATION EMAIL SENT', ['email' => $booking->contact_info['email']]);
        } catch (\Exception $emailEx) {
            Log::error('EMAIL SENDING FAILED', ['error' => $emailEx->getMessage()]);
        }

        // 9. تنظيف البيانات المؤقتة
        Cache::forget("booking_{$session->id}");
        Log::debug('CACHE CLEARED', ['cache_key' => "booking_{$session->id}"]);

        // 10. التوجيه لصفحة النجاح
        $deepLink = "yourapp://booking-success?ref={$booking->reference_number}";

        Log::info('REDIRECTING TO SUCCESS PAGE', [
            'deep_link' => $deepLink,
            'view' => 'payment.success'
        ]);

        return view('payment.success', [
            'booking' => $booking,
            'redirect_url' => $deepLink,
            'countdown_seconds' => 5
        ]);

    } catch (\Exception $e) {
        Log::error('PAYMENT PROCESSING FAILED', [
            'error' => $e->getMessage(),
            'session_id' => $request->session_id,
            'trace' => $e->getTraceAsString()
        ]);

        // إلغاء الحجز في Amadeus إذا تم إنشاؤه جزئياً
        if (isset($amadeusResponse)) {
            try {
                $this->amadeus->cancelBooking($amadeusResponse['data']['id']);
                Log::info('AMADEUS BOOKING CANCELLED DUE TO ERROR');
            } catch (\Exception $cancelEx) {
                Log::error('AMADEUS CANCELLATION FAILED', ['error' => $cancelEx->getMessage()]);
            }
        }

        return view('payment.error', [
            'message' => 'We encountered an issue processing your booking. Please contact support.',
            'redirect_url' => 'yourapp://booking-failed',
            'support_email' => 'support@syriatourism.com'
        ]);
    }
}
    /**
     * Handle cancelled payment
     */
    public function handleCancel(Request $request)
    {
        Log::info('Payment cancelled', [
            'session_id' => $request->session_id,
            'user_ip' => $request->ip()
        ]);

        return view('payment.cancel', [
            'redirect_url' => 'yourapp://booking-cancelled',
            'retry_url' => 'yourapp://flights'
        ]);
    }

    /**
     * Send booking confirmation email
     */
    private function sendBookingConfirmation(Booking $booking): void
    {
        try {
            $userEmail = $booking->contact_info['email'];
            Mail::to($userEmail)->send(new BookingConfirmationMail($booking));
            Log::info('Booking confirmation sent', ['email' => $userEmail]);
        } catch (\Exception $e) {
            Log::error('Failed to send booking confirmation', [
                'error' => $e->getMessage(),
                'booking_id' => $booking->id
            ]);
        }
    }

    /**
     * Get user's bookings
     */
    public function getUserBookings(Request $request)
    {
        try {
            $bookings = $request->user()->bookings()
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $bookings->map(function ($booking) {
                    return $this->formatBookingResponse($booking);
                })
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to retrieve user bookings', [
                'user_id' => $request->user()->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve bookings'
            ], 500);
        }
    }

    /**
     * Get booking details
     */
    public function getBookingDetails(Request $request, $referenceNumber)
    {
        try {
            $booking = $request->user()->bookings()
                ->where('reference_number', $referenceNumber)
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'data' => $this->formatBookingResponse($booking)
            ]);

        } catch (\Exception $e) {
            Log::error('Booking details not found', [
                'reference' => $referenceNumber,
                'user_id' => $request->user()->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Booking not found'
            ], 404);
        }
    }

    /**
     * Cancel booking
     */
    public function cancelBooking(Request $request, $bookingId)
    {
        try {
            $booking = $request->user()->bookings()
                ->where('id', $bookingId)
                ->where('payment_status', 'paid')
                ->firstOrFail();

            // Cancel in Amadeus
            $success = $this->amadeus->cancelBooking($booking->amadeus_booking_id);

            if ($success) {
                $booking->update([
                    'payment_status' => 'cancelled',
                    'cancelled_at' => now()
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Booking cancelled successfully'
                ]);
            }

            throw new \Exception("Failed to cancel booking with Amadeus");

        } catch (\Exception $e) {
            Log::error('Booking cancellation failed', [
                'booking_id' => $bookingId,
                'user_id' => $request->user()->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get booking status from Amadeus
     */
    public function getBookingStatus(Request $request, $bookingId)
    {
        try {
            $booking = $request->user()->bookings()
                ->where('id', $bookingId)
                ->firstOrFail();

            $status = $this->amadeus->getBookingStatus($booking->amadeus_booking_id);

            return response()->json([
                'success' => true,
                'data' => $status
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to get booking status', [
                'booking_id' => $bookingId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Format booking response
     */
    private function formatBookingResponse(Booking $booking): array
    {
        return [
            'id' => $booking->id,
            'reference_number' => $booking->reference_number,
            'amadeus_booking_id' => $booking->amadeus_booking_id,
            'status' => $booking->payment_status,
            'flight' => $booking->flight_details,
            'travelers' => $booking->travelers,
            'amount' => $booking->payment_amount,
            'currency' => $booking->payment_currency,
            'created_at' => $booking->created_at->toDateTimeString(),
            'cancelled_at' => $booking->cancelled_at?->toDateTimeString(),
            'contact_info' => $booking->contact_info
        ];
    }

    /**
     * Generate flight description for Stripe
     */
    private function generateFlightDescription(array $offer): string
    {
        $segments = $offer['itineraries'][0]['segments'];
        $firstSegment = $segments[0];
        $lastSegment = end($segments);

        return sprintf(
            'Flight from %s to %s on %s',
            $firstSegment['departure']['iataCode'],
            $lastSegment['arrival']['iataCode'],
            Carbon::parse($firstSegment['departure']['at'])->format('M d, Y')
        );
    }
}
