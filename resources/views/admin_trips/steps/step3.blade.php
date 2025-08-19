@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto mt-10 bg-white p-6 rounded-xl shadow-lg border border-blue-200">
    <h2 class="text-xl font-bold text-blue-600 mb-4">Step 3: Guide & Transport</h2>

    <form action="{{ route('trips.store.step3') }}" method="POST" class="space-y-4">
        @csrf

        {{-- Tour Guide --}}
        <div>
            <label class="block text-blue-600">Tour Guide</label>
            <select name="guide_id" class="w-full border border-blue-300 p-2 rounded">
                <option value="">-- Select Guide --</option>
                @foreach($tourGuides as $guide)
                    <option value="{{ $guide->id }}">{{ $guide->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Transport --}}
        <div>
            <label class="block text-blue-600">Transport</label>
            <select name="transportation_id" class="w-full border border-blue-300 p-2 rounded">
                <option value="">-- Select Transport --</option>
                @foreach($transports as $transport)
                    <option value="{{ $transport->id }}">{{ $transport->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex justify-between">
            <a href="{{ route('trips.create.step2') }}" class="bg-gray-400 text-white px-6 py-2 rounded">← Back</a>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded">Next →</button>
        </div>
    </form>
</div>
@endsection
