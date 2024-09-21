@extends('layouts.login-register')
@section('title', 'Home')

@section('content')
    <form action="{{ route('attemptRegister') }}" method="POST" class="flex flex-col gap-5 w-full px-10 sm:grid sm:px-10 md:px-28 lg:w-1/2 lg:px-0 xl:w-1/2 2xl:w-1/3">
        @csrf
        <email-form @class([
            'flex flex-col border-2 rounded-md px-2 py-1 text-gray-400 sm:grid',
            'border-red-500 text-red-500' => $errors->has('email'),
        ])>
            <label for="email" class="text-xs font-medium">E-mail</label>
            <input type="email" name="email" id="email" class="focus:outline-none text-gray-900 py-1 border-0 focus:ring-0 p-0 dark:bg-[#121212] dark:text-white" value="{{ old('email') }}">
            @error('email')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </email-form>
        <fullname-form class="sm:grid sm:grid-cols-2flex flex-col gap-5">
            <first-name @class([
                'flex flex-col border-2 rounded-md px-2 py-1 text-gray-400 sm:grid',
                'border-red-500 text-red-500' => $errors->has('first_name'),
            ])>
                <label for="first-name" class=" text-xs font-medium">First Name</label>
                <input type="text" name="first_name" id="first-name" autocomplete="off" class="focus:outline-none text-gray-900 py-1 border-0 focus:ring-0 p-0 dark:bg-[#121212] dark:text-white" value="{{ old('first_name') }}">
                @error('first_name')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </first-name>
            <last-name @class([
                'flex flex-col border-2 rounded-md px-2 py-1 text-gray-400 sm:grid',
                'border-red-500 text-red-500' => $errors->has('last_name'),
            ])>
                <label for="last-name" class="text-xs font-medium">Last Name</label>
                <input type="text" name="last_name" id="last-name" autocomplete="off" class="focus:outline-none text-gray-900 py-1 border-0 focus:ring-0 p-0 dark:bg-[#121212] dark:text-white" value="{{ old('last_name') }}">
                @error('last_name')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </last-name>
        </fullname-form>
        <password-form @class([
            'flex flex-col border-2 rounded-md px-2 py-1 text-gray-400 sm:grid',
            'border-red-500 text-red-500' => $errors->has('password'),
        ])>
            <label for="password" class="text-xs font-medium">Password</label>
            <input type="password" name="password" id="password" autocomplete="off" class="focus:outline-none text-gray-900 py-1 border-0 focus:ring-0 p-0 dark:bg-[#121212] dark:text-white">
            @error('password')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </password-form>
        <password-form @class([
            'flex flex-col border-2 rounded-md px-2 py-1 text-gray-400 sm:grid',
            'border-red-500 text-red-500' => $errors->has('password_confirmation'),
        ])>
            <label for="password" class="text-xs font-medium">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password-confirmation" autocomplete="off" class="focus:outline-none text-gray-900 py-1 border-0 focus:ring-0 p-0 dark:bg-[#121212] dark:text-white">
            @error('password_confirmation')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </password-form>
        <button type="submit" class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-md px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Create My Account</button>
        <p class="text-center text-sm">
            Already have an account?
            <a href="{{ route('login') }}" class="font-semibold leading-6 text-blue-600 transition-colors hover:text-blue-500 underline decoration-2 underline-offset-2">Login</a>
        </p>
    </form>
@endsection
