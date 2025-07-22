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
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('transport');
            $table->text('description');
            $table->text('start_date')->nullable(); 
            $table->string('image')->nullable();
            $table->foreignId('hotel_id')->onDelete('cascade');
            $table->foreignId('category_id')->onDelete('cascade');
            $table->double('price')->nullable();
            $table->integer('count_days');
            $table->foreignId('governorate_id')->nullable()->constrained('governorates') ->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
