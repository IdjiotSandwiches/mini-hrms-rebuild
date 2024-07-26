@extends('profile.layouts.profile-layout', with(['title' => 'Edit Profile', 'desc' => 'This is how your profile will be displayed.']))
@section('title', 'Profile - Edit Profile')

@section('content')
    <profile-section class="text-black dark:text-white">
        <form action="{{ route('profile.edit-profile') }}" method="POST" class="flex flex-col gap-4 py-10" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <avatar-input class="grid gap-2">
                <label for="avatar" class="font-medium">Avatar</label>
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
                <p class="text-gray-500 dark:text-gray-300">This is your public display avatar.</p>
                @error('avatar')
                    <p class="text-red-500">{{ $message }}</p>
                @enderror
            </avatar-input>
            <username-input class="grid gap-2">
                <label for="username" class="font-medium">Username</label>
                <input type="text" name="username" id="username" class="
                    py-1 px-2 border-2 border-gray-200 rounded-md disabled:bg-gray-200 text-gray-500
                    dark:disabled:bg-gray-500 dark:placeholder:text-gray-300 dark:text-gray-200
                " value="{{ $userInformation->username }}" disabled>
                <p class="text-gray-500 dark:text-gray-300">This is your public display username.</p>
            </username-input>
            <email-input class="grid gap-2">
                <label for="email" class="font-medium">Email</label>
                <input type="email" name="email" id="email" class="
                    py-1 px-2 border-2 border-gray-200 rounded-md disabled:bg-gray-200 text-gray-500
                    dark:disabled:bg-gray-500 dark:placeholder:text-gray-300 dark:text-gray-200
                " value="{{ $userInformation->email }}" disabled>
                <p class="text-gray-500 dark:text-gray-300">This is your public display email.</p>
            </email-input>
            <name-input class="grid gap-2">
                <label for="first-name" class="font-medium">First Name</label>
                <input type="text" name="first_name" id="first-name" class="
                    py-1 px-2 border-2 border-gray-200 rounded-md disabled:bg-white focus:outline-blue-500
                    dark:bg-gray-500 dark:placeholder:text-gray-300
                " placeholder="{{ $userInformation->firstName }}">
                <p class="text-gray-500 dark:text-gray-300">This is your birth first name.</p>
                @error('first_name')
                    <p class="text-red-500">{{ $message }}</p>
                @enderror
            </name-input>
            <name-input class="grid gap-2">
                <label for="last-name" class="font-medium">Last Name</label>
                <input type="text" name="last_name" id="last-name" class="
                    py-1 px-2 border-2 border-gray-200 rounded-md disabled:bg-white focus:outline-blue-500
                    dark:bg-gray-500 dark:placeholder:text-gray-300
                " placeholder="{{ $userInformation->lastName }}">
                <p class="text-gray-500 dark:text-gray-300">This is your birth last name.</p>
                @error('last_name')
                    <p class="text-red-500">{{ $message }}</p>
                @enderror
            </name-input>
            <div>
                <button disabled id="update-btn" class="px-6 py-1 text-white text-lg rounded-md bg-blue-600 disabled:bg-gray-500 hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-colors">Update</button>
            </div>
        </form>
    </profile-section>

    <script type="module">
        @if (Session::has('status'))
            toastr.{{ Session::get('status') }}('{{ Session::get('message') }}');
        @endif

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
