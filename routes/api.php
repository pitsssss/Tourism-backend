<?php
use App\Http\Controllers\Api\AuthController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Api\ForgotPasswordController;
use App\Http\Controllers\Api\PasswordResetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\{

    TripsController,
    UserController,
    HotelController,
    RestaurantController,
    CategoryController,
    BookingController,
    FavoriteController,
    ReviewController,
    SearchController,
    NotificationController,
    ContactMessageController,
    PlaceController,
    ExploreController,
    GovernoratesController,
    PrivateTripController,
    UserTripsController,
    DayController,
    FlightController


};

use App\Http\Controllers\Admin\{
    AdminUserController,
    AdminTripsController,
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

    // ✅ Auth //tested
    Route::post('/register', [AuthController::class, 'register']);//->middleware(['throttle:register']);
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');
    Route::post('/verify-code', [AuthController::class, 'verifyCode']);
    Route::post('/resend-verification', [AuthController::class, 'resendVerificationCode']);

    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetCode'])
    ->middleware('throttle:2,1'); // 2 attempts per minute
    Route::post('/verify-ResetCode', [PasswordResetController::class, 'verifyResetCode']);
    Route::post('/reset-password', [PasswordResetController::class, 'resetPassword']);





//     // ✅ View trips, Hotels, Restaurants, Categories
    Route::get('/Alltrips', [TripsController::class, 'getAllTrips']);
    Route::get('/trips/{id}', [TripsController::class, 'show']);
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{id}/trips', [CategoryController::class, 'getTrips']);



Route::get('/categories/{id}/trips', [TripsController::class, 'getTripsByCategory']);
Route::get('/trips/{id}', [TripsController::class, 'getTripDetails']);
Route::get('/trips/category/{categoryId}', [TripsController::class, 'showCategoryTripsSorted']);//new
 Route::get('/sortedTrips', [TripsController::class, 'showTripsSorted']);
Route::get('/activity_details/{activityId}', [TripsController::class, 'show_activity_details']);//new
/////Explor
Route::get('/explore/hotels', [ExploreController::class, 'getAllHotels']);
Route::get('/explore/restaurants', [ExploreController::class, 'getAllRestaurants']);
Route::get('/explore/activities', [ExploreController::class, 'getAllActivities']);
Route::get('/explore/places', [ExploreController::class, 'getAllPlaces']);



Route::get('/governorates', [GovernoratesController::class, 'index']);

Route::get('/flights', [FlightController::class, 'getFlights']);
Route::post('/flights/price', [FlightController::class, 'price']);



//     Route::get('/places', [PlaceController::class, 'index']);


//     Route::get('/hotels', [HotelController::class, 'index']);
//     Route::get('/hotels/{id}', [HotelController::class, 'show']);

//     Route::get('/restaurants', [RestaurantController::class, 'index']);
//     Route::get('/restaurants/{id}', [RestaurantController::class, 'show']);



//     Route::get('/users/{id}', [UserController::class, 'show']);

   //  Route::get('/search', [SearchController::class, 'search']);
});

/// 🔐 Protected API routes for authenticated users
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {


Route::post('/save-fcm-token', [AuthController::class, 'saveFcmToken']);



Route::post('/private-trips', [PrivateTripController::class, 'store']);//make privat trip
Route::post('/book-trip', [UserTripsController::class, 'bookTrip']);
Route::get('/user-trips/upcoming', [UserTripsController::class, 'upcomingTrips']);//all trip upcoming
Route::post('/private-trips/{trip}/days', [DayController::class, 'store']);//add day to private trip
Route::get('/private-trip/{trip}/hotels', [PrivateTripController::class, 'getHotels']);//get hotel by governorates
Route::get('/private-trips/{trip}/details', [PrivateTripController::class, 'getTripGovernorateDetails']);//باقي الاماكن المرتبطة بالمحافظة
Route::get('/transportations', [PrivateTripController::class, 'getTransportations']);//show all
Route::post('/private-trip/{privateTrip}/transportation', [PrivateTripController::class, 'chooseTransportation']);// اختيار وسيلة نقل لرحلة خاصة
Route::delete('/private-trip/{trip}/days/{dayId}', [DayController::class, 'destroyDay']);//حذف يوم من الرحلة الخاصة


Route::post('/flights/submit-passenger', [FlightController::class, 'submitPassenger']);
Route::post('/flights/pay-and-book', [FlightController::class, 'payAndBook']);
Route::get('/user/bookings', [BookingController::class, 'getUserBookings']);
Route::delete('/flights/cancel-booking/{bookingId}', [FlightController::class, 'cancelBooking']);

     // ✅ Bookings
    // Route::get('/bookings', [BookingController::class, 'index']);
    // Route::post('/bookings', [BookingController::class, 'store']);

     // ✅ Favorites
    // Route::get('/favorites/{userId}', [FavoriteController::class, 'index']);
    // Route::post('/favorites', [FavoriteController::class, 'store']);
    // Route::delete('/favorites/{id}', [FavoriteController::class, 'destroy']);

     // ✅ Reviews
    // Route::post('/reviews', [ReviewController::class, 'store']);
    // Route::get('/reviews/{type}/{id}', [ReviewController::class, 'getFor']);

    // ✅ Notifications
    // Route::get('/notifications/{userId}', [NotificationController::class, 'index']);

     // ✅ Contact Messages
    // Route::post('/contact-messages', [ContactMessageController::class, 'store'])->middleware('throttle:contact');

    // ✅ Logout//tested
Route::post('/logout', [AuthController::class, 'logout']);
});

 //🔒 Admin routes (Authenticated + Admin middleware)
Route::prefix('admin')->middleware(['auth:sanctum', 'admin'])->group(function () {

    Route::apiResource('/users', AdminUserController::class);
    //Route::apiResource('/trips', AdminTripsController::class);
    Route::apiResource('/hotels', AdminHotelController::class);
    Route::apiResource('/restaurants', AdminRestaurantController::class);
    Route::apiResource('/categories', AdminCategoryController::class);
    Route::apiResource('/bookings', AdminBookingController::class);
    Route::apiResource('/favorites', AdminFavoriteController::class);
    Route::apiResource('/reviews', AdminReviewController::class);
    Route::apiResource('/contact-messages', AdminContactMessageController::class);
    Route::apiResource('/notifications', AdminNotificationController::class);
});
