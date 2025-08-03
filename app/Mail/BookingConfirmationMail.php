<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $flightDetails;
    public $travelers;

    /**
     * Create a new message instance.
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
        $this->flightDetails = $this->formatFlightDetails($booking->flight_details);
        $this->travelers = $this->formatTravelers($booking->travelers);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'تأكيد حجز رحلتك - ' . $this->booking->reference_number,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.booking_confirmation',
            with: [
                'booking' => $this->booking,
                'flightDetails' => $this->flightDetails,
                'travelers' => $this->travelers,
                'contact' => $this->booking->contact_info
            ],
        );
    }

    /**
     * Format flight details for email
     */
    private function formatFlightDetails(array $flightDetails): array
    {
        $segments = $flightDetails['itineraries'][0]['segments'];
        $firstSegment = $segments[0];
        $lastSegment = end($segments);

        return [
            'airline' => $firstSegment['carrierCode'],
            'flightNumber' => $firstSegment['number'],
            'departure' => [
                'airport' => $firstSegment['departure']['iataCode'],
                'terminal' => $firstSegment['departure']['terminal'] ?? null,
                'date' => \Carbon\Carbon::parse($firstSegment['departure']['at'])->format('d M Y'),
                'time' => \Carbon\Carbon::parse($firstSegment['departure']['at'])->format('h:i A')
            ],
            'arrival' => [
                'airport' => $lastSegment['arrival']['iataCode'],
                'terminal' => $lastSegment['arrival']['terminal'] ?? null,
                'date' => \Carbon\Carbon::parse($lastSegment['arrival']['at'])->format('d M Y'),
                'time' => \Carbon\Carbon::parse($lastSegment['arrival']['at'])->format('h:i A')
            ],
            'duration' => $flightDetails['itineraries'][0]['duration'],
            'class' => $flightDetails['travelerPricings'][0]['fareDetailsBySegment'][0]['cabin']
        ];
    }

    /**
     * Format travelers list for email
     */
    private function formatTravelers(array $travelers): array
    {
        return array_map(function ($traveler) {
            return [
                'name' => $traveler['firstName'] . ' ' . $traveler['lastName'],
                'passport' => $traveler['passportNumber'],
                'nationality' => $traveler['nationality']
            ];
        }, $travelers);
    }
}
