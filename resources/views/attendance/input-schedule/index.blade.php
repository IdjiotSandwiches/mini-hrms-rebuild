@extends('attendance.layouts.attendance-layout', with(['title' => 'Input Schedule', 'desc' => 'This is where you upload and view your work schedule.']))
@section('title', 'Attendance - Input Schedule')

@php
    $dayWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thrusday', 'Friday', 'Saturday', 'Sunday'];
@endphp

@section('content')
    <schedule-modal class="schedule-modal hidden z-30 absolute bg-black backdrop-blur-sm bg-opacity-30 w-screen h-screen left-0 top-0 overflow-hidden">
        <div class="flex justify-center items-center h-full">
            <div class="modal grid bg-white px-8 py-6 w-1/3 rounded-md gap-8">
                <header class="flex justify-between">
                    <h1 id="day" class="text-2xl font-medium">Day</h1>
                    <button class="close-btn">Close</button>
                </header>
                <form class="grid gap-8">
                    <schedule-form class="grid grid-cols-2 gap-10 justify-between">
                        <div class="flex flex-col gap-2">
                            <label for="from">From Hour</label>
                            <input type="time" name="from" id="" class="border-black border-2 rounded-md px-2 py-1">
                        </div>
                        <div class="flex flex-col gap-2">
                            <label for="">To Hour</label>
                            <input type="time" name="" id="" class="border-black border-2 rounded-md px-2 py-1">
                        </div>
                    </schedule-form>
                    <submit-button class="flex justify-end">
                        <button class="py-1 px-4 text-white text-lg rounded-md bg-blue-600 hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-colors">Confirm</button>
                    </submit-button>
                </form>
            </div>
        </div>
    </schedule-modal>

    <schedule-section class="py-10 gap-4 flex flex-col">
        <div class="w-full relative overflow-x-auto rounded-md">
            <table class="w-full table text-center text-gray-500">
                <thead class="bg-blue-500 text-white">
                    <tr class="font-semibold">
                        <td class="px-4 py-3">Day</td>
                        <td class="px-4 py-3 bg-blue-600">Time</td>
                        <td class="px-4 py-3">Action</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dayWeek as $day)
                        <tr class="border-b-2 border-gray-200" id="{{ $day }}">
                            <td class="px-4 py-3">{{ $day }}</td>
                            <td class="px-4 py-3 bg-gray-100">00:00 AM - 00:00 AM (0hr 0m)</td>
                            <td class="flex justify-center px-4 py-3">
                                <svg width="35" height="30" viewBox="0 0 49 42" fill="none" xmlns="http://www.w3.org/2000/svg" class="action" id="{{ $day }}">
                                    <path d="M0.948975 11.8472H30.0956V17.2976H0.948975V11.8472ZM0.948975 6.39681H30.0956V0.946411H0.948975V6.39681ZM0.948975 28.1984H19.4968V22.748H0.948975V28.1984ZM40.7208 19.6685L42.6021 17.7336C42.8472 17.481 43.1384 17.2806 43.459 17.1438C43.7795 17.0071 44.1231 16.9367 44.4701 16.9367C44.8172 16.9367 45.1608 17.0071 45.4813 17.1438C45.8019 17.2806 46.093 17.481 46.3382 17.7336L48.2195 19.6685C49.2528 20.7313 49.2528 22.4482 48.2195 23.511L46.3382 25.4459L40.7208 19.6685ZM38.8396 21.6034L24.7962 36.047V41.8244H30.4135L44.4569 27.3808L38.8396 21.6034Z" fill="#2563EB"/>
                                </svg>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <form action="" class="flex gap-4 justify-end items-center">
            @csrf
            <p class="text-lg">Work Hours: <span id="work-hours">0 Hours</span></p>
            <button class="py-2 px-5 text-white text-lg rounded-md bg-blue-600 hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-colors">Submit</button>
        </form>
    </schedule-section>

    <script type="module">
        let modal = $('.schedule-modal');

        $(document).ready(function() {
            $('.action').click(function() {
                let id = $(this).attr('id');
                modal.toggle();
                $('#day').text(id);
            });

            $('.close-btn').click(function() {
                modal.toggle();
            });

            modal.on('click', function(event) {
                let trigger = $('.modal');
                let targetModal = $(event.target).closest('.modal');

                if(trigger[0] !== targetModal[0]){
                    modal.toggle();
                }
            })
        })
    </script>
@endsection
