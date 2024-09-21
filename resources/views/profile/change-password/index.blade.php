@extends('profile.layouts.profile-layout', with(['title' => 'Change Password', 'desc' => 'This is where you change your account password.']))
@section('title', 'Profile - Change Password')

@section('content')
    <password-section class="text-black dark:text-white">
        <form action="{{ route('profile.change-password') }}" method="POST" class="flex flex-col pt-10 gap-4">
            @csrf
            @method('PUT')
            <password-input class="grid gap-2">
                <label for="update-password" class="block text-sm font-medium text-gray-900 dark:text-white">Password</label>
                <input type="password" id="update-password" name="update_password" @class([
                    'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
                    'border-red-500 dark:border-red-500' => $errors->has('update_password'),
                ]) />
                @error('update_password')
                    <p class="text-red-500">{{ $message }}</p>
                @enderror
            </password-input>
            <confirm-password class="grid gap-2">
                <password-input class="grid gap-2">
                    <label for="confirm-password" class="block text-sm font-medium text-gray-900 dark:text-white">Confirm Password</label>
                    <input type="password" id="confirm-password" name="confirm_password" @class([
                        'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
                        'border-red-500 dark:border-red-500' => $errors->has('confirm_password'),
                    ]) />
                    @error('confirm_password')
                        <p class="text-red-500">{{ $message }}</p>
                    @enderror
                </password-input>
            </confirm-password>
            <div>
                <button type="submit" id="update-btn" class="disabled:bg-blue-400 disabled:dark:bg-blue-500 disabled:cursor-not-allowed text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-md w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" disabled >Update</button>
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
