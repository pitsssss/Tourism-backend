<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;
use App\Models\Hotel;        
use App\Models\Trip;    
use App\Models\Activity; 
use App\Models\Place;
use App\Models\Restaurant;
class FavoriteController extends Controller
{
    public function add(Request $request)
{
    $user = Auth::user();
// تحويل النوع النصي إلى Class كامل
    $map = [
        'Hotel' => Hotel::class,
        'Trip' => Trip::class,
        'Activity' => Activity::class,
        'Place' => Place::class,
        'Restaurant' => Restaurant::class,
    ];

    $type = $map[$request->favorable_type] ?? null;

    if (!$type) {
        return response()->json(['message' => 'Invalid type'], 400);
    }

    $exists = Favorite::where('user_id', $user->id)
        ->where('favorable_id', $request->favorable_id)
        ->where('favorable_type', $type)
        ->exists();

    if ($exists) {
        return response()->json(['message' => 'Already in favorites'], 400);
    }

    $favorite = Favorite::create([
        'user_id' => $user->id,
        'favorable_id' => $request->favorable_id,
        'favorable_type' => $type,
    ]);

    return response()->json(['message' => 'Added to favorites', 'favorite' => $favorite]);
}


    
    public function remove(Request $request)
{
    $user = Auth::user();

    $map = [
        'Hotel' => Hotel::class,
        'Trip' => Trip::class,
        'Activity' => Activity::class,
        'Place' => Place::class,
        'Restaurant' => Restaurant::class,
    ];

    $type = $map[$request->favorable_type] ?? null;

    if (!$type) {
        return response()->json(['message' => 'Invalid type'], 400);
    }

    $favorite = Favorite::where('user_id', $user->id)
        ->where('favorable_id', $request->favorable_id)
        ->where('favorable_type', $type)
        ->first();

    if (!$favorite) {
        return response()->json(['message' => 'Not found in favorites'], 404);
    }

    $favorite->delete();

    return response()->json(['message' => 'Removed from favorites']);
}

    
   public function index()
{
    $user = Auth::user();
    $favorites = $user->favorites()->with('favorable')->get();
    return response()->json($favorites);
}


}
