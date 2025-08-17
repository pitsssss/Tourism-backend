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
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            //$table->string('cuisine_type')->nullable();
            $table->string('location');
            $table->string('phone_number')->nullable();
            $table->float('rating')->nullable();
            $table->string('image')->nullable();
           // $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
           $table->foreignId('governorate_id')->constrained()->onDelete('cascade');
   
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurants');
    }
};
