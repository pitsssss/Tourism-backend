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
        $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
        $table->string('flight_offer_id')->nullable();
        $table->string('amadeus_booking_id')->nullable();
        $table->json('passenger_details');
        $table->json('flight_details');
        $table->string('payment_intent_id');
        $table->decimal('amount', 10, 2);
        $table->string('currency', 3)->default('USD');
        $table->string('status')->default('done');
        $table->timestamps();
    });
}


    public function down()
    {
        Schema::dropIfExists('bookings');
    }
};
