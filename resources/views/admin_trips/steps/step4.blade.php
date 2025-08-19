@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto mt-10 bg-white p-6 rounded-xl shadow-lg border border-blue-200">
    <h2 class="text-xl font-bold text-blue-600 mb-4">Step 4: Days & Activities</h2>

    <form action="{{ route('trips.store.final') }}" method="POST" class="space-y-4">
        @csrf

        @for($i = 0; $i < session('trip_step1.count_days'); $i++)
        <div class="border p-4 rounded mb-4">
            <h3 class="font-bold text-blue-600 mb-2">Day {{ $i + 1 }}</h3>
            <input type="text" name="days[{{ $i }}][name]" placeholder="Day Name" class="w-full border p-2 rounded mb-2">

            <label class="block text-blue-600">Places</label>
            <select name="days[{{ $i }}][places][]" multiple class="w-full border p-2 rounded mb-2">
                @foreach($places as $place)
                    <option value="{{ $place->id }}">{{ $place->name }}</option>
                @endforeach
            </select>

            <label class="block text-blue-600">Restaurants</label>
            <select name="days[{{ $i }}][restaurants][]" multiple class="w-full border p-2 rounded mb-2">
                @foreach($restaurants as $restaurant)
                    <option value="{{ $restaurant->id }}">{{ $restaurant->name }}</option>
                @endforeach
            </select>

            <label class="block text-blue-600">Activities</label>
            <select name="days[{{ $i }}][activities][]" multiple class="w-full border p-2 rounded mb-2">
                @foreach($activities as $activity)
                    <option value="{{ $activity->id }}">{{ $activity->name }}</option>
                @endforeach
            </select>
        </div>
        @endfor

        <div class="flex justify-between">
        <a href="{{ route('trips.create.step3') }}" class="bg-gray-400 text-white px-6 py-2 rounded">← Back</a>
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded">Finish & Save</button>
        </div>
    </form>
</div>
@endsection
