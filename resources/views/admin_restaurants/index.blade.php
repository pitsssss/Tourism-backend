@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto mt-10">
    <h2 class="text-2xl font-bold text-blue-600 mb-6">Restaurants Management</h2>

    <a href="{{ route('admin_restaurants.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg mb-4 inline-block">
        + Add Restaurant
    </a>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($restaurants as $restaurant)
            <div class="bg-white rounded-2xl shadow-md p-4 border border-blue-200">
                @if($restaurant->image)
                    <img src="{{ asset('imgs/restaurant.img/' . $restaurant->image) }}" 
                         alt="{{ $restaurant->name }}" 
                         class="w-full h-40 object-cover rounded-xl mb-3">
                @endif

                <h3 class="text-lg font-semibold text-blue-700">{{ $restaurant->name }}</h3>
                <p class="text-gray-600">{{ $restaurant->location }}</p>
                <p class="text-sm text-gray-500">Phone: 📞 {{ $restaurant->phone_number ?? '—' }}</p>
                <p class="text-sm text-gray-500">Governorate: 🏙 {{ $restaurant->governorate->name ?? '—' }}</p>
                <p class="text-sm text-gray-500">Rating: ⭐ {{ $restaurant->rating ?? '—' }}</p>

                @if($restaurant->images->count())
                    <div class="grid grid-cols-3 gap-2 mt-2">
                        @foreach($restaurant->images as $img)
                            <img src="{{ asset('imgs/restaurant.img/' . $img->image) }}" class="w-full h-24 object-cover rounded-lg">
                        @endforeach
                    </div>
                @endif

                <div class="mt-3 flex gap-2">
                    <a href="{{ route('admin_restaurants.edit', $restaurant->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg">Edit</a>
                    <form action="{{ route('admin_restaurants.destroy', $restaurant->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg">Delete</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
