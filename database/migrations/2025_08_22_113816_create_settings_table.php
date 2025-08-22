<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings_pages', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // privacy_policy, terms_conditions, support_faq
            $table->longText('content_en');  // النص الإنجليزي
            $table->longText('content_ar');  // النص العربي
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings_pages');
    }
};
