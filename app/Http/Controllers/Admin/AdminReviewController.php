<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;

class AdminReviewController extends Controller
{
    public function index() {
        return Review::with('user')->get();
    }

    public function destroy($id) {
        Review::destroy($id);
        return response()->json(['message' => 'Review deleted']);
    }
}
