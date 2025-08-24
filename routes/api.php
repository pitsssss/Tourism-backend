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
    Private_TripController,
    UserTripsController,
    DayController,
    FlightController,
    SettingController,
    FlightsBookingController,
    ProfileController,
    SettingsController


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





// ✅ View trips, Hotels, Restaurants, Categories
Route::get('/Alltrips', [TripsController::class, 'getAllTrips']);//show all trip
// Route::get('/trips/{id}', [TripsController::class, 'show']);
Route::get('/trips/{id}', [TripsController::class, 'getTripDetails']);//TripDetails
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{id}/trips', [CategoryController::class, 'getTrips']);
Route::get('/categories/{id}/trips', [TripsController::class, 'getTripsByCategory']);
Route::get('/trips/category/{categoryId}', [TripsController::class, 'showCategoryTripsSorted']);//new
 Route::get('/sortedTrips', [TripsController::class, 'showTripsSorted']);
Route::get('/activity_details/{activityId}', [TripsController::class, 'show_activity_details']);//new
Route::get('/hotels/{id}', [HotelController::class, 'show']);//hotel_details
Route::get('/restaurants/{restaurant}', [RestaurantController::class, 'show_details']);//restaurant details
Route::get('/places/{place}', [PlaceController::class, 'show_details']);//places details
/////Explor
Route::get('/explore/hotels', [ExploreController::class, 'getAllHotels']);
Route::get('/explore/restaurants', [ExploreController::class, 'getAllRestaurants']);
Route::get('/explore/activities', [ExploreController::class, 'getAllActivities']);
Route::get('/explore/places', [ExploreController::class, 'getAllPlaces']);
Route::get('/explore/search', [ExploreController::class, 'search']);


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



Route::post('/private-trips', [Private_TripController::class, 'store']);//make privat trip
Route::get('/user-trips/upcoming', [UserTripsController::class, 'upcomingTrips']);//all trip upcoming
Route::post('/private-trips/{trip}/days', [DayController::class, 'store']);//add day to private trip
Route::get('/private-trip/{trip}/hotels', [Private_TripController::class, 'getHotels']);//get hotel by governorates
Route::get('/private-trips/{trip}/details', [Private_TripController::class, 'getTripGovernorateDetails']);//باقي الاماكن المرتبطة بالمحافظة
Route::get('/transportations', [Private_TripController::class, 'getTransportations']);//show all
Route::post('/private-trip/{privateTrip}/transportation', [Private_TripController::class, 'chooseTransportation']);// اختيار وسيلة نقل لرحلة خاصة
Route::post('/private-trips/{privateTrip}/choose-room', [Private_TripController::class, 'chooseRoom']);
Route::delete('/private-trip/{trip}/days/{dayId}', [DayController::class, 'destroyDay']);//حذف يوم من الرحلة الخاصة
Route::get('/tour-guides', [Private_TripController::class, 'getTourGuides']);//show all
Route::post('/private-trips/{privateTrip}/choose-tour-guide', [Private_TripController::class, 'chooseTourGuide']);//يختار الدليل للرحلة الخاصة
Route::get('/days/{dayId}', [Private_TripController::class, 'showDay']);
Route::post('/days/{dayId}/add-element', [Private_TripController::class, 'addElement']);
Route::get('/private_trip_details/{privateTrip}', [Private_TripController::class, 'show']);//grt private trip details

Route::delete('/days/{dayId}/remove-element', [Private_TripController::class, 'removeElement']);


Route::post('/private_trips/book', [Private_TripController::class, 'bookTrip']);
Route::post('/private_trips/confirm-booking', [Private_TripController::class, 'confirmBooking']);
Route::get('/private_trips/my-bookings', action: [Private_TripController::class, 'myBookings']);


Route::get('/hotels_Room/{id}', [HotelController::class, 'show_Room']);





Route::post('/trips/book', [TripsController::class, 'bookTrip']);
Route::post('/trips/confirm-booking', [TripsController::class, 'confirmBooking']);
Route::get('/my-bookings', [TripsController::class, 'myBookings']);

Route::post('/flights/submit-passenger', [FlightController::class, 'submitPassenger']);
Route::post('/flights/pay-and-book', [FlightController::class, 'payAndBook']);
Route::post('/flights/confirm-booking', [FlightController::class, 'confirmBooking']);

Route::get('/user/bookings', [FlightsBookingController::class, 'getUserBookings']);
Route::delete('/flights/cancel-booking/{bookingId}', [FlightController::class, 'cancelBooking']);


Route::post('/favorites/add', [FavoriteController::class, 'add']);//add_to_favourite
Route::post('/favorites/remove', [FavoriteController::class, 'remove']);//delete_from_favorite
Route::get('/favorites', [FavoriteController::class, 'index']);




Route::get('/profile', [ProfileController::class, 'index']);
Route::put('/profile/update', [ProfileController::class, 'update']);

Route::get('settings/{key}', [SettingsController::class, 'show']);




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
