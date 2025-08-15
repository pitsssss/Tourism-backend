@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto mt-10 bg-white p-8 rounded-2xl shadow-lg border border-blue-100">

    <h2 class="text-2xl font-bold text-blue-700 mb-6">Edit Tour Guide</h2>

    <form action="{{ route('admin_tour_guides.update', $tourGuide) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-blue-600 mb-1">Name</label>
            <input type="text" name="name" value="{{ old('name', $tourGuide->name) }}" class="w-full border border-blue-300 p-2 rounded focus:ring-2 focus:ring-blue-500" required>
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-blue-600 mb-1">Phone</label>
            <input type="text" name="phone" value="{{ old('phone', $tourGuide->phone) }}" class="w-full border border-blue-300 p-2 rounded focus:ring-2 focus:ring-blue-500" required>
            @error('phone')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-blue-600 mb-1">Rating (0 to 5)</label>
            <input type="number" step="0.1" min="0" max="5" name="rating" value="{{ old('rating', $tourGuide->rating) }}" class="w-full border border-blue-300 p-2 rounded focus:ring-2 focus:ring-blue-500">
            @error('rating')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-blue-600 mb-1">Current Image</label>
            @if($tourGuide->image)
                <img src="{{ asset('imgs/guides/' . $tourGuide->image) }}" alt="{{ $tourGuide->name }}" class="w-20 h-20 rounded-full mb-3">
            @else
                <p class="text-gray-500">No image uploaded</p>
            @endif
        </div>

        <div>
            <label class="block text-blue-600 mb-1">Change Image</label>
            <input type="file" name="image" class="w-full border border-blue-300 p-2 rounded bg-white">
            @error('image')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">Update Guide</button>
        </div>
    </form>

</div>
@endsection
