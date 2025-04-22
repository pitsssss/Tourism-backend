<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Destination;

class DestinationController extends Controller
{
    // ✅ عرض جميع الوجهات السياحية
    public function index()
    {
        $destinations = Destination::with('category')->get();
        return response()->json($destinations);
    }

    // ✅ عرض وجهة سياحية محددة
    public function show($id)
    {
        $destination = Destination::with('category')->findOrFail($id);
        return response()->json($destination);
    }

    // ✅ البحث عن وجهة حسب الاسم أو الموقع
    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        $results = Destination::where('name', 'like', "%$keyword%")
            ->orWhere('location', 'like', "%$keyword%")
            ->get();

        return response()->json($results);
    }
}
