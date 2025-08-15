<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlightsBooking extends Model
{

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    protected $fillable = [
        'user_id',
        'amadeus_booking_id',
        'flight_offer_id',
        'passenger_details',
        'flight_details',
        'payment_intent_id',
        'amount',
        'currency',
    ];

    protected $casts = [
        'flight_details' => 'array',
        'travelers' => 'array',
        'contact_info' => 'array',
        'payment_amount' => 'decimal:2',
        'cancelled_at' => 'datetime'
    ];

    // Helper Methods
    public function isConfirmed(): bool
    {
        return $this->payment_status === 'paid';
    }

    public function isCancellable(): bool
    {
        return $this->isConfirmed() &&
               !$this->cancelled_at &&
               now()->diffInHours($this->created_at) < 24;
    }

    public function primaryTraveler(): array
    {
        return $this->travelers[0] ?? [];
    }
}
