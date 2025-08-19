<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use App\Models\governorates;
class AdminRestaurantController extends Controller
{
    public function index()
    {
        $restaurants = Restaurant::with('governorate', 'images')->get();
        return view('admin_restaurants.index', compact('restaurants'));
    }

    public function create()
    {
        $governorates = governorates::all();
        return view('admin_restaurants.create', compact('governorates'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'location' => 'required',
            'description' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'rating' => 'nullable|numeric',
            'governorate_id' => 'required|exists:governorates,id',
            'image' => 'nullable|image',
            'images.*' => 'nullable|image',
        ]);

        // الصورة الرئيسية
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('imgs/restaurant.img'), $filename);
            $data['image'] = $filename;
        }

        $restaurant = Restaurant::create($data);

        // الصور الإضافية
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $filename = time().'_'.$img->getClientOriginalName();
                $img->move(public_path('imgs/restaurant.img'), $filename);
                $restaurant->images()->create(['image' => $filename]);
            }
        }

        return redirect()->route('admin_restaurants.index')->with('success', 'Restaurant added successfully');
    }

    public function edit(Restaurant $restaurant)
    {
        $governorates = governorates::all();
        return view('admin_restaurants.edit', compact('restaurant', 'governorates'));
    }

    public function update(Request $request, Restaurant $restaurant)
    {
        $data = $request->validate([
            'name' => 'required',
            'location' => 'required',
            'description' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'rating' => 'nullable|numeric',
            'governorate_id' => 'required|exists:governorates,id',
            'image' => 'nullable|image',
            'images.*' => 'nullable|image',
        ]);

        // تحديث الصورة الرئيسية
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('imgs/restaurant.img'), $filename);
            $data['image'] = $filename;
        }

        $restaurant->update($data);

        // إضافة صور إضافية جديدة
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $filename = time().'_'.$img->getClientOriginalName();
                $img->move(public_path('imgs/restaurant.img'), $filename);
                $restaurant->images()->create(['image' => $filename]);
            }
        }

        return redirect()->route('admin_restaurants.index')->with('success', 'Restaurant updated successfully');
    }

    public function destroy(Restaurant $restaurant)
    {
        $restaurant->delete();
        return redirect()->route('admin_restaurants.index')->with('success', 'Restaurant deleted successfully');
    }
}
