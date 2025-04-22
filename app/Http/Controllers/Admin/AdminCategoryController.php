<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class AdminCategoryController extends Controller
{
    public function index() {
        return Category::all();
    }

    public function store(Request $request) {
        return Category::create($request->all());
    }

    public function update(Request $request, $id) {
        $category = Category::findOrFail($id);
        $category->update($request->all());
        return $category;
    }

    public function destroy($id) {
        Category::destroy($id);
        return response()->json(['message' => 'Category deleted']);
    }
}
