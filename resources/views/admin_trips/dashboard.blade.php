@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto mt-10">

    <h1 class="text-2xl font-bold text-blue-800 mb-6 text-center">All Trips</h1>

    <!-- ✅ زر إضافة -->
    <div class="mb-4">
        <a href="{{ route('admin_trips.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            + Add new Trip 
        </a>
    </div>



    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach ($trips as $trip)
            <div x-data="{ open: false }" class="border border-blue-200 rounded-2xl shadow-md p-4 bg-white">
                
                <!-- ✅ صورة الرحلة + الاسم -->
                <div @click="open = !open" class="cursor-pointer">
                


                      <img src="{{ asset('imgs/trips.img/' . $trip->image) }}" alt="Trip Image" class="w-full h-48 object-cover rounded-xl mb-3">

                    <h2 class="text-lg font-semibold text-blue-700 text-center">{{ $trip->name }}</h2>
                </div>

                <!-- ✅ التفاصيل -->
                <div x-show="open" x-transition class="mt-4 space-y-2 text-sm text-gray-700">
                    <p><span class="font-bold text-blue-600">Transport:</span> {{ $trip->transport }}</p>
                    <p><span class="font-bold text-blue-600">Governorate:</span> {{ $trip->governorate?->name ?? '—' }}</p>
                    <p><span class="font-bold text-blue-600">Days:</span> {{ $trip->count_days }}</p>
                    <p><span class="font-bold text-blue-600">Price:</span> ${{ $trip->price }}</p>
                    <p><span class="font-bold text-blue-600">Description:</span> {{ $trip->description }}</p>
                    <p><span class="font-bold text-blue-600">start_date:</span> {{ $trip->start_date }}</p>
                    <p><span class="font-bold text-blue-600">count_days:</span> {{ $trip->count_days }}</p>
                    <p><span class="font-bold text-blue-600">Tour Guide:</span> {{ $trip->tourGuide?->name ?? '—' }}</p>

@if($trip->tourGuide?->image)
    <img src="{{ asset('imgs/guides.img/' . $trip->tourGuide->image) }}" alt="{{ $trip->tourGuide->name }}" class="w-16 h-16 rounded-full mt-1">
@endif


   


                    {{-- ✅ الصور الإضافية --}}
                    @if($trip->images->count())
                        <div class="mt-2">
                            <span class="font-bold text-blue-600 block mb-1">Additional Images:</span>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($trip->images as $img)
                                 <img src="{{ asset('imgs/trips.img/' . $img->image) }}" width="100" class="inline m-1 rounded">
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- ✅ زر التعديل --}}
                    <div class="text-center mt-4">
                        <a href="{{ route('admin_trips.edit', $trip->id) }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                            Edit Trip
                        </a>
                    </div>
                </div>

            </div>
        @endforeach
    </div>

</div>

{{-- ✅ تضمين AlpineJS --}}
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection
