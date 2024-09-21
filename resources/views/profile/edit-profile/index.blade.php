@extends('profile.layouts.profile-layout', with(['title' => 'Edit Profile', 'desc' => 'This is how your profile will be displayed.']))
@section('title', 'Profile - Edit Profile')

@section('content')
    <profile-section class="text-black dark:text-white">
        <form action="{{ route('profile.edit-profile') }}" method="POST" class="flex flex-col gap-4 py-10" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <avatar-input class="grid gap-2">
                <label for="avatar" class="block text-sm font-medium text-gray-900 dark:text-white">Avatar</label>
                <div class="w-40 h-40 rounded-full">
                    <div class="absolute rounded-full bg-black bg-opacity-0 opacity-0 transition ease-out hover:bg-opacity-30 hover:opacity-100 w-40 h-40">
                        <div class="flex flex-col gap-2 justify-center items-center h-full fill-white">
                            <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="50" height="50" viewBox="0 0 24 24">
                                <path d="M 18 2 L 15.585938 4.4140625 L 19.585938 8.4140625 L 22 6 L 18 2 z M 14.076172 5.9238281 L 3 17 L 3 21 L 7 21 L 18.076172 9.9238281 L 14.076172 5.9238281 z"></path>
                            </svg>
                            <p class="text-white text-center text-sm">Click to upload your avatar</p>
                            <input type="file" name="avatar" id="avatar" class="absolute w-40 h-40 rounded-full opacity-0">
                        </div>
                    </div>
                    <img src="{{ asset($userInformation->avatar) }}" alt="" class="rounded-full w-40 h-40" id="avatar-preview">
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-300">This is your public display avatar.</p>
                @error('avatar')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </avatar-input>
            <username-input class="grid gap-2">
                <label for="username" class="block text-sm font-medium text-gray-900 dark:text-white">Username</label>
                <input type="text" id="username" name="username" aria-label="disabled input" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{ $userInformation->username }}" disabled>
                <p class="text-sm text-gray-500 dark:text-gray-300">This is your public display username.</p>
            </username-input>
            <email-input class="grid gap-2">
                <label for="email" class="block text-sm font-medium text-gray-900 dark:text-white">Email</label>
                <input type="email" id="email" name="email" aria-label="disabled input" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{ $userInformation->email }}" disabled>
                <p class="text-sm text-gray-500 dark:text-gray-300">This is your public display email.</p>
            </email-input>
            <name-input class="grid gap-2">
                <label for="first-name" class="block text-sm font-medium text-gray-900 dark:text-white">First Name</label>
                <input type="text" id="first-name" name="first_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="{{ $userInformation->firstName }}" />
                <p class="text-sm text-gray-500 dark:text-gray-300">This is your birth first name.</p>
                @error('first_name')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </name-input>
            <name-input class="grid gap-2">
                <label for="last-name" class="block text-sm font-medium text-gray-900 dark:text-white">Last Name</label>
                <input type="text" id="last-name" name="last_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="{{ $userInformation->lastName }}" />
                <p class="text-sm text-gray-500 dark:text-gray-300">This is your birth last name.</p>
                @error('last_name')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </name-input>
            <div>
                <button type="submit" id="update-btn" class="disabled:bg-blue-400 disabled:dark:bg-blue-500 disabled:cursor-not-allowed text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-md w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" disabled >Update</button>
            </div>
        </form>
    </profile-section>
@endsection

@section('extra-js')
    <script>
        $(document).ready(function() {
            $('input').on('input change', function() {
                $('#update-btn').prop('disabled', !$(this).val().length);
            });

            $('#avatar').change(function(e) {
                let imgUrl = URL.createObjectURL(e.target.files[0]);
                $('#avatar-preview').attr('src', imgUrl);
            })
        });
    </script>
@endsection
