@props(['title', 'desc'])

@extends('layouts.main', ['sidebar' => false])

@section('main-title')
    <div class="border-gray-200 border-b-2 py-5">
        <h1 class="text-2xl font-semibold">Profile</h1>
        <p>Manage your account settings here.</p>
    </div>
@endsection

@section('sub-title')
    <div class="border-gray-200 border-b-2 pb-5">
        <h2 class="text-xl font-semibold">{{ $title }}</h2>
        <p class="text-gray-500 dark:text-gray-300">{{ $desc }}</p>
    </div>
@endsection

@section('sidebar')
    @include('components.sidebar-item', with(['item_title' => 'Edit Profile', 'path' => ['v1.settings.profile.edit']]))
    @include('components.sidebar-item', with(['item_title' => 'Change Password', 'path' => ['v1.settings.security.edit']]))
@endsection

@section('content')
    @yield('content')
@endsection

