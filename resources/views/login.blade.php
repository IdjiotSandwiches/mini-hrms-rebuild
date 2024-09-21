@extends('layouts.login-register')
@section('title', 'Login')

@section('content')
    <form action="{{ route('attemptLogin') }}" method="POST" class="flex flex-col gap-5 w-full px-10 sm:grid sm:px-10 md:px-28 lg:w-1/2 lg:px-0 xl:w-1/2 2xl:w-1/3">
        @csrf
        <email-form @class([
            'grid border-2 rounded-md px-2 py-1 text-gray-400',
            'border-red-500 text-red-500' => $errors->has('email'),
        ])>
            <label for="email" class="text-xs font-medium">E-mail</label>
            <input type="email" name="email" id="email" class="focus:outline-none text-gray-900 py-1 border-0 focus:ring-0 p-0 dark:bg-[#121212] dark:text-white" value="{{ old('email') }}">
            @error('email')
                <p class="text-red-500">{{ $message }}</p>
            @enderror
        </email-form>
        <password-form @class([
            'grid border-2 rounded-md px-2 py-1 text-gray-400',
            'border-red-500 text-red-500' => $errors->has('password'),
        ])>
            <label for="password" class="text-xs font-medium">Password</label>
            <input type="password" name="password" id="password" autocomplete="off" class="focus:outline-none text-gray-900 py-1 border-0 focus:ring-0 p-0 dark:bg-[#121212] dark:text-white">
            @error('password')
                <p class="text-red-500">{{ $message }}</p>
            @enderror
        </password-form>
        <button type="submit" class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-md px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Login</button>
        <p class="text-center text-sm">
            Don't have an account?
            <a href="{{ route('register') }}" class="font-semibold leading-6 text-blue-600 transition-colors hover:text-blue-500 underline decoration-2 underline-offset-2">Register</a>
        </p>
    </form>
@endsection
