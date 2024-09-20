@extends('attendance.layouts.attendance-layout', with(['title' => 'Take Attendance', 'desc' => 'This is where you verify your attendance.']))
@section('title', 'Attendance - Take Attendance')

@section('content')
    <attendance-section class="py-10 flex flex-col gap-6 justify-center items-center select-none">
        <p id="current-day" class="
            md:text-4xl
            lg:text-5xl
            text-3xl text-center font-semibold
        "></p>
        <div class="
            sm:grid sm:grid-cols-2
            lg:grid-cols-3
            flex flex-col gap-2
        ">
            <div class="
                flex justify-center items-center w-48 h-32 bg-gray-300 rounded-md font-semibold text-8xl shadow-md
                dark:bg-gray-600
            " id="current-hours"></div>
            <div class="
                flex justify-center items-center w-48 h-32 bg-gray-300 rounded-md font-semibold text-8xl shadow-md
                dark:bg-gray-600
            " id="current-minutes"></div>
            <div class="
                hidden lg:flex justify-center items-center w-48 h-32 bg-gray-300 rounded-md font-semibold text-8xl shadow-md
                dark:bg-gray-600
            " id="current-seconds"></div>
        </div>
        <form action="{{ route('attendance.take-attendance') }}" method="POST">
            @csrf
            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-md w-full sm:w-auto px-20 py-2 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                @if($isCheckedIn)
                    Check Out
                @else
                    Check In
                @endif
            </button>
        </form>
    </attendance-section>
@endsection

@section('extra-js')
    <script>
        $(document).ready(function() {
            $('button[type="submit"]').click(function() {
                disableButton();
            });
        });
    </script>
@endsection

