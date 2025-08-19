<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Place;
use Illuminate\Http\Request;


class PlaceController extends Controller
{
    public function index()
{
    $places = Place::with('category')->get(); 
    return response()->json($places);
}

public function show_details($id)
{
    $place = Place::with('images:id,place_id,image_path', 'governorate:id,name')
        ->select('id', 'name', 'location', 'description', 'image', 'governorate_id')
        ->findOrFail($id);

    return response()->json($place);
}

}
