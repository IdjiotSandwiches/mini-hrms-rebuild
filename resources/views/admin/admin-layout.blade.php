@props(['title', 'desc'])

@extends('layouts.main')

@section('main-title')
    <div class="border-gray-200 border-b-2 py-5">
        <h1 class="text-2xl font-semibold">{{ $title }}</h1>
        <p>{{ $desc }}</p>
    </div>
@endsection

@section('content')
    @yield('content')
@endsection
