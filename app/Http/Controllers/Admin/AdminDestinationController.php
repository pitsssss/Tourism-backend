<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Destination;

class AdminDestinationController extends Controller
{

    public function index()
    {
        return Destination::with('category')->get();
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

        $destination = Destination::create($validated);

        return response()->json($destination, 201);
    }


    public function show($id)
    {
        $destination = Destination::with('category')->findOrFail($id);
        return response()->json($destination);
    }


    public function update(Request $request, $id)
    {
        $destination = Destination::findOrFail($id);

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
        Destination::destroy($id);
        return response()->json(['message' => 'Destination deleted successfully']);
    }
}
