<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            // Amadeus Booking References
            $table->string('amadeus_booking_id')->unique();
            $table->string('reference_number')->unique();

            // Flight Details (Stored as JSON)
            $table->json('flight_details');

            // Passenger Information
            $table->json('travelers'); // Array of travelers
            $table->json('contact_info'); // Primary contact info

            // Payment Information
            $table->string('payment_status')->default('pending'); // pending/paid/cancelled/refunded
            $table->decimal('payment_amount', 10, 2);
            $table->string('payment_currency')->default('USD');
            $table->string('stripe_session_id')->nullable();

            // Timestamps
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();

            // Indexes for faster queries
            $table->index('payment_status');
            $table->index('reference_number');
        });
    }

    public function down()
    {
        Schema::dropIfExists('bookings');
    }
};
