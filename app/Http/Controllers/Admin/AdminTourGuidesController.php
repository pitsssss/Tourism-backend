<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TourGuide;

class AdminTourGuidesController extends Controller
{
    public function index()
    {
        $guides = TourGuide::all();
        return view('admin_tour_guides.index', compact('guides'));
    }

    public function create()
    {
        return view('admin_tour_guides.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'rating' => 'nullable|numeric|min:0|max:5',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        $imageName = null;
        if ($request->hasFile('image')) {
            $imageName = time().'_'.$request->image->getClientOriginalName();
            $request->image->move(public_path('imgs/guides.img'), $imageName);
        }

        TourGuide::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'rating' => $request->rating ?? 0,
            'image' => $imageName
        ]);

        return redirect()->route('admin_tour_guides.index')->with('success', 'تمت إضافة الدليل بنجاح');
    }

    public function edit(TourGuide $tourGuide)
    {
        return view('admin_tour_guides.edit', compact('tourGuide'));
    }

    public function update(Request $request, TourGuide $tourGuide)
    {
        $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'rating' => 'nullable|numeric|min:0|max:5',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $imageName = time().'_'.$request->image->getClientOriginalName();
            $request->image->move(public_path('imgs/guides.img'), $imageName);
            $tourGuide->image = $imageName;
        }

        $tourGuide->name = $request->name;
        $tourGuide->phone = $request->phone;
        $tourGuide->rating = $request->rating ?? 0;
        $tourGuide->save();

        return redirect()->route('admin_tour_guides.index')->with('success', 'تم تعديل بيانات الدليل بنجاح');
    }

    public function destroy(TourGuide $tourGuide)
    {
        $tourGuide->delete();
        return redirect()->route('admin_tour_guides.index')->with('success', 'تم حذف الدليل بنجاح');
    }
}