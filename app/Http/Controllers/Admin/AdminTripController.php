<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Trip;
use App\Models\Hotel;
use App\Models\Category;
use App\Models\governorates;
use App\Models\TripImage;
use App\Models\TourGuide;




class AdminTripController extends Controller
{
      public function dashboard()
    {
        $trips = Trip::with(['governorate', 'hotel', 'category','TourGuide'])->get();
        return view('admin_trips.dashboard', compact('trips'));
        

    }

 public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'transport' => 'required|string|max:255',
            'description' => 'required',
            'start_date' => 'nullable|date',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'trip_images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'hotel_id' => 'required|exists:hotels,id',
            'category_id' => 'required|exists:categories,id',
            'governorate_id' => 'nullable|exists:governorates,id',
            'price' => 'nullable|numeric',
            'count_days' => 'required|integer|min:1',
            'guide_id' => 'nullable|exists:tour_guides,id',
        ]);

     $mainImage = null;
if ($request->hasFile('image')) {
    $image = $request->file('image');
    $imageName = time() . '_' . $image->getClientOriginalName();  
    $image->move(public_path('imgs/trips.img'), $imageName);
    $mainImage = $imageName;}
        
        $trip = Trip::create([
            'name' => $request->name,
            'transport' => $request->transport,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'image' => $mainImage,
            'hotel_id' => $request->hotel_id,
            'category_id' => $request->category_id,
            'governorate_id' => $request->governorate_id,
            'guide_id' => $request->guide_id,
            'price' => $request->price,
            'count_days' => $request->count_days,
            
            
        ]);

        //حفظ الصور الإضافية  
       if ($request->hasFile('trip_images')) {
    foreach ($request->file('trip_images') as $imageFile) {
        $imageName = time() . '_' . $imageFile->getClientOriginalName();
        $imageFile->move(public_path('imgs/trips.img'), $imageName);

        TripImage::create([
            'trip_id' => $trip->id,
            'image' => $imageName,  
        ]);
    }


        }

        return redirect()->route('dashboard')->with('success', 'تمت إضافة الرحلة بنجاح!');
    }

public function create()
    {
        $hotels = Hotel::all();
        $categories = Category::all();
        $governorates = governorates::all();
        $tourGuides = TourGuide::all();

        return view('admin_trips.create', compact('hotels', 'categories', 'governorates','tourGuides'));
    }

}
