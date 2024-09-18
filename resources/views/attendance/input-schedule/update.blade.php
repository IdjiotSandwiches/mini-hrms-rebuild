@extends('attendance.layouts.attendance-layout', with(['title' => 'Input Schedule', 'desc' => 'This is where you upload and view your work schedule.']))
@section('title', 'Attendance - Input Schedule')

@php
    $dayWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
@endphp

@section('content')
    @include('attendance.input-schedule.components.schedule-modal')

    <schedule-section class="
        py-10 gap-4 flex flex-col
        dark:text-white
    ">
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Day</th>
                        <th scope="col" class="px-6 py-3">Time</th>
                        <th scope="col" class="px-6 py-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dayWeek as $day)
                        <tr id="{{ $day }}" class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $day }}</th>
                            <td class="px-6 py-4">00:00:00 - 00:00:00 (0hr 0m 0s)</td>
                            <td class="px-6 py-4">
                                <button class="action font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <p class="text-lg font-medium text-center">Work Hours: <span id="work-hours" class="text-red-500">0 Hours</span></p>
        <div @class([
            'justify-between' => $isUpdateSchedule,
            'flex justify-end'
        ])>
            @if ($isUpdateSchedule)
                <a href="{{ route('attendance.input-schedule-page') }}" class="py-2 px-5 text-white text-lg rounded-md bg-red-600 hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600 transition-colors">Cancel</a>
            @endif
            <button class="py-2 px-5 text-white text-lg rounded-md bg-blue-600 hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-colors" id="submit">Save Schedule</button>
        </div>
    </schedule-section>
@endsection

@section('extra-js')
    @include('attendance.input-schedule.components.common-js')
@endsection
