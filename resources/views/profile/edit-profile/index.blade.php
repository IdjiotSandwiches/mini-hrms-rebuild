@extends('profile.profile-layout', with(['title' => 'Edit Profile', 'desc' => 'This is how your profile will be displayed.']))
@section('title', 'Profile - Edit Profile')

@section('content')
    <profile-section class="text-black dark:text-white">
        <form action="{{ route('v1.settings.profile.update') }}" method="POST" class="flex flex-col gap-4 py-10" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <name-input class="grid gap-2">
                <label for="name" class="block text-sm font-medium text-gray-900 dark:text-white">Name</label>
                <input type="text" id="name" name="name" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{ auth()->user()->name }}">
                <p class="text-sm text-gray-500 dark:text-gray-300">This is your public display name.</p>
            </name-input>
            <email-input class="grid gap-2">
                <label for="email" class="block text-sm font-medium text-gray-900 dark:text-white">Email</label>
                <input type="email" id="email" name="email" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{ auth()->user()->email }}">
                <p class="text-sm text-gray-500 dark:text-gray-300">This is your public display email.</p>
            </email-input>
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
