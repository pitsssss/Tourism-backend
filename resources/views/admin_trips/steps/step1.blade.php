@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto mt-10 bg-white p-6 rounded-xl shadow-lg border border-blue-200">
    <h2 class="text-xl font-bold text-blue-600 mb-4">Step 1: Basic Trip Info</h2>

    <form action="{{ route('trips.store.step1') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div>
            <label class="block text-blue-600">Trip Name</label>
            <input type="text" name="name" class="w-full border border-blue-300 p-2 rounded">
        </div>

        <div>
            <label class="block text-blue-600">Description</label>
            <textarea name="description" rows="3" class="w-full border border-blue-300 p-2 rounded"></textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-blue-600">Price</label>
                <input type="number" name="price" class="w-full border border-blue-300 p-2 rounded">
            </div>
            <div>
                <label class="block text-blue-600">Number of Days</label>
                <input type="number" name="count_days" class="w-full border border-blue-300 p-2 rounded">
            </div>
        </div>

        <div>
            <label class="block text-blue-600">Start Date</label>
            <input type="date" name="start_date" class="w-full border border-blue-300 p-2 rounded">
        </div>

        <div>
            <label class="block text-blue-600">Main Image</label>
            <input type="file" name="image" class="w-full border border-blue-300 p-2 rounded bg-white">
        </div>

        <div>
            <label class="block text-blue-600">Additional Images</label>
            <input type="file" name="trip_images[]" multiple class="w-full border border-blue-300 p-2 rounded bg-white">
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded">Next →</button>
        </div>
    </form>
</div>
@endsection
