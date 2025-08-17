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
        Schema::create('tour_guides', function (Blueprint $table) {
            $table->id();
    $table->string('name');
    $table->string('phone');
    $table->float('rating')->default(0); 
    $table->string('image')->nullable();
   //$table->foreignId('trip_id')->constrained('trips')->onDelete('cascade'); // كل دليل مرتبط برحلة جاهزة
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tour_guides');
    }
};
