@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto mt-10 bg-white p-8 rounded-2xl shadow-lg border border-blue-100">
    <h2 class="text-2xl font-bold text-blue-700 mb-6">Tour Guides</h2>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('admin_tour_guides.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Add New Guide</a>

    <table class="w-full mt-6 border-collapse border border-blue-200">
        <thead>
            <tr class="bg-blue-100 text-blue-800">
                <th class="p-3 border border-blue-200">Image</th>
                <th class="p-3 border border-blue-200">Name</th>
                <th class="p-3 border border-blue-200">Phone</th>
                <th class="p-3 border border-blue-200">Rating</th>
                <th class="p-3 border border-blue-200">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($guides as $guide)
            <tr class="hover:bg-blue-50">
                <td class="p-3 border border-blue-200">
                    @if($guide->image)
                        <img src="{{ asset('imgs/guides.img/'.$guide->image) }}" class="w-16 h-16 rounded-full">
                    @else
                        —
                    @endif
                </td>
                <td class="p-3 border border-blue-200">{{ $guide->name }}</td>
                <td class="p-3 border border-blue-200">{{ $guide->phone }}</td>
                <td class="p-3 border border-blue-200">{{ $guide->rating }}</td>
                <td class="p-3 border border-blue-200 flex gap-2">
                    <a href="{{ route('admin_tour_guides.edit', $guide) }}" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">Edit</a>
                    <form action="{{ route('admin_tour_guides.destroy', $guide) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                        @csrf @method('DELETE')
                        <button class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
