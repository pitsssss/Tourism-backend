<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class AdminRestaurantController extends Controller
{
    public function index()
    {
        $hotels = Restaurant::all();
        return view('admin.Restaurant.index', compact('Restaurant'));
    }

    public function create()
    {
        return view('admin.Restaurant.create');
    }

    public function store(Request $request)
    {
        Restaurant::create($request->all());
        return redirect()->route('admin.Restaurant.index');
    }

    public function edit(Restaurant $hotel)
    {
        return view('admin.Restaurant.edit', compact('Restaurant'));
    }

    public function update(Request $request, Restaurant $hotel)
    {
        $hotel->update($request->all());
        return redirect()->route('admin.Restaurant.index');
    }

    public function destroy(Restaurant $hotel)
    {
        $hotel->delete();
        return redirect()->back();
    }
}

