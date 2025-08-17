<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hotel;

class HotelController extends Controller
{
    public function index()
    {
        return Hotel::all();
    }

    public function show($id)
{
    $hotel = Hotel::findOrFail($id);
    return response()->json($hotel);
}

public function show_Room($id)
    {
        $hotel = Hotel::with(['rooms' => function($query) {
            $query->where('available_rooms', '>', 0); // بس الغرف الفاضية
        }])->findOrFail($id);

        return response()->json($hotel);
    }
}
