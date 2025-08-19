<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transportation;
use Illuminate\Http\Request;
use App\Models\Trip;
use App\Models\Hotel;
use App\Models\Category;
use App\Models\governorates;
use App\Models\TripImage;
use App\Models\TourGuide;
use App\Models\Place;
use App\Models\Restaurant;
use App\Models\Activity;




class AdminTripController extends Controller
{
      public function dashboard()
    {
        $trips = Trip::with(['governorate', 'hotel', 'category','TourGuide'])->get();
        return view('admin_trips.dashboard', compact('trips'));
        

    }

//  public function store(Request $request)
//     {
//         $request->validate([
//             'name' => 'required|string|max:255',
//             'transport' => 'required|string|max:255',
//             'description' => 'required',
//             'start_date' => 'nullable|date',
//             'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
//             'trip_images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
//             'hotel_id' => 'required|exists:hotels,id',
//             'category_id' => 'required|exists:categories,id',
//             'governorate_id' => 'nullable|exists:governorates,id',
//             'price' => 'nullable|numeric',
//             'count_days' => 'required|integer|min:1',
//             'guide_id' => 'nullable|exists:tour_guides,id',
//             'transportation' => 'required|string|max:255',
//         ]);

//      $mainImage = null;
// if ($request->hasFile('image')) {
//     $image = $request->file('image');
//     $imageName = time() . '_' . $image->getClientOriginalName();  
//     $image->move(public_path('imgs/trips.img'), $imageName);
//     $mainImage = $imageName;}
        
//         $trip = Trip::create([
//             'name' => $request->name,
//             'transport' => $request->transport,
//             'description' => $request->description,
//             'start_date' => $request->start_date,
//             'image' => $mainImage,
//             'hotel_id' => $request->hotel_id,
//             'category_id' => $request->category_id,
//             'governorate_id' => $request->governorate_id,
//             'guide_id' => $request->guide_id,
//             'price' => $request->price,
//             'count_days' => $request->count_days,
//             'transportation' => $request->transportation,
            
//         ]);

//         //حفظ الصور الإضافية  
//        if ($request->hasFile('trip_images')) {
//     foreach ($request->file('trip_images') as $imageFile) {
//         $imageName = time() . '_' . $imageFile->getClientOriginalName();
//         $imageFile->move(public_path('imgs/trips.img'), $imageName);

//         TripImage::create([
//             'trip_id' => $trip->id,
//             'image' => $imageName,  
//         ]);
//     }


//         }

//         return redirect()->route('dashboard')->with('success', 'تمت إضافة الرحلة بنجاح!');
//     }

// public function create()
//     {
//         $hotels = Hotel::all();
//         $categories = Category::all();
//         $governorates = governorates::all();
//         $tourGuides = TourGuide::all();
//         $transports = Transportation::all();

//         return view('admin_trips.create', compact('hotels', 'categories', 'governorates','tourGuides','transports'));
//     }

public function createStep1()
{
    return view('admin_trips.steps.step1');
}

public function storeStep1(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'price' => 'required|numeric',
        'count_days' => 'required|integer|min:1',
        'start_date' => 'required|date',
        'image' => 'required|image|max:2048',
        'trip_images.*' => 'nullable|image|max:2048', // الصور الإضافية
    ]);

    $data = [
        'name' => $request->name,
        'description' => $request->description,
        'price' => $request->price,
        'count_days' => $request->count_days,
        'start_date' => $request->start_date,
    ];

    // حفظ الصورة الرئيسية
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = time().'_'.$image->getClientOriginalName();
        $image->storeAs('trips', $imageName, 'public');
        $data['image'] = $imageName;
    }

    // حفظ الصور الإضافية (فقط أسماء الملفات في الجلسة)
    if ($request->hasFile('trip_images')) {
        $additionalImages = [];
        foreach ($request->file('trip_images') as $img) {
            $imgName = time().'_'.$img->getClientOriginalName();
            $img->storeAs('trips', $imgName, 'public');
            $additionalImages[] = $imgName;
        }
        $data['trip_images'] = $additionalImages; // array
    }

    session(['trip_step1' => $data]);

    return redirect()->route('trips.create.step2');
}


// STEP 2: اختيار الفندق، الغرفة، التصنيف والمحافظة
public function createStep2()
{
    $hotels = Hotel::with('rooms')->get();
    $categories = Category::all();
    $governorates = Governorates::all();

    return view('admin_trips.steps.step2', compact('hotels', 'categories', 'governorates'));
}

public function storeStep2(Request $request)
{
    $request->validate([
        'hotel_id' => 'required|exists:hotels,id',
        'room_id' => 'required|exists:hotel__rooms,id',
        'category_id' => 'required|exists:categories,id',
        'governorate_id' => 'required|exists:governorates,id',
    ]);

    $data = $request->only(['hotel_id', 'room_id', 'category_id', 'governorate_id']);

    // حفظ بيانات الخطوة الثانية في الجلسة
    session(['trip_step2' => $data]);

    return redirect()->route('trips.create.step3');
}

public function createStep3()
{
    $tourGuides = TourGuide::all();
    $transports = Transportation::all();

    return view('admin_trips.steps.step3', compact('tourGuides', 'transports'));
}

public function storeStep3(Request $request)
{
    $request->validate([
        'guide_id' => 'nullable|exists:tour_guides,id',
        'transportation_id' => 'required|exists:transportations,id',
    ]);

    $data = $request->only(['guide_id', 'transportation_id']);

    session()->put('trip_step3', $data);

    return redirect()->route('trips.create.step4');
}
public function createStep4()
{
    $places = Place::all();
    $restaurants = Restaurant::all();
    $activities = Activity::all();

    return view('admin_trips.steps.step4', compact('places', 'restaurants', 'activities'));
}

public function storeStep4(Request $request)
{
    // نحفظ الأيام مع الأنشطة والمطاعم والأماكن
    $request->validate([
        'days' => 'required|array|min:1',
        'days.*.name' => 'required|string',
        'days.*.places' => 'nullable|array',
        'days.*.restaurants' => 'nullable|array',
        'days.*.activities' => 'nullable|array',
    ]);

    $data = $request->only(['days']);

    session()->put('trip_step4', $data);

    return redirect()->route('trips.store.final');
}

public function storeFinal(Request $request)
{
    try {
        // طباعة كل بيانات الخطوات قبل الحفظ لتتأكدي إنها موجودة
        $step1 = session('trip_step1');
        $step2 = session('trip_step2');
        $step3 = session('trip_step3');
        $step4 = session('trip_step4');

        // لو في أي خطوة ناقصة، نرجع رسالة خطأ
        if (!$step1 || !$step2 || !$step3 || !$step4) {
            return back()->with('error', 'Incomplete trip data!')->with([
                'step1' => $step1,
                'step2' => $step2,
                'step3' => $step3,
                'step4' => $step4,
            ]);
        }

        // التحقق من وجود الصور الإضافية
        if (!isset($step1['trip_images'])) {
            $step1['trip_images'] = [];
        }

        // حفظ الرحلة الأساسية
        
        $trip = Trip::create([
            'name' => $step1['name'],
            'description' => $step1['description'],
            'price' => $step1['price'],
            'count_days' => $step1['count_days'],
            'start_date' => $step1['start_date'],
            'image' => $step1['image'] ?? null,
            'hotel_id' => $step2['hotel_id'],
            'category_id' => $step2['category_id'],
            'governorate_id' => $step2['governorate_id'],
            'guide_id' => $step3['guide_id'] ?? null,
            'transportation_id' => $step3['transportation_id'] ?? null,
        ]);

        // حفظ الصور الإضافية
        foreach ($step1['trip_images'] as $img) {
            TripImage::create([
                'trip_id' => $trip->id,
                'image' => $img,
            ]);
        }

        // حفظ الأيام مع Polymorphic relation
        foreach ($step4['days'] as $dayData) {
            $day = $trip->days()->create([
                
    'name' => $dayData['name'],
    'date' => $dayData['date'] ?? now(),
    'tripable_id' => $trip->id,
    'tripable_type' => Trip::class,


            ]);

            if (!empty($dayData['activities'])) {
                $day->activities()->attach($dayData['activities']);
            }

            if (!empty($dayData['restaurants'])) {
                $day->restaurants()->attach($dayData['restaurants']);
            }

            if (!empty($dayData['places'])) {
                $day->places()->attach($dayData['places']);
            }
        }

        // مسح الجلسة بعد الحفظ
        session()->forget(['trip_step1', 'trip_step2', 'trip_step3', 'trip_step4']);

        return redirect()->route('dashboard')->with('success', 'Trip created successfully!');

    } catch (\Exception $e) {
        // هنا نرجع الخطأ لتعرفي السبب
        return back()->with('error', 'حدث خطأ أثناء حفظ الرحلة: ' . $e->getMessage())
                     ->withInput();
    }
}






}
