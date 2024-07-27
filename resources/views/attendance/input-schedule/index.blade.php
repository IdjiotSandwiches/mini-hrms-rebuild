@extends('attendance.layouts.attendance-layout', with(['title' => 'Input Schedule', 'desc' => 'This is where you upload and view your work schedule.']))
@section('title', 'Attendance - Input Schedule')

@php
    $dayWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
@endphp

@section('content')
    <schedule-modal class="
        schedule-modal z-30 fixed hidden bg-black backdrop-blur-sm bg-opacity-30 w-screen h-screen left-0 top-0
        dark:bg-opacity-0
    ">
        <div class="
            flex justify-center items-center h-full px-10
            md:px-28
        ">
            <div class="
                modal grid bg-white px-8 py-6 w-full rounded-md gap-8 select-none
                dark:bg-[#121212] dark:border-2
                xl:w-1/2
            ">
                <header class="flex justify-between items-start">
                    <h1 id="day" class="text-2xl font-medium">Day</h1>
                    <button class="close-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 30 30" class="dark:fill-white">
                            <path d="M 7 4 C 6.744125 4 6.4879687 4.0974687 6.2929688 4.2929688 L 4.2929688 6.2929688 C 3.9019687 6.6839688 3.9019687 7.3170313 4.2929688 7.7070312 L 11.585938 15 L 4.2929688 22.292969 C 3.9019687 22.683969 3.9019687 23.317031 4.2929688 23.707031 L 6.2929688 25.707031 C 6.6839688 26.098031 7.3170313 26.098031 7.7070312 25.707031 L 15 18.414062 L 22.292969 25.707031 C 22.682969 26.098031 23.317031 26.098031 23.707031 25.707031 L 25.707031 23.707031 C 26.098031 23.316031 26.098031 22.682969 25.707031 22.292969 L 18.414062 15 L 25.707031 7.7070312 C 26.098031 7.3170312 26.098031 6.6829688 25.707031 6.2929688 L 23.707031 4.2929688 C 23.316031 3.9019687 22.682969 3.9019687 22.292969 4.2929688 L 15 11.585938 L 7.7070312 4.2929688 C 7.5115312 4.0974687 7.255875 4 7 4 z"></path>
                        </svg>
                    </button>
                </header>
                <div class="grid gap-8">
                    <schedule-form>
                        <div class="
                            flex flex-col
                            sm:grid sm:grid-cols-2 gap-10 justify-between
                        ">
                            <div class="flex flex-col gap-2">
                                <label for="from-hour">From Hour</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                            <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <input type="time" id="from-hour" name="from-hour" step="2" class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
                                </div>
                            </div>
                            <div class="flex flex-col gap-2">
                                <label for="to-hour">To Hour</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                            <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <input type="time" id="to-hour" name="to-hour" step="2" class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
                                </div>
                            </div>
                        </div>
                        <p id="error" class="text-red-500 hidden"></p>
                    </schedule-form>
                    <submit-button class="flex justify-end">
                        <button id="submit-schedule" class="py-1 px-4 text-white text-lg rounded-md bg-blue-600 hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-colors">Confirm</button>
                    </submit-button>
                </div>
            </div>
        </div>
    </schedule-modal>

    <confirm-modal class="
        confirm-modal hidden z-30 fixed bg-black backdrop-blur-sm bg-opacity-30 w-screen h-screen left-0 top-0 text-center
        dark:bg-opacity-0
    ">
        <div class="
            flex justify-center items-center h-full px-10
            md:px-28
        ">
            <div class="
                grid bg-white px-8 py-6 w-full rounded-md gap-8 select-none
                dark:bg-[#121212] dark:border-2
                lg:w-1/2
            ">
                <div class="flex flex-col gap-5 items-center">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="fill-red-500 h-32 w-32">
                        <path d="M13 17C13 17.5523 12.5523 18 12 18C11.4477 18 11 17.5523 11 17C11 16.4477 11.4477 16 12 16C12.5523 16 13 16.4477 13 17Z"/>
                        <path clip-rule="evenodd" d="M12 6C12.5523 6 13 6.44772 13 7V13C13 13.5523 12.5523 14 12 14C11.4477 14 11 13.5523 11 13V7C11 6.44772 11.4477 6 12 6Z" fill-rule="evenodd"/>
                        <path clip-rule="evenodd" d="M12 4C7.58172 4 4 7.58172 4 12C4 16.4183 7.58172 20 12 20C16.4183 20 20 16.4183 20 12C20 7.58172 16.4183 4 12 4ZM2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12Z" fill-rule="evenodd"/>
                    </svg>
                    <h1 class="text-3xl font-semibold">Are you sure?</h1>
                    <p>You cannot change your schedule in 3 months!</p>
                    <form class="flex justify-center gap-5">
                        @csrf
                        <button id="confirm-schedule" class="py-2 px-8 text-white text-lg rounded-md bg-blue-600 hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-colors">Yes</button>
                        <button id="cancel" class="py-2 px-8 text-white text-lg rounded-md bg-red-600 hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600 transition-colors">No</button>
                    </form>
                </div>
            </div>
        </div>
    </confirm-modal>

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
                        @if (!$isScheduleSubmitted)
                            <td class="px-4 py-3">Action</td>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @if ($isScheduleSubmitted)
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
                    @else
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
                    @endif
                </tbody>
            </table>
        </div>
        <p id="submit-error" class="text-red-500 text-center hidden"></p>
        <div class="flex gap-4 justify-end items-center">
            @if ($isScheduleSubmitted)
                <p class="text-lg font-medium">Work Hours: <span id="work-hours" class="text-blue-500">{{ str($totalWorkHour) . ' Hours' }}</span></p>
            @else
                <p class="text-lg font-medium">Work Hours: <span id="work-hours" class="text-red-500">0 Hours</span></p>
                <button class="py-2 px-5 text-white text-lg rounded-md bg-blue-600 hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-colors" id="submit">Save Schedule</button>
            @endif
        </div>
    </schedule-section>

    <script type="module">
        @if (Session::has('status'))
            toastr.{{ Session::get('status') }}('{{ Session::get('message') }}');
        @endif

        let modal = $('.schedule-modal');
        let totalWorkHours = 0;
        let id = null;
        let schedule = {
            Monday: {
                start: '00:00:00',
                end: '00:00:00',
                workTime: 0,
            },
            Tuesday: {
                start: '00:00:00',
                end: '00:00:00',
                workTime: 0,
            },
            Wednesday: {
                start: '00:00:00',
                end: '00:00:00',
                workTime: 0,
            },
            Thursday: {
                start: '00:00:00',
                end: '00:00:00',
                workTime: 0,
            },
            Friday: {
                start: '00:00:00',
                end: '00:00:00',
                workTime: 0,
            },
            Saturday: {
                start: '00:00:00',
                end: '00:00:00',
                workTime: 0,
            },
            Sunday: {
                start: '00:00:00',
                end: '00:00:00',
                workTime: 0,
            },
        }

        function hourInputError(text){
            $('#error').on('error', function() {
                $(this).text(text);
                $(this).removeClass('hidden');
                $('.time-input').addClass('border-red-500');
            })
            $('#error').trigger('error');
        }

        function ajaxRequest() {
            const url = "{{ route('attendance.input-schedule') }}";
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    schedule: schedule,
                    _token: '{{ csrf_token() }}'
                }
            });
        }

        $(document).ready(function() {
            $('.action').click(function() {
                id = $(this).closest('tr').attr('id');
                modal.toggle();
                $('#day').text(id);
            });

            $('#submit-schedule').click(function() {
                let fromHour = $('#from-hour').val();
                let toHour = $('#to-hour').val();
                let start = dayjs(fromHour, 'HH:mm:ss');
                let end = dayjs(toHour, 'HH:mm:ss');
                let breakStart = dayjs('12:00:00', 'HH:mm:ss');
                let breakEnd = dayjs('13:00:00', 'HH:mm:ss');

                if(!fromHour || !toHour) {
                    hourInputError('You must input the schedule.');
                    return;
                }

                if(end.diff(start) <= 0) {
                    hourInputError('End time must be greater than start time.');
                    return;
                }

                if(end.diff(start, 'hour') < 1 && end.diff(start, 'hour') >= 0) {
                    hourInputError('Work time must at least 1 hour.');
                    return;
                }

                if(start.isBefore(dayjs('08:00:00', 'HH:mm:ss')) || end.isAfter(dayjs('19:00:00', 'HH:mm:ss'))) {
                    hourInputError('Invalid work hours.');
                    return;
                }

                let workTime = 0;
                if(start.isBefore(breakEnd) && end.isAfter(breakStart)) {
                    let overtimeStart = start.isBefore(breakStart) ? breakStart : start;
                    let overtimeEnd = end.isAfter(breakEnd) ? breakEnd : end;
                    workTime = (end.diff(start) - overtimeEnd.diff(overtimeStart)) / 1000;
                }
                else {
                    workTime = end.diff(start) / 1000;
                }

                let hour = Math.floor(workTime / 3600);
                let minute = Math.floor((workTime % 3600) / 60);
                let second = Math.round(workTime % 60);

                $(`#${id} > td:nth-child(2)`).text(`${fromHour} - ${toHour} (${hour}hr ${minute}m ${second}s)`);

                schedule[id].start = fromHour;
                schedule[id].end = toHour;
                schedule[id].workTime =workTime;

                totalWorkHours = 0;
                for(const [key, value] of Object.entries(schedule)) {
                    totalWorkHours += value.workTime;
                }

                totalWorkHours = Math.floor(totalWorkHours / 3600);
                $('#work-hours').text(`${totalWorkHours} Hours`);
                totalWorkHours >= 20 ? $('#work-hours').removeClass('text-red-500') : '';
                totalWorkHours >= 20 ? $('#work-hours').addClass('text-blue-500') : '';

                $('#error').addClass('hidden');
                $('.time-input').val('');

                modal.toggle();
            });

            $('.close-btn').click(function() {
                modal.toggle();
            });

            $('#submit').click(function(e) {
                // if(totalWorkHours < 20) {
                //     $('#submit-error').text('You must work at least 20 hours a week');
                //     $('#submit-error').removeClass('hidden');
                //     e.preventDefault();
                //     return;
                // }

                $('#submit-error').addClass('hidden');
                $('.confirm-modal').toggle();
            });

            $('#cancel').click(function() {
                $('.confirm-modal').toggle()
            });

            $('#confirm-schedule').click(function() {
                ajaxRequest();
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
