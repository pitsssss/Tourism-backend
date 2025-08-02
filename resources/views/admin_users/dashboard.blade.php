@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6 text-blue-800">Users List</h1>

    @foreach ($users as $user)
        <div class="bg-white shadow-md rounded-lg p-4 mb-4 border border-blue-100 flex justify-between items-center">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">{{ $user->name }}</h2>
                <p class="text-sm text-gray-600">{{ ucfirst($user->role) }} - {{ $user->email }}</p>
            </div>
            <a href="{{ route('admin_users.user_trips', $user->id) }}"
               class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded shadow">
               View Trips
            </a>
        </div>
    @endforeach
</div>
@endsection
