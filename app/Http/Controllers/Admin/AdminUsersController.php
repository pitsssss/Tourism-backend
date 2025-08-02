<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\user_trips;
use Illuminate\Http\Request;



class AdminUsersController extends Controller
{
    public function dashboard()
{
    $users = User::where('role', 'user')->get();
    return view('admin_users.dashboard', compact('users'));
}

public function userTrips($id)
{
    $user = User::findOrFail($id);
    $trips = $user->userTrips()->with(['trip', 'governorate'])->get();

    return view('admin_users.user_trips', compact('user', 'trips'));
}

public function updateTripStatus(Request $request)
{
    $request->validate([
        'trip_id' => 'required|exists:user_trips,id',
        'status' => 'required|in:upcoming,in_progress,finished',
    ]);

    $trip = user_trips::findOrFail($request->trip_id);
    $trip->status = $request->status;
    $trip->save();

    return back()->with('success', 'تم تعديل حالة الرحلة بنجاح');
}

}
