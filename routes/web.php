<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\SuperAdminController;
use App\Http\Controllers\Admin\AdminUsersController;
use App\Http\Controllers\Admin\AdminTripController;
use App\Http\Controllers\Admin\AdminTourGuidesController;
use App\Http\Controllers\Admin\AdminRestaurantController;
use App\Http\Middleware\Admin as AdminMiddleware;
use App\Http\Middleware\AdminUsersMiddleware;
use App\Http\Middleware\AdminTripsMiddleware;
use App\Http\Middleware\AdminTourGuidesMiddleware;
use App\Http\Middleware\AdminRestaurantMiddleware;


use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});


Route::middleware(['auth', AdminMiddleware::class])->group(function () {
     Route::get('/dashboard', [SuperAdminController::class, 'index'])->name('super_admin.dashboard');
    Route::get('/super-admin', [SuperAdminController::class, 'index'])->name('super_admin.dashboard');
    Route::get('/super-admin/create', [SuperAdminController::class, 'create'])->name('super_admin.create');
    Route::get('/super-admin/admins', [SuperAdminController::class, 'admins'])->name('super_admin.admins');
    Route::post('/super-admin/store', [SuperAdminController::class, 'store'])->name('super_admin.store');
    
});


Route::middleware(['auth', AdminUsersMiddleware::class])->group(function () {
    Route::get('/admin-users/dashboard', [AdminUsersController::class, 'dashboard'])->name('admin_users.dashboard');
    Route::get('/admin-users/user/{id}/trips', [AdminUsersController::class, 'userTrips'])->name('admin_users.user_trips');
    Route::post('/admin-users/update-trip-status', [AdminUsersController::class, 'updateTripStatus'])->name('admin_users.update_trip_status');
});




Route::middleware(['auth', AdminTripsMiddleware::class])->group(function () {
    Route::get('/dashboard', [AdminTripController::class, 'dashboard'])->name('dashboard');
//  Route::get('/create', [AdminTripController::class, 'create'])->name('admin_trips.create');
//     Route::post('/', [AdminTripController::class, 'store'])->name('admin_trips.store');
    
    Route::get('create/step1', [AdminTripController::class, 'createStep1'])->name('trips.create.step1');
    Route::post('create/step1', [AdminTripController::class, 'storeStep1'])->name('trips.store.step1');

    Route::get('create/step2', [AdminTripController::class, 'createStep2'])->name('trips.create.step2');
    Route::post('create/step2', [AdminTripController::class, 'storeStep2'])->name('trips.store.step2');

    Route::get('create/step3', [AdminTripController::class, 'createStep3'])->name('trips.create.step3');
    Route::post('create/step3', [AdminTripController::class, 'storeStep3'])->name('trips.store.step3');

    Route::get('create/step4', [AdminTripController::class, 'createStep4'])->name('trips.create.step4');
    Route::post('create/step4', [AdminTripController::class, 'storeStep4'])->name('trips.store.step4');

    Route::post('store-final', [AdminTripController::class, 'storeFinal'])->name('trips.store.final');
});


Route::middleware(['auth', AdminTourGuidesMiddleware::class])->group(function () {
    Route::get('/admin-tour-guides', [AdminTourGuidesController::class, 'index'])->name('admin_tour_guides.index');
    Route::get('/admin-tour-guides/create', [AdminTourGuidesController::class, 'create'])->name('admin_tour_guides.create');
    Route::post('/admin-tour-guides', [AdminTourGuidesController::class, 'store'])->name('admin_tour_guides.store');
    Route::get('/admin-tour-guides/{tourGuide}/edit', [AdminTourGuidesController::class, 'edit'])->name('admin_tour_guides.edit');
    Route::put('/admin-tour-guides/{tourGuide}', [AdminTourGuidesController::class, 'update'])->name('admin_tour_guides.update');
    Route::delete('/admin-tour-guides/{tourGuide}', [AdminTourGuidesController::class, 'destroy'])->name('admin_tour_guides.destroy');
});



Route::middleware(['auth', AdminRestaurantMiddleware::class])->group(function () {
    Route::get('/admin-restaurants', [AdminRestaurantController::class, 'index'])->name('admin_restaurants.index');
    Route::get('/admin-restaurants/create', [AdminRestaurantController::class, 'create'])->name('admin_restaurants.create');
    Route::post('/admin-restaurants', [AdminRestaurantController::class, 'store'])->name('admin_restaurants.store');
    Route::get('/admin-restaurants/{restaurant}/edit', [AdminRestaurantController::class, 'edit'])->name('admin_restaurants.edit');
    Route::put('/admin-restaurants/{restaurant}', [AdminRestaurantController::class, 'update'])->name('admin_restaurants.update');
    Route::delete('/admin-restaurants/{restaurant}', [AdminRestaurantController::class, 'destroy'])->name('admin_restaurants.destroy');
});
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
