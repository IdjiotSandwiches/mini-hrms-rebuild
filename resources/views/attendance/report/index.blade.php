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
                    flex justify-center items-center gap-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-md w-full sm:w-auto px-5 py-2 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800
                ">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50" class="fill-white h-5 w-5">
                        <path d="M 21 3 C 11.601563 3 4 10.601563 4 20 C 4 29.398438 11.601563 37 21 37 C 24.355469 37 27.460938 36.015625 30.09375 34.34375 L 42.375 46.625 L 46.625 42.375 L 34.5 30.28125 C 36.679688 27.421875 38 23.878906 38 20 C 38 10.601563 30.398438 3 21 3 Z M 21 7 C 28.199219 7 34 12.800781 34 20 C 34 27.199219 28.199219 33 21 33 C 13.800781 33 8 27.199219 8 20 C 8 12.800781 13.800781 7 21 7 Z"></path>
                    </svg>
                    View
                </button>
            </div>
            <div class="relative overflow-x-auto sm:rounded-lg" id="custom-report">
                <table class="w-full text-sm text-center rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">No.</th>
                            <th scope="col" class="px-6 py-3">Date</th>
                            <th scope="col" class="px-6 py-3">Check In Time</th>
                            <th scope="col" class="px-6 py-3">Check Out Time</th>
                            <th scope="col" class="px-6 py-3">Early</th>
                            <th scope="col" class="px-6 py-3">Late</th>
                            <th scope="col" class="px-6 py-3">Absence</th>
                            <th scope="col" class="px-6 py-3">Work Duration</th>
                        </tr>
                    </thead>
                        <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                            <td colspan="8" class="px-6 py-3 text-center">
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
                            <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                                <td colspan="8" class="px-6 py-3 text-center">
                                    You do not have work attendance
                                </td>
                            </tr>
                        `;
                        table.append(row);
                    }
                    else {
                        $.each(res, function(index, report) {
                            let row = `
                                <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">${index + 1}</th>
                                    <td class="px-6 py-4">${report.date}</td>
                                    <td class="px-6 py-4">${report.checkIn}</td>
                                    <td class="px-6 py-4">${report.checkOut}</td>
                                    <td class="px-6 py-4">${report.early}</td>
                                    <td class="px-6 py-4">${report.late}</td>
                                    <td class="px-6 py-4">${report.absence}</td>
                                    <td class="px-6 py-4">${report.workTime}</td>
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
