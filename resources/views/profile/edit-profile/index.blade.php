@extends('profile.layouts.profile-layout', with(['title' => 'Edit Profile', 'desc' => 'This is how your profile will be displayed.']))
@section('title', 'Profile - Edit Profile')

@section('content')
    <profile-section>
        <form action="" class="flex flex-col gap-4 py-10">
            @csrf
            <username-input class="grid gap-2">
                <label for="username" class="font-medium">Username</label>
                <input type="text" name="username" id="username" class="py-1 px-2 border-2 border-gray-200 rounded-md disabled:bg-white text-gray-500" value="Test" disabled>
                <p class="text-gray-500">This is your public display username.</p>
            </username-input>
            <email-input class="grid gap-2">
                <label for="email" class="font-medium">Email</label>
                <input type="email" name="email" id="email" class="py-1 px-2 border-2 border-gray-200 rounded-md disabled:bg-white text-gray-500" disabled>
                <p class="text-gray-500">This is your public display email.</p>
            </email-input>
            <name-input class="grid gap-2">
                <label for="first-name" class="font-medium">First Name</label>
                <input type="text" name="first-name" id="first-name" class="py-1 px-2 border-2 border-gray-200 rounded-md disabled:bg-white text-gray-500 focus:outline-blue-500">
                <p class="text-gray-500">This is your birth first name.</p>
            </name-input>
            <name-input class="grid gap-2">
                <label for="last-name" class="font-medium">Last Name</label>
                <input type="text" name="last-name" id="last-name" class="py-1 px-2 border-2 border-gray-200 rounded-md disabled:bg-white text-gray-500 focus:outline-blue-500">
                <p class="text-gray-500">This is your birth last name.</p>
            </name-input>
            <avatar-input class="grid gap-2">
                <label for="avatar" class="font-medium">Avatar</label>
                <input type="file" name="avatar" id="avatar" class="">
                <p class="text-gray-500">This is your public display avatar.</p>
            </avatar-input>
            <div>
                <button class="px-6 py-1 text-white text-lg rounded-md bg-blue-600 hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-colors">Update</button>
            </div>
        </form>
    </profile-section>
@endsection
