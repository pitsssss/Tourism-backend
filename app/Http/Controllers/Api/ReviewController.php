<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'reviewable_id' => 'required',
            'reviewable_type' => 'required',
            'rating' => 'required|numeric|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $review = Review::create($request->all());

        return response()->json($review);
    }

    public function getFor($type, $id)
    {
        $model = "App\\Models\\" . ucfirst($type);

        $reviews = Review::where('reviewable_type', $model)
            ->where('reviewable_id', $id)
            ->with('user')
            ->get();

        return response()->json($reviews);
    }
}
