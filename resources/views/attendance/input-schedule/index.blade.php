@extends('attendance.layouts.attendance-layout', with(['title' => 'Input Schedule', 'desc' => 'This is where you upload and view your work schedule.']))
@section('title', 'Attendance - Input Schedule')

@section('content')
    <schedule-section class="
        py-10 gap-4 flex flex-col
        dark:text-white
    ">
    <div class="relative overflow-x-auto sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Day</th>
                        <th scope="col" class="px-6 py-3">Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($schedule as $eachSchedule)
                        <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $eachSchedule->day }}</th>
                            @if ($eachSchedule->start_time == '00:00:00' && $eachSchedule->end_time == '00:00:00')
                                <td class="px-6 py-4">No work schedule.</td>
                            @else
                                <td class="px-6 py-4">{{ "$eachSchedule->start_time - $eachSchedule->end_time" }}</td>
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
@endsection

@section('extra-js')
    @include('attendance.input-schedule.components.common-js')
@endsection
