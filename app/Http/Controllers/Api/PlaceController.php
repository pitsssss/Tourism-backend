<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Place;
use Illuminate\Http\Request;


class PlaceController extends Controller
{
    public function index()
{
    $places = Place::with('category')->get(); // جلب الأماكن مع التصنيف
    return response()->json($places);
}
}
