@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6 text-blue-800">Trips for {{ $user->name }}</h1>

    @forelse ($trips as $trip)
        <div class="bg-white shadow rounded-lg p-4 mb-4 border">
            <p class="text-gray-700 mb-2"><strong>Trip ID:</strong> {{ $trip->id }}</p>
            <p class="text-gray-700 mb-2"><strong>Start Date:</strong> {{ $trip->trip_date_start }}</p>
            <p class="text-gray-700 mb-2"><strong>Status:</strong>
                <span class="px-2 py-1 rounded text-white text-sm 
                    @if($trip->status == 'upcoming') bg-yellow-500
                    @elseif($trip->status == 'in_progress') bg-blue-500
                    @elseif($trip->status == 'finished') bg-green-600
                    @endif">
                    {{ ucwords(str_replace('_', ' ', $trip->status)) }}
                </span>
            </p>

            <form action="{{ route('admin_users.update_trip_status') }}" method="POST" class="mt-3">
                @csrf
                <input type="hidden" name="trip_id" value="{{ $trip->id }}">

                <label for="status" class="block text-sm font-medium text-gray-700">Update Status:</label>
                <select name="status" id="status" class="mt-1 block w-1/2 rounded border-gray-300 shadow-sm">
                    <option value="upcoming" {{ $trip->status == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                    <option value="in_progress" {{ $trip->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="finished" {{ $trip->status == 'finished' ? 'selected' : '' }}>Finished</option>
                </select>

                <button type="submit"
                    class="mt-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-1 px-3 rounded">
                    Save
                </button>
            </form>
        </div>
    @empty
        <p class="text-gray-600">No trips found for this user.</p>
    @endforelse
</div>
@endsection
