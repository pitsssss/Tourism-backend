<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PaymentController;

// مسارات الدفع والتأكيد (يجب أن تكون في web.php لأن Stripe يوجه إليها مباشرة)
Route::middleware(['web'])->group(function () {
    // مسار نجاح الدفع (يجب أن يكون GET)
    Route::get('/payment/success', [PaymentController::class, 'handleSuccess'])
         ->name('payment.success');

    // مسار إلغاء الدفع (يجب أن يكون GET)
    Route::get('/payment/cancel', [PaymentController::class, 'handleCancel'])
         ->name('payment.cancel');
});

// (اختياري) مسارات أخرى للتطبيق...
Route::get('/', function () {
    return view('welcome');
});
