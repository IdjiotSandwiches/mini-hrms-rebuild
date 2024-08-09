@extends('profile.layouts.profile-layout', with(['title' => 'Change Password', 'desc' => 'This is where you change your account password.']))
@section('title', 'Profile - Change Password')

@section('content')
    <password-section class="text-black dark:text-white">
        <form action="{{ route('profile.change-password') }}" method="POST" class="flex flex-col pt-10 gap-4">
            @csrf
            @method('PUT')
            <password-input class="grid gap-2">
                <label for="update-password" class="font-medium">Password</label>
                <input type="password" name="update_password" id="update-password" @class([
                    'px-2 py-1 border-2 border-gray-200 rounded-md focus:border-blue-500 focus:ring-blue-500',
                    'dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500',
                    'border-red-500 dark:border-red-500' => $errors->has('update_password')
                ])>
                @error('update_password')
                    <p class="text-red-500">{{ $message }}</p>
                @enderror
            </password-input>
            <confirm-password class="grid gap-2">
                <password-input class="grid gap-2">
                    <label for="confirm-password" class="font-medium">Confirm Password</label>
                    <input type="password" name="confirm_password" id="confirm-password" @class([
                        'px-2 py-1 border-2 border-gray-200 rounded-md focus:border-blue-500 focus:ring-blue-500',
                        'dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500',
                        'border-red-500 dark:border-red-500' => $errors->has('confirm_password'),
                    ])>
                    @error('confirm_password')
                        <p class="text-red-500">{{ $message }}</p>
                    @enderror
                </password-input>
            </confirm-password>
            <div>
                <button disabled id="update-btn" class="px-5 py-1 text-white text-lg rounded-md bg-blue-600 disabled:bg-gray-500 hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-colors">Update</button>
            </div>
        </form>
    </password-section>

@endsection

@section('extra-js')
    <script>
        $(document).ready(function() {
            $('input[type="password"]').on('input change', function() {
                $('#update-btn').prop('disabled', !$(this).val().length);
            });
        });
    </script>
@endsection
