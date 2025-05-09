<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Destination;

class DestinationController extends Controller
{

    public function index()
    {
        $destinations = Destination::with('category')->get();
        return response()->json($destinations);
    }

    public function show($id)
    {
        $destination = Destination::with('category')->findOrFail($id);
        return response()->json($destination);
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        $results = Destination::where('name', 'like', "%$keyword%")
            ->orWhere('location', 'like', "%$keyword%")
            ->get();

        return response()->json($results);
    }
}
