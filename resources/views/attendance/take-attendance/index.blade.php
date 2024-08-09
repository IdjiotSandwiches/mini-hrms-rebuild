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
@endsection

