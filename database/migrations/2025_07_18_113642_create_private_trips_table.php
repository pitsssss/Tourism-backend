<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('private_trips', function (Blueprint $table) {
           $table->id();
           $table->foreignId('user_id')->constrained()->onDelete('cascade');
           $table->foreignId('governorate_id')->constrained()->onDelete('cascade');
           $table->date('trip_date_start');
           $table->foreignId('transportation_id')->nullable()->constrained('transportations') ->nullOnDelete();
           $table->foreignId('tour_guide_id')->nullable()->constrained('tour_guides')->nullOnDelete();
           $table->foreignId('hotel_room_id')->nullable()->constrained('hotel__rooms')->nullOnDelete();
           $table->foreignId('hotel_id')->nullable()->constrained('hotels')->nullOnDelete();
           $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('private_trips');
    }
};
