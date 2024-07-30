@extends('attendance.layouts.attendance-layout', with(['title' => 'Input Schedule', 'desc' => 'This is where you upload and view your work schedule.']))
@section('title', 'Attendance - Input Schedule')

@section('content')
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
                    </tr>
                </thead>
                <tbody>
                    @foreach ($schedule as $eachSchedule)
                        <tr class="border-b-2 border-gray-200 dark:text-white">
                            <td class="px-4 py-3 dark:bg-gray-700">{{ $eachSchedule->day }}</td>
                            @if ($eachSchedule->start_time == '00:00:00' && $eachSchedule->end_time == '00:00:00')
                                <td class="px-4 py-3 bg-gray-100 dark:bg-gray-800">No work schedule</td>
                            @else
                                <td class="px-4 py-3 bg-gray-100 dark:bg-gray-800">{{ str($eachSchedule->start_time) . ' - ' . str($eachSchedule->end_time) }}</td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="flex gap-4 justify-end items-center">
            <p class="text-lg font-medium">Work Hours: <span id="work-hours" class="text-blue-500">{{ str($totalWorkHour) . ' Hours' }}</span></p>
            @if ($isUpdateSchedule)
                <a href="{{ route('attendance.update-schedule-page') }}" class="py-2 px-5 text-white text-lg rounded-md bg-blue-600 hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-colors">Change Schedule</a>
            @endif
        </div>
    </schedule-section>

    @include('attendance.input-schedule.components.common-js')
@endsection
