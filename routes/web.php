<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\SuperAdminController;
use App\Http\Controllers\Admin\AdminUsersController;
use App\Http\Middleware\Admin as AdminMiddleware;
use App\Http\Middleware\AdminUsersMiddleware;
use App\Http\Middleware\AdminTripsMiddleware;
use App\Http\Controllers\Admin\AdminTripController;

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
 Route::get('/create', [AdminTripController::class, 'create'])->name('admin_trips.create');
    Route::post('/', [AdminTripController::class, 'store'])->name('admin_trips.store');
    Route::get('/{trip}/edit', [AdminTripController::class, 'edit'])->name('admin_trips.edit');
    Route::put('/{trip}', [AdminTripController::class, 'update'])->name('update');
    Route::delete('/{trip}', [AdminTripController::class, 'destroy'])->name('destroy');
   
});



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
