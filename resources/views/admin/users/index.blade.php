@extends('layouts.app')

@section('content')
<h2>إدارة المستخدمين</h2>

<ul>
    @foreach($users as $user)
        <li>{{ $user->name }} - {{ $user->email }} - {{ $user->role }}</li>
    @endforeach
</ul>
@endsection
