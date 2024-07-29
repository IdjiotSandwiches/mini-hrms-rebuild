@extends('attendance.layouts.attendance-layout', with(['title' => 'Input Schedule', 'desc' => 'This is where you upload and view your work schedule.']))
@section('title', 'Attendance - Input Schedule')

@php
    $dayWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
@endphp

@section('content')
    @include('attendance.input-schedule.components.schedule-modal')
    @include('attendance.input-schedule.components.confirm-modal')

    <schedule-section class="
        py-10 gap-4 flex flex-col
        dark:text-white
    ">
        <div class="w-full relative overflow-x-auto rounded-md">
            <table class="w-full table-auto text-center text-gray-500">
                <thead class="bg-blue-500 text-white">
                    <tr class="font-semibold">
                        <td class="px-4 py-3">Day</td>
                        <td class="px-4 py-3 bg-blue-600">Time</td>
                        <td class="px-4 py-3">Action</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dayWeek as $day)
                        <tr class="
                            border-b-2 border-gray-200
                            dark:text-white
                        " id="{{ $day }}">
                            <td class="
                                px-4 py-3
                                dark:bg-gray-700
                            ">{{ $day }}</td>
                            <td class="
                                px-4 py-3 bg-gray-100
                                dark:bg-gray-800
                            ">00:00:00 - 00:00:00 (0hr 0m 0s)</td>
                            <td class="
                                px-4 py-3
                                dark:bg-gray-700
                            ">
                                <div class="flex justify-center items-center">
                                    <svg viewBox="0 0 49 42" xmlns="http://www.w3.org/2000/svg" class="action fill-blue-500 transition-colors dark:hover:fill-white h-8 w-8">
                                        <path d="M0.948975 11.8472H30.0956V17.2976H0.948975V11.8472ZM0.948975 6.39681H30.0956V0.946411H0.948975V6.39681ZM0.948975 28.1984H19.4968V22.748H0.948975V28.1984ZM40.7208 19.6685L42.6021 17.7336C42.8472 17.481 43.1384 17.2806 43.459 17.1438C43.7795 17.0071 44.1231 16.9367 44.4701 16.9367C44.8172 16.9367 45.1608 17.0071 45.4813 17.1438C45.8019 17.2806 46.093 17.481 46.3382 17.7336L48.2195 19.6685C49.2528 20.7313 49.2528 22.4482 48.2195 23.511L46.3382 25.4459L40.7208 19.6685ZM38.8396 21.6034L24.7962 36.047V41.8244H30.4135L44.4569 27.3808L38.8396 21.6034Z"/>
                                    </svg>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <p id="submit-error" class="text-red-500 text-center hidden"></p>
        <div class="flex gap-4 justify-end items-center">
            <p class="text-lg font-medium">Work Hours: <span id="work-hours" class="text-red-500">0 Hours</span></p>
            <button class="py-2 px-5 text-white text-lg rounded-md bg-blue-600 hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-colors" id="submit">Save Schedule</button>
        </div>
    </schedule-section>

    @include('attendance.input-schedule.components.common-js')
@endsection
