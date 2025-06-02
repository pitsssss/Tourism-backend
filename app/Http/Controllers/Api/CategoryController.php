<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {

        return Category::all();
    }

    public function show($id)
    {
        return Category::findOrFail($id);
    }

    public function getTrips($id)
{
    $category = Category::with('trips')->findOrFail($id);
    return response()->json([
        'category' => $category->name,
        'trips' => $category->trips
       
    ]);
}
}
