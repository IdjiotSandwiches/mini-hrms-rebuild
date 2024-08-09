@extends('attendance.layouts.attendance-layout', with(['title' => 'Report', 'desc' => 'This is where your weekly and monthly work report will be displayed.']))
@section('title', 'Attendance - Report')

@section('content')
    <report-section class="flex flex-col gap-8 py-10">
        <current-date>
            <h1 class="text-lg font-medium">Current Date</h1>
            <p class="
                text-gray-500
                dark:text-gray-300
            ">Today is <span id="current-day"></span>.
                <span id="current-hours"></span>:<span id="current-minutes"></span>:<span id="current-seconds"></span>
            </p>
        </current-date>
        <custom-report class="gap-4 flex flex-col">
            <div>
                <h1 class="text-lg font-medium">Custom Report</h1>
                <p class="
                    text-gray-500
                    dark:text-gray-300
                ">Pick your own date.</p>
            </div>
            <div id="date-range-picker" date-rangepicker class="
                flex flex-col gap-4 items-center
                sm:flex-row
            ">
                <div class="
                    relative w-full
                    sm:w-auto
                ">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                        </svg>
                    </div>
                    <input id="datepicker-start" name="start" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Select date start">
                </div>
                <div class="
                    relative w-full
                    sm:w-auto
                ">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                        </svg>
                    </div>
                    <input id="datepicker-end" name="end" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Select date end">
                </div>
                <button id="view-report" class="
                    flex w-full justify-center items-center gap-2 py-1.5 px-4 text-white text-lg rounded-md bg-blue-600 hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-colors
                    sm:w-auto
                ">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50" class="fill-white h-5 w-5">
                        <path d="M 21 3 C 11.601563 3 4 10.601563 4 20 C 4 29.398438 11.601563 37 21 37 C 24.355469 37 27.460938 36.015625 30.09375 34.34375 L 42.375 46.625 L 46.625 42.375 L 34.5 30.28125 C 36.679688 27.421875 38 23.878906 38 20 C 38 10.601563 30.398438 3 21 3 Z M 21 7 C 28.199219 7 34 12.800781 34 20 C 34 27.199219 28.199219 33 21 33 C 13.800781 33 8 27.199219 8 20 C 8 12.800781 13.800781 7 21 7 Z"></path>
                    </svg>
                    View
                </button>
            </div>
            <div class="w-full relative overflow-x-auto rounded-md" id="custom-report">
                <table class="w-full table-auto text-center text-gray-500 dark:text-white">
                    <thead class="bg-blue-500 text-white">
                        <tr class="font-semibold">
                            <td class="px-4 py-3">No.</td>
                            <td class="px-4 py-3 bg-blue-600">Date</td>
                            <td class="px-4 py-3">Check In Time</td>
                            <td class="px-4 py-3 bg-blue-600">Check Out Time</td>
                            <td class="px-4 py-3">Early</td>
                            <td class="px-4 py-3 bg-blue-600">Late</td>
                            <td class="px-4 py-3">Absence</td>
                            <td class="px-4 py-3 bg-blue-600">Work Duration</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b-2 border-gray-200">
                            <td colspan="8" class="px-4 py-3">
                                You do not have work attendance
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </custom-report>
        <weekly-table class="gap-4 flex flex-col">
            <div>
                <h1 class="text-lg font-medium">Weekly Report</h1>
                <p class="
                    text-gray-500
                    dark:text-gray-300
                ">This is your work report for the last 7 days.</p>
            </div>
            @include('attendance.report.components.report-table', with(['attendances' => $weeklyAttendances]))
            <p class="font-medium">Total Weekly Work Hours:
                <span @class([
                    'text-blue-500',
                    'text-red-500' => $weeklyWorkHours < 20
                ])>{{ $weeklyWorkHours }} Hours</span>
            </p>
        </weekly-table>
        <monthly-table class="gap-4 flex flex-col">
            <div>
                <h1 class="text-lg font-medium">Monthly Report</h1>
                <p class="
                    text-gray-500
                    dark:text-gray-300
                ">This is your work report for the last 30 days.</p>
            </div>
            @include('attendance.report.components.report-table', with(['attendances' => $monthlyAttendances]))
            <p class="font-medium">Total Monthly Work Hours:
                <span @class([
                    'text-red-500' => $monthlyWorkHours < 80,
                    'text-blue-500'
                ])>{{ $monthlyWorkHours }} Hours</span>
            </p>
            {{ $monthlyAttendances->links('pagination::tailwind') }}
        </monthly-table>
    </report-section>
@endsection

@section('extra-js')
    <script>
        function ajaxRequest(start, end) {
            const url = '{{ route('attendance.get-range-report') }}';
            $.ajax({
                type: 'GET',
                url: url,
                data: {
                    start_time: start,
                    end_time: end,
                },
                success: function(res) {
                    const table = $('#custom-report').find('tbody');
                    table.children().remove();
                    if(res.length === 0) {
                        let row = `
                            <tr class="border-b-2 border-gray-200">
                                <td colspan="8" class="px-4 py-3">
                                    You do not have work attendance
                                </td>
                            </tr>
                        `;
                        table.append(row);
                    }
                    else {
                        $.each(res, function(index, report) {
                            let row = `
                                <tr class="border-b-2 border-gray-200">
                                    <td class="px-4 py-3 dark:bg-gray-700">${index + 1}</td>
                                    <td class="px-4 py-3 bg-gray-100 dark:bg-gray-800">${report.date}</td>
                                    <td class="px-4 py-3 dark:bg-gray-700">${report.checkIn}</td>
                                    <td class="px-4 py-3 bg-gray-100 dark:bg-gray-800">${report.checkOut}</td>
                                    <td class="px-4 py-3 dark:bg-gray-700">${report.early}</td>
                                    <td class="px-4 py-3 bg-gray-100 dark:bg-gray-800">${report.late}</td>
                                    <td class="px-4 py-3 dark:bg-gray-700">${report.absence}</td>
                                    <td class="px-4 py-3 bg-gray-100 dark:bg-gray-800">${report.workTime}</td>
                                </tr>
                            `;
                            table.append(row);
                        });
                    }
                },
                error: function(res) {
                    Swal.fire({
                        text: 'Invalid operation.',
                        icon: 'error',
                        confirmButtonColor: 'blue',
                    });
                }
            });
        }

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

                ajaxRequest(start, end);
            });
        });
    </script>
@endsection
