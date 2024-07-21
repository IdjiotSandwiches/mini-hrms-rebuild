@extends('profile.layouts.profile-layout', with(['title' => 'Change Password', 'desc' => 'This is where you change your account password.']))
@section('title', 'Profile - Change Password')

@section('content')
    <password-section>
        <form action="" class="flex flex-col py-10 gap-4">
            <password-input class="grid gap-2">
                <label for="update-password" class="font-mediu">Password</label>
                <input type="password" name="update-password" id="update-password" class="px-2 py-1 border-2 border-gray-200 rounded-md focus:outline-blue-500 text-gray-500">
                <p class="text-gray-500">This will be your new account password.</p>
            </password-input>
            <div>
                <button class="px-5 py-1 text-white text-lg rounded-md bg-blue-600 hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-colors">Update</button>
            </div>
        </form>
    </password-section>
@endsection
