<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trips;
use Illuminate\Http\Request;
class AdminTripsController extends Controller
{

    public function index()
    {
        return Trips::with('category')->get();
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|string',
        ]);

        $destination = Trips::create($validated);

        return response()->json($destination, 201);
    }


    public function show($id)
    {
        $destination = Trips::with('category')->findOrFail($id);
        return response()->json($destination);
    }


    public function update(Request $request, $id)
    {
        $destination = Trips::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string',
            'category_id' => 'sometimes|required|exists:categories,id',
            'image' => 'nullable|string',
        ]);

        $destination->update($validated);

        return response()->json($destination);
    }


    public function destroy($id)
    {
        Trips::destroy($id);
        return response()->json(['message' => 'Trips deleted successfully']);
    }
}
