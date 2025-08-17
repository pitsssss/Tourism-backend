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
        Schema::create('hotel__rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained('hotels')->onDelete('cascade');
            $table->enum('room_type', ['single', 'double', 'suite']); 
            $table->unsignedTinyInteger('capacity');  
            $table->decimal('price', 10, 2);          
            $table->unsignedSmallInteger('available_rooms')->default(0); 
            $table->unsignedSmallInteger('total_rooms')->default(0);     
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel__rooms');
    }
};
