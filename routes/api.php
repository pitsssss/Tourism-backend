<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    AuthController,
    UserController,
    DestinationController,
    HotelController,
    RestaurantController,
    CategoryController,
    BookingController,
    FavoriteController,
    ReviewController,
    SearchController,
    NotificationController,
    ContactMessageController
};

use App\Http\Controllers\Admin\{
    AdminUserController,
    AdminDestinationController,
    AdminHotelController,
    AdminRestaurantController,
    AdminCategoryController,
    AdminBookingController,
    AdminFavoriteController,
    AdminReviewController,
    AdminContactMessageController,
    AdminNotificationController
};

/// 🌐 Public API routes (No auth needed)
Route::prefix('v1')->group(function () {

    // ✅ Auth
    Route::post('/register', [AuthController::class, 'register'])->middleware(['throttle:register']);
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');

    // ✅ View Destinations, Hotels, Restaurants, Categories
    Route::get('/destinations', [DestinationController::class, 'index']);
    Route::get('/destinations/{id}', [DestinationController::class, 'show']);

    Route::get('/hotels', [HotelController::class, 'index']);
    Route::get('/hotels/{id}', [HotelController::class, 'show']);

    Route::get('/restaurants', [RestaurantController::class, 'index']);
    Route::get('/restaurants/{id}', [RestaurantController::class, 'show']);

    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);

    Route::get('/users/{id}', [UserController::class, 'show']);

    Route::get('/search', [SearchController::class, 'search']);
});

/// 🔐 Protected API routes for authenticated users
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {

    // ✅ Bookings
    Route::get('/bookings', [BookingController::class, 'index']);
    Route::post('/bookings', [BookingController::class, 'store']);

    // ✅ Favorites
    Route::get('/favorites/{userId}', [FavoriteController::class, 'index']);
    Route::post('/favorites', [FavoriteController::class, 'store']);
    Route::delete('/favorites/{id}', [FavoriteController::class, 'destroy']);

    // ✅ Reviews
    Route::post('/reviews', [ReviewController::class, 'store']);
    Route::get('/reviews/{type}/{id}', [ReviewController::class, 'getFor']);

    // ✅ Notifications
    Route::get('/notifications/{userId}', [NotificationController::class, 'index']);

    // ✅ Contact Messages
    Route::post('/contact-messages', [ContactMessageController::class, 'store'])->middleware('throttle:contact');

    // ✅ Logout
    Route::post('/logout', [AuthController::class, 'logout']);
});

/// 🔒 Admin routes (Authenticated + Admin middleware)
Route::prefix('admin')->middleware(['auth:sanctum', 'admin'])->group(function () {

    Route::apiResource('/users', AdminUserController::class);
    Route::apiResource('/destinations', AdminDestinationController::class);
    Route::apiResource('/hotels', AdminHotelController::class);
    Route::apiResource('/restaurants', AdminRestaurantController::class);
    Route::apiResource('/categories', AdminCategoryController::class);
    Route::apiResource('/bookings', AdminBookingController::class);
    Route::apiResource('/favorites', AdminFavoriteController::class);
    Route::apiResource('/reviews', AdminReviewController::class);
    Route::apiResource('/contact-messages', AdminContactMessageController::class);
    Route::apiResource('/notifications', AdminNotificationController::class);
});
