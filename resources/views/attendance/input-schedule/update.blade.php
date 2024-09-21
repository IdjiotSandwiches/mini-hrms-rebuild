@extends('attendance.layouts.attendance-layout', with(['title' => 'Input Schedule', 'desc' => 'This is where you upload and view your work schedule.']))
@section('title', 'Attendance - Input Schedule')

@php
    $dayWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
@endphp

@section('content')
    @include('attendance.input-schedule.components.schedule-modal')
    <schedule-section class="py-10 gap-4 flex flex-col dark:text-white">
        <div class="relative overflow-x-auto sm:rounded-lg">
            <table class="w-full text-sm text-center rtl:text-right text-gray-500 dark:text-gray-400">
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
        <p class="text-md font-medium text-center">Work Hours: <span id="work-hours" class="text-red-500">0 Hours</span></p>
        <div @class([
            'justify-between' => $isUpdateSchedule,
            'flex justify-end'
        ])>
            @if ($isUpdateSchedule)
                <a href="{{ route('attendance.input-schedule-page') }}" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-md w-full sm:w-auto px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-blue-800">Cancel</a>
            @endif
            <button type="submit" id="submit" class="disabled:bg-blue-400 disabled:dark:bg-blue-500 disabled:cursor-not-allowed text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-md w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Save Schedule</button>
        </div>
    </schedule-section>
@endsection

@section('extra-js')
    <script>
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
                },
                beforeSend: function() {
                    $('#loading-overlay').show();
                    $('button').prop('disabled', true);
                },
                complete: function() {
                    $('#loading-overlay').hide();
                    $('button').prop('disabled', false);
                },
                success: function(response, textStatus, xhr) {
                    alertSwal.fire({
                        title: response.status,
                        text: response.message,
                        icon: response.status,
                    }).then((result) => {
                        if(result.isConfirmed) {
                            window.location.href = '{{ route('attendance.input-schedule-page') }}';
                        }
                    });
                },
                error: function(xhr, textStatus, errorThrown) {
                    alertSwal.fire({
                        title: response.status,
                        text: response.message,
                        icon: response.status,
                    }).then((result) => {
                        if(result.isConfirmed) {
                            location.reload();
                        }
                    });
                }
            });
        }

        function hideModal() {
            modal.hide();
            $('body').removeClass('overflow-hidden');
        }

        $(document).ready(function() {
            dayjs.extend(dayjs_plugin_customParseFormat);
            $('.action').click(function() {
                id = $(this).closest('tr').attr('id');
                modal.show();
                $('body').addClass('overflow-hidden');
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
                $('input[type="time"]').val('');

                hideModal();
            });

            $('.close-btn').click(function() {
                hideModal();
            });

            $('#submit').click(function() {
                if(totalWorkHours < 20) {
                    alertSwal.fire({
                        title: 'Invalid!',
                        text: 'You must work at least 20 hours a week',
                        icon: 'warning',
                    });
                    return;
                }

                confirmSwal.fire({
                    title: 'Are you sure?',
                    text: 'You cannot change your schedule in 3 months!',
                    icon: 'warning',
                    iconColor: 'red',
                }).then((result) => {
                    if(result.isConfirmed) ajaxRequest();
                });
            });

            modal.on('click', function(event) {
                let trigger = $('.modal');
                let targetModal = $(event.target).closest('.modal');

                if(trigger[0] !== targetModal[0]){
                    hideModal();
                }
            })
        })
    </script>
@endsection
