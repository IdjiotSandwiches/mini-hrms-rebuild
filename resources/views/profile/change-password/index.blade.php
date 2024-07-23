@extends('profile.layouts.profile-layout', with(['title' => 'Change Password', 'desc' => 'This is where you change your account password.']))
@section('title', 'Profile - Change Password')

@section('content')
    @if (!$isUpdateTime)
        <timer-section class="py-10 flex flex-col">
            <h1 class="text-lg">You can change your password in: <span id="countdown" class="text-red-500 font-medium"></span></h1>
        </timer-section>
    @else
        <password-section>
            <form action="{{ route('profile.change-password') }}" method="POST" class="flex flex-col pt-10 gap-4">
                @csrf
                @method('PUT')
                <password-input class="grid gap-2">
                    <label for="update-password" class="font-medium">Password</label>
                    <input type="password" name="update_password" id="update-password" class="px-2 py-1 border-2 border-gray-200 rounded-md focus:outline-blue-500 text-gray-500
                        @error('update_password')
                            border-red-500
                        @enderror
                    ">
                    @error('update_password')
                        <p class="text-red-500">{{ $message }}</p>
                    @enderror
                </password-input>
                <confirm-password class="grid gap-2">
                    <password-input class="grid gap-2">
                        <label for="confirm-password" class="font-medium">Confirm Password</label>
                        <input type="password" name="confirm_password" id="confirm-password" class="px-2 py-1 border-2 border-gray-200 rounded-md focus:outline-blue-500 text-gray-500
                            @error('confirm_password')
                                border-red-500
                            @enderror
                        ">
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
    @endif

    <script type="module">
        @if (Session::has('status'))
            toastr.{{ Session::get('status') }}('{{ Session::get('message') }}');
        @endif

        let countdown = {{ $countdown }};
        let timerInterval;

        function timer(e) {
            let hours = Math.floor(countdown / 3600);
            let minutes = Math.floor(countdown / 60) % 60;
            let seconds = countdown % 60;

            $('#countdown').text(`${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`);

            if(countdown <= 0) {
                clearInterval(timerInterval);
                window.location.reload();
            }
            else countdown--;
        }

        $(document).ready(function() {
            $('input[type="password"]').on('input change', function() {
                $('#update-btn').prop('disabled', !$(this).val().length);
            });

            timer();
            timerInterval = setInterval(timer, 1000);
        });
    </script>
@endsection
