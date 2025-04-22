<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Favorite;

class FavoriteController extends Controller
{
    public function index($userId)
    {
        return Favorite::where('user_id', $userId)->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'destination_id' => 'required|exists:destinations,id',
        ]);

        $favorite = Favorite::create($request->all());
        return response()->json($favorite);
    }

    public function destroy($id)
    {
        Favorite::destroy($id);
        return response()->json(['message' => 'Deleted']);
    }
}
