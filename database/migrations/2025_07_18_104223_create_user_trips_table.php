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
        Schema::create('user_trips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('trip_id')->nullable()->constrained('trips')->onDelete('set null'); 
        $table->unsignedBigInteger('private_trip_id')->nullable();
        $table->enum('type', ['ready', 'custom']);
        $table->foreignId('governorate_id')->nullable()->constrained('governorates')->onDelete('set null');
        $table->enum('status', ['upcoming', 'in_progress', 'finished'])->default('upcoming');

        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_trips');
    }
};
