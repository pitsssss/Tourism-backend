@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto mt-10 bg-white p-8 rounded-2xl shadow-lg border border-blue-100">

    <h2 class="text-2xl font-bold text-blue-700 mb-6">Add New Trip</h2>

    <form action="{{ route('admin_trips.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf

        {{-- Trip Name --}}
        <div>
            <label class="block text-blue-600 mb-1">Trip Name</label>
            <input type="text" name="name" class="w-full border border-blue-300 p-2 rounded focus:ring-2 focus:ring-blue-500">
        </div>

        {{-- Category --}}
        <div>
            <label class="block text-blue-600 mb-1">Category</label>
            <select name="category_id" class="w-full border border-blue-300 p-2 rounded focus:ring-2 focus:ring-blue-500">
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Hotel --}}
        <div>
            <label class="block text-blue-600 mb-1">Hotel</label>
            <select name="hotel_id" class="w-full border border-blue-300 p-2 rounded focus:ring-2 focus:ring-blue-500">
                @foreach ($hotels as $hotel)
                    <option value="{{ $hotel->id }}">{{ $hotel->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Governorate --}}
        <div>
            <label class="block text-blue-600 mb-1">Governorate</label>
            <select name="governorate_id" class="w-full border border-blue-300 p-2 rounded focus:ring-2 focus:ring-blue-500">
                @foreach ($governorates as $gov)
                    <option value="{{ $gov->id }}">{{ $gov->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Transport --}}
        <div>
            <label class="block text-blue-600 mb-1">Transport</label>
            <input type="text" name="transport" class="w-full border border-blue-300 p-2 rounded focus:ring-2 focus:ring-blue-500">
        </div>

        {{-- Price --}}
        <div>
            <label class="block text-blue-600 mb-1">Price</label>
            <input type="number" name="price" step="0.01" class="w-full border border-blue-300 p-2 rounded focus:ring-2 focus:ring-blue-500">
        </div>

        {{-- Number of Days --}}
        <div>
            <label class="block text-blue-600 mb-1">Number of Days</label>
            <input type="number" name="count_days" class="w-full border border-blue-300 p-2 rounded focus:ring-2 focus:ring-blue-500">
        </div>

        {{-- Start Date --}}
        <div>
            <label class="block text-blue-600 mb-1">Start Date</label>
            <input type="date" name="start_date" class="w-full border border-blue-300 p-2 rounded focus:ring-2 focus:ring-blue-500">
        </div>

        {{-- Description --}}
        <div>
            <label class="block text-blue-600 mb-1">Description</label>
            <textarea name="description" rows="4" class="w-full border border-blue-300 p-2 rounded focus:ring-2 focus:ring-blue-500"></textarea>
        </div>

        {{-- Main Image --}}
        <div>
            <label class="block text-blue-600 mb-1">Main Image</label>
            <input type="file" name="image" class="w-full border border-blue-300 p-2 rounded bg-white">
        </div>

        {{-- Additional Images --}}
        <div>
            <label class="block text-blue-600 mb-1">Additional Images (You can select multiple)</label>
            <input type="file" name="trip_images[]" multiple class="w-full border border-blue-300 p-2 rounded bg-white">
        </div>

        {{-- Submit Button --}}
        <div>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">Add Trip</button>
        </div>
    </form>
</div>
@endsection
