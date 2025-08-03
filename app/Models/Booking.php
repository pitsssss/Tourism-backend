<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    protected $fillable = [
        'amadeus_booking_id',
        'reference_number',
        'flight_details',
        'travelers',
        'contact_info',
        'payment_status',
        'payment_amount',
        'payment_currency',
        'stripe_session_id',
        'cancelled_at'
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
