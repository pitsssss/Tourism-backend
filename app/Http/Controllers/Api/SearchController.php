<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Trips;
use Illuminate\Http\Request;
use App\Models\Destination;
use App\Models\Hotel;
use App\Models\Restaurant;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        return response()->json([
            'trips'        => Trips::where('name', 'like', "%$keyword%")->get()
            // 'hotels'       => Hotel::where('name', 'like', "%$keyword%")->get(),
            // 'restaurants'  => Restaurant::where('name', 'like', "%$keyword%")->get(),
        ]);
    }
}
