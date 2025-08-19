@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto mt-10 bg-white p-6 rounded-xl shadow-lg border border-blue-200">
    <h2 class="text-xl font-bold text-blue-600 mb-4">Step 2: Hotel & Location</h2>

    <form action="{{ route('trips.store.step2') }}" method="POST" class="space-y-4">
        @csrf

        {{-- Governorate --}}
        <div>
            <label class="block text-blue-600">Governorate</label>
            <select name="governorate_id" class="w-full border border-blue-300 p-2 rounded">
                @foreach($governorates as $gov)
                    <option value="{{ $gov->id }}">{{ $gov->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Category --}}
        <div>
            <label class="block text-blue-600">Category</label>
            <select name="category_id" class="w-full border border-blue-300 p-2 rounded">
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Hotel --}}
        <div>
            <label class="block text-blue-600">Hotel</label>
            <select id="hotel-select" name="hotel_id" class="w-full border border-blue-300 p-2 rounded">
                <option value="">-- Select Hotel --</option>
                @foreach($hotels as $hotel)
                    <option value="{{ $hotel->id }}">{{ $hotel->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Room (Dynamic per Hotel) --}}
        <div>
            <label class="block text-blue-600">Room Type</label>
            <select id="room-select" name="room_id" class="w-full border border-blue-300 p-2 rounded">
                <option value="">-- Select Room --</option>
            </select>
        </div>

        <div class="flex justify-between">
            <a href="{{ route('trips.create.step1') }}" class="bg-gray-400 text-white px-6 py-2 rounded">← Back</a>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded">Next →</button>
        </div>
    </form>
</div>

{{-- Small Script for dynamic room loading --}}
<script>
    const hotels = @json($hotels);

    document.getElementById('hotel-select').addEventListener('change', function () {
        let hotelId = this.value;
        let roomSelect = document.getElementById('room-select');
        roomSelect.innerHTML = '<option value="">-- Select Room --</option>';

        if (hotelId) {
            let selectedHotel = hotels.find(h => h.id == hotelId);
            if (selectedHotel && selectedHotel.rooms) {
                selectedHotel.rooms.forEach(room => {
                    roomSelect.innerHTML += `<option value="${room.id}">${room.room_type} - $${room.price}</option>`;
                });
            }
        }
    });
</script>
@endsection
