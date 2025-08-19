@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto mt-10 bg-white p-6 rounded-2xl shadow-md border border-blue-200">
    <h2 class="text-2xl font-bold text-blue-600 mb-6">Edit Restaurant</h2>

    <form action="{{ route('admin_restaurants.update', $restaurant->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-blue-700 font-semibold">Name</label>
            <input type="text" name="name" value="{{ $restaurant->name }}" class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" required>
        </div>

        <div>
            <label class="block text-blue-700 font-semibold">Location</label>
            <input type="text" name="location" value="{{ $restaurant->location }}" class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" required>
        </div>

        <div>
            <label class="block text-blue-700 font-semibold">Description</label>
            <textarea name="description" class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">{{ $restaurant->description }}</textarea>
        </div>

        <div>
            <label class="block text-blue-700 font-semibold">Phone Number</label>
            <input type="text" name="phone_number" value="{{ $restaurant->phone_number }}" class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
        </div>

        <div>
            <label class="block text-blue-700 font-semibold">Governorate</label>
            <select name="governorate_id" class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" required>
                <option value="">-- Select Governorate --</option>
                @foreach($governorates as $gov)
                    <option value="{{ $gov->id }}" {{ $restaurant->governorate_id == $gov->id ? 'selected' : '' }}>
                        {{ $gov->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-blue-700 font-semibold">Rating</label>
            <input type="number" step="0.1" name="rating" value="{{ $restaurant->rating }}" class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
        </div>

        <div>
            <label class="block text-blue-700 font-semibold">Main Image</label>
            <input type="file" name="image" class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
            @if($restaurant->image)
                <img src="{{ asset('imgs/restaurant.img/' . $restaurant->image) }}" alt="" class="w-24 h-24 mt-2 rounded-lg">
            @endif
        </div>

        <div>
            <label class="block text-blue-700 font-semibold">Additional Images</label>
            <input type="file" name="images[]" multiple class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">

            @if($restaurant->images->count())
                <div class="grid grid-cols-3 gap-2 mt-2">
                    @foreach($restaurant->images as $img)
                        <img src="{{ asset('imgs/restaurant.img/' . $img->image) }}" class="w-full h-24 object-cover rounded-lg">
                    @endforeach
                </div>
            @endif
        </div>

        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg w-full">
            Update
        </button>
    </form>
</div>
@endsection
