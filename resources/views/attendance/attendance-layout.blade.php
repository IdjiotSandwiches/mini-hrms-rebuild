@props(['title', 'desc'])

@extends('layouts.main')

@section('main-title')
    <div class="border-gray-200 border-b-2 py-5">
        <h1 class="text-2xl font-semibold">Attendance</h1>
        <p>Manage your work schedule and attendance here.</p>
    </div>
@endsection

@section('sub-title')
    <div class="border-gray-200 border-b-2 pb-5">
        <h2 class="text-xl font-semibold">{{ $title }}</h2>
        <p class="text-gray-500 dark:text-gray-300">{{ $desc }}</p>
    </div>
@endsection

@section('content')
    @yield('content')
@endsection
