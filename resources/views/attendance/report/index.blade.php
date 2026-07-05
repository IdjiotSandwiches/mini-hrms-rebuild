@extends('attendance.attendance-layout', with(['title' => 'Report', 'desc' => 'This is where your weekly and monthly work report will be displayed.']))
@section('title', 'Attendance - Report')

@section('content')
    <report-section class="flex flex-col gap-8 py-10">
        <current-date>
            <h1 class="text-lg font-medium">Current Date</h1>
            <p class="text-gray-500 dark:text-gray-300">Today is <span id="current-day"></span>.
                <span id="current-hours"></span>:<span id="current-minutes"></span>:<span id="current-seconds"></span>
            </p>
        </current-date>
        <custom-report class="gap-4 flex flex-col">
            <div>
                <h1 class="text-lg font-medium">Custom Report</h1>
                <p class="text-gray-500 dark:text-gray-300">Pick your own date.</p>
            </div>
            <div id="date-range-picker" date-rangepicker class="flex flex-col gap-4 items-center sm:flex-row">
                @include('attendance.report.components.time-input', with(['inputType' => 'start']))
                @include('attendance.report.components.time-input', with(['inputType' => 'end']))
                <button id="view-report" class="flex justify-center items-center gap-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-md w-full sm:w-auto px-5 py-2 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50" class="fill-white h-5 w-5">
                        <path d="M 21 3 C 11.601563 3 4 10.601563 4 20 C 4 29.398438 11.601563 37 21 37 C 24.355469 37 27.460938 36.015625 30.09375 34.34375 L 42.375 46.625 L 46.625 42.375 L 34.5 30.28125 C 36.679688 27.421875 38 23.878906 38 20 C 38 10.601563 30.398438 3 21 3 Z M 21 7 C 28.199219 7 34 12.800781 34 20 C 34 27.199219 28.199219 33 21 33 C 13.800781 33 8 27.199219 8 20 C 8 12.800781 13.800781 7 21 7 Z"></path>
                    </svg>
                    View
                </button>
            </div>
            @include('attendance.report.components.report-table', with(['attendances' => $rangedAttendances]))
            <p class="font-medium">Total Work Hours:
                <span @class([
                    'text-blue-500',
                    'text-red-500' => $rangedWorkHours < 20,
                ])>{{ $rangedWorkHours }} Hours</span>
            </p>
        </custom-report>
        <weekly-table class="gap-4 flex flex-col">
            <div>
                <h1 class="text-lg font-medium">Weekly Report</h1>
                <p class="text-gray-500 dark:text-gray-300">This is your work report for the last 7 days.</p>
            </div>
            @include('attendance.report.components.report-table', with(['attendances' => $weeklyAttendances]))
            <p class="font-medium">Total Weekly Work Hours:
                <span @class([
                    'text-blue-500',
                    'text-red-500' => $weeklyWorkHours < 20,
                ])>{{ $weeklyWorkHours }} Hours</span>
            </p>
        </weekly-table>
        <monthly-table class="gap-4 flex flex-col">
            <div>
                <h1 class="text-lg font-medium">Monthly Report</h1>
                <p class="text-gray-500 dark:text-gray-300">This is your work report for the last 30 days.</p>
            </div>
            @include('attendance.report.components.report-table', with(['attendances' => $monthlyAttendances]))
            {{ $monthlyAttendances->links('pagination::tailwind') }}

            <p class="font-medium">Total Monthly Work Hours:
                <span @class([
                    'text-red-500' => $monthlyWorkHours < 80,
                    'text-blue-500',
                ])>{{ $monthlyWorkHours }} Hours</span>
            </p>
        </monthly-table>
    </report-section>
@endsection

@section('extra-js')
    <script>
        $(document).ready(function() {
            $('#view-report').click(function() {
                const start = $('#datepicker-start').val();
                const end = $('#datepicker-end').val();

                if(!start || !end) {
                    Swal.fire({
                        text: 'You need to fill the input!',
                        icon: 'error',
                        confirmButtonColor: 'blue',
                    });
                    return;
                }

                window.location.href = `?start_time=${start}&end_time=${end}`;
            });
        });
    </script>
@endsection
