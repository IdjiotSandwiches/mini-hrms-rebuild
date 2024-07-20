@extends('attendance.layouts.attendance-layout', with(['title' => 'Take Attendance', 'desc' => 'This is where you verify your attendance.']))
@section('title', 'Attendance - Take Attendance')

@section('content')
    <attendance-section class="py-10 flex flex-col gap-6 justify-center items-center select-none">
        <p id="current-day" class="text-center text-5xl font-semibold"></p>
        <div class="flex gap-2">
            <div class="flex justify-center items-center w-48 h-32 bg-gray-300 rounded-md font-semibold text-8xl shadow-md" id="current-hours"></div>
            <div class="flex justify-center items-center w-48 h-32 bg-gray-300 rounded-md font-semibold text-8xl shadow-md" id="current-minutes"></div>
            <div class="flex justify-center items-center w-48 h-32 bg-gray-300 rounded-md font-semibold text-8xl shadow-md" id="current-seconds"></div>
        </div>
        @error('attendanceError')
            <p class="text-red-500">{{ $message }}</p>
        @enderror
        @if ($isCheckedIn)
            <form action="{{ route('attendance.check-out') }}" method="POST">
                @method('PUT')
                @csrf
                <button class="py-2 px-20 text-white text-lg rounded-md bg-blue-600 hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-colors">Check Out</button>
            </form>
        @else
            <form action="{{ route('attendance.check-in') }}" method="POST">
                @csrf
                <button class="py-2 px-20 text-white text-lg rounded-md bg-blue-600 hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-colors">Check In</button>
            </form>
        @endif
    </attendance-section>
    <script type="module">
        @if (Session::has('status'))
            toastr.{{ Session::get('status') }}('{{ Session::get('message') }}')
        @endif

        function getCurrentTime() {
            let now = new Date();
            let currentDay = `${now.toLocaleString('default', {weekday: 'long'})}, ${now.getDate()} ${now.toLocaleString('default', {month: 'long'})} ${now.getFullYear()}`;
            let currentHours = now.getHours().toString().padStart(2, '0');
            let currentMinutes = now.getMinutes().toString().padStart(2, '0');
            let currentSeconds = now.getSeconds().toString().padStart(2, '0');
            $('#current-day').text(currentDay);
            $('#current-hours').text(currentHours);
            $('#current-minutes').text(currentMinutes);
            $('#current-seconds').text(currentSeconds);
            setTimeout(getCurrentTime, 1000);
        }

        $(document).ready(function() {
            getCurrentTime();
        });
    </script>
@endsection

