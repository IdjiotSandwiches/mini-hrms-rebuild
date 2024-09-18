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
            },
            complete: function() {
                $('#loading-overlay').hide();
            },
            success: function(res) {
                alertSwal.fire({
                    title: res.status,
                    text: res.message,
                    icon: res.status,
                }).then((result) => {
                    if(result.isConfirmed) {
                        window.location.href = '{{ route('attendance.input-schedule-page') }}';
                    }
                });
            },
            error: function(res) {
                alertSwal.fire({
                    title: res.status,
                    text: res.message,
                    icon: res.status,
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
