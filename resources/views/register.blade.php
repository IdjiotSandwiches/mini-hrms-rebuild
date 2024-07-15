@extends('layouts.login-register')
@section('title', 'Home')

@section('content')
    <form action="{{ route('attemptRegister') }}" method="POST" class="grid gap-5 w-1/3">
        @csrf
        <email-form class="grid border-2 rounded-md px-2 py-1 text-gray-400
            @error('email')
                border-red-500 text-red-500
            @enderror">
            <label for="email" class="text-xs font-medium">E-mail</label>
            <input type="email" name="email" id="email" class="focus:outline-none text-gray-900 py-1" value="{{ old('email') }}">
            @error('email')
                <p class="text-red-500">{{ $message }}</p>
            @enderror
        </email-form>
        <fullname-form class="grid grid-cols-2 gap-5">
            <first-name class="grid border-2 rounded-md px-2 py-1 text-gray-400
                @error('first_name')
                    border-red-500 text-red-500
                @enderror">
                <label for="first-name" class=" text-xs font-medium">First Name</label>
                <input type="text" name="first_name" id="first-name" autocomplete="off" class="focus:outline-none text-gray-900 py-1" value="{{ old('first_name') }}">
                @error('first_name')
                    <p class="text-red-500">{{ $message }}</p>
                @enderror
            </first-name>
            <last-name class="grid border-2 rounded-md px-2 py-1 text-gray-400
                @error('last_name')
                    border-red-500 text-red-500
                @enderror">
                <label for="last-name" class="text-xs font-medium">Last Name</label>
                <input type="text" name="last_name" id="last-name" autocomplete="off" class="focus:outline-none text-gray-900 py-1" value="{{ old('last_name') }}">
                @error('last_name')
                    <p class="text-red-500">{{ $message }}</p>
                @enderror
            </last-name>
        </fullname-form>
        <password-form class="grid border-2 rounded-md px-2 py-1 text-gray-400
            @error('password')
                border-red-500 text-red-500
            @enderror">
            <label for="password" class="text-xs font-medium">Password</label>
            <input type="password" name="password" id="password" autocomplete="off" class="focus:outline-none text-gray-900 py-1">
            @error('password')
                <p class="text-red-500">{{ $message }}</p>
            @enderror
        </password-form>
        <password-form class="grid border-2 rounded-md px-2 py-1 text-gray-400
            @error('password_confirmation')
                border-red-500 text-red-500
            @enderror">
            <label for="password" class="text-xs font-medium">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password-confirmation" autocomplete="off" class="focus:outline-none text-gray-900 py-1">
            @error('password_confirmation')
                <p class="text-red-500">{{ $message }}</p>
            @enderror
        </password-form>
        <button class="py-2 text-white text-lg rounded-md bg-blue-600 hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-colors">Create My Account</button>
        <p class="text-center text-sm">
            Already have an account?
            <a href="{{ route('login') }}" class="font-semibold leading-6 text-blue-600 transition-colors hover:text-blue-500 underline decoration-2 underline-offset-2">Login</a>
        </p>
    </form>
@endsection
