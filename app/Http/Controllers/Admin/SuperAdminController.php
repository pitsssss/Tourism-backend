<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SuperAdminController extends Controller
{
   public function index()
{
    $admins = User::whereIn('role', [
        'admin_users', 'admin_trips', 'admin_hotels', 'admin_restaurants', 'admin_places','admin_tour_guides'
    ])->get();

    return view('super_admin.dashboard', compact('admins'));
}

public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6',
        'role' => 'required|in:admin_users,admin_trips,admin_hotels,admin_restaurants,admin_places,admin_tour_guides',
    ]);

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => $request->role,
        'is_verified' => true,
    ]);

      return redirect()->route('super_admin.admins')->with('success', 'تمت إضافة الأدمن بنجاح');
}

public function admins()
{
    $admins = User::whereIn('role', [
        'admin_users', 'admin_trips', 'admin_hotels', 'admin_restaurants', 'admin_places','admin_tour_guides'
    ])->get();

    return view('super_admin.admins', compact('admins'));
}



}
