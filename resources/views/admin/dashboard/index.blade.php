@extends('admin.layout', with(['title' => 'Dashboard', 'desc' => 'Review of registered users.']))
@section('title', 'Admin - Dashboard')

@php
    $colors = ['blue', 'teal', 'orange'];
@endphp

@section('content')
    <dashboard-section class="flex flex-col gap-4 py-10">
        <most-rank class="grid grid-cols-1 xl:grid-cols-2 gap-4 text-center">
            <!-- Current checked in & out -->
            <div class="max-w-screen-3xl w-full bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 p-4 md:p-6">
                <div class="flex justify-between mb-3">
                    <div class="flex justify-center items-center">
                        <h5 class="text-xl font-semibold leading-none text-gray-900 dark:text-white pe-1">Current checked in & out</h5>
                        <svg data-popover-target="user-status-chart-info" data-popover-placement="bottom" class="w-3.5 h-3.5 text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white cursor-pointer ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm0 16a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3Zm1-5.034V12a1 1 0 0 1-2 0v-1.418a1 1 0 0 1 1.038-.999 1.436 1.436 0 0 0 1.488-1.441 1.501 1.501 0 1 0-3-.116.986.986 0 0 1-1.037.961 1 1 0 0 1-.96-1.037A3.5 3.5 0 1 1 11 11.466Z"/>
                        </svg>
                        <div data-popover id="user-status-chart-info" role="tooltip" class="absolute z-10 invisible inline-block text-sm text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 w-72 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                            <div class="p-3 space-y-2">
                                <p>Daily count of user check in & out.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-2" id="check-in-out">
                    <dl class="bg-blue-50 dark:bg-blue-600 rounded-lg flex flex-col items-center justify-center h-24">
                        <dt class="w-8 h-8 rounded-full text-blue-600 dark:text-blue-100 text-xl font-semibold flex items-center justify-center mb-1"></dt>
                        <dd class="text-blue-600 dark:text-blue-100 text-sm font-medium">Checked In</dd>
                    </dl>
                    <dl class="bg-teal-50 dark:bg-teal-400 rounded-lg flex flex-col items-center justify-center h-24">
                        <dt class="w-8 h-8 rounded-full text-teal-600 dark:text-teal-100 text-xl font-semibold flex items-center justify-center mb-1"></dt>
                        <dd class="text-teal-600 dark:text-teal-100 text-sm font-medium">Checked Out</dd>
                    </dl>
                </div>
                <div class="py-6" id="user-status-chart"></div>
            </div>

            <!-- Late, early, absence -->
            <div class="max-w-screen-3xl w-full bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 p-4 md:p-6">
                <div class="flex justify-between mb-3">
                    <div class="flex justify-center items-center">
                        <h5 class="text-xl font-semibold leading-none text-gray-900 dark:text-white pe-1">Late, early, and absence</h5>
                        <svg data-popover-target="user-absence-chart-info" data-popover-placement="bottom" class="w-3.5 h-3.5 text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white cursor-pointer ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm0 16a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3Zm1-5.034V12a1 1 0 0 1-2 0v-1.418a1 1 0 0 1 1.038-.999 1.436 1.436 0 0 0 1.488-1.441 1.501 1.501 0 1 0-3-.116.986.986 0 0 1-1.037.961 1 1 0 0 1-.96-1.037A3.5 3.5 0 1 1 11 11.466Z"/>
                        </svg>
                        <div data-popover id="user-absence-chart-info" role="tooltip" class="absolute z-10 invisible inline-block text-sm text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 w-72 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                            <div class="p-3 space-y-2">
                                <p>Daily count of user late, early, and absence.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-2" id="attendance">
                    <dl class="bg-blue-50 dark:bg-blue-600 rounded-lg flex flex-col items-center justify-center h-24">
                        <dt class="w-8 h-8 rounded-full text-blue-600 dark:text-blue-100 text-xl font-semibold flex items-center justify-center mb-1"></dt>
                        <dd class="text-blue-600 dark:text-blue-100 text-sm font-medium">Attendance</dd>
                    </dl>
                    <dl class="bg-teal-50 dark:bg-teal-400 rounded-lg flex flex-col items-center justify-center h-24">
                        <dt class="w-8 h-8 rounded-full text-teal-600 dark:text-teal-100 text-xl font-semibold flex items-center justify-center mb-1"></dt>
                        <dd class="text-teal-400 dark:text-teal-100 text-sm font-medium">Late</dd>
                    </dl>
                    <dl class="bg-orange-50 dark:bg-orange-400 rounded-lg flex flex-col items-center justify-center h-24">
                        <dt class="w-8 h-8 rounded-full text-orange-600 dark:text-orange-100 text-xl font-semibold flex items-center justify-center mb-1"></dt>
                        <dd class="text-orange-400 dark:text-orange-100 text-sm font-medium">Early</dd>
                    </dl>
                    <dl class="bg-red-50 dark:bg-red-400 rounded-lg flex flex-col items-center justify-center h-24">
                        <dt class="w-8 h-8 rounded-full text-red-600 dark:text-red-100 text-xl font-semibold flex items-center justify-center mb-1"></dt>
                        <dd class="text-red-400 dark:text-red-100 text-sm font-medium">Absence</dd>
                    </dl>
                </div>
                <div class="py-6" id="user-absence-chart"></div>
            </div>
        </most-rank>
        <attendance-frequency class="grid grid-cols-1 gap-4 text-center">
            <div class="max-w-screen-3xl w-full bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 p-4 md:p-6">
                <div class="flex justify-between mb-3">
                    <div class="flex justify-center items-center">
                        <h5 class="text-xl font-semibold leading-none text-gray-900 dark:text-white pe-1">Weekly Attendance</h5>
                        <svg data-popover-target="weekly-attendance-chart-info" data-popover-placement="bottom" class="w-3.5 h-3.5 text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white cursor-pointer ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm0 16a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3Zm1-5.034V12a1 1 0 0 1-2 0v-1.418a1 1 0 0 1 1.038-.999 1.436 1.436 0 0 0 1.488-1.441 1.501 1.501 0 1 0-3-.116.986.986 0 0 1-1.037.961 1 1 0 0 1-.96-1.037A3.5 3.5 0 1 1 11 11.466Z"/>
                        </svg>
                        <div data-popover id="weekly-attendance-chart-info" role="tooltip" class="absolute z-10 invisible inline-block text-sm text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 w-72 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                            <div class="p-3 space-y-2">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="py-6" id="weekly-attendance-chart"></div>
            </div>
        </attendance-frequency>
    </dashboard-section>
@endsection

@section('extra-js')
    <script>
        const daily = @json($daily);
        const weekly = @json($weekly);

        const userStatusChartOptions = {
            series: Object.values(daily.checkInOut),
            colors: ['#1C64F2', '#16BDCA'],
            chart: {
                height: '320px',
                width: '100%',
                type: 'donut',
            },
            stroke: {
                colors: ['transparent'],
                lineCap: '',
            },
            plotOptions: {
                pie: {
                    donut: {
                        labels: {
                            show: true,
                            name: {
                                show: true,
                                offsetY: 20,
                            },
                            total: {
                                showAlways: true,
                                show: true,
                                label: 'User active',
                                formatter: function (w) {
                                    const val = w.globals.seriesTotals;
                                    return val[0] - val[1];
                                },
                            },
                            value: {
                                show: true,
                                offsetY: -20,
                                formatter: function (value) {
                                    return value
                                },
                            },
                        },
                        size: '85%',
                    },
                },
            },
            grid: {
                padding: {
                    top: -2,
                },
            },
            labels: ['Checked In', 'Checked Out'],
            dataLabels: {
                enabled: false,
            },
            legend: {
                position: 'bottom',
            },
            yaxis: {
                labels: {
                    formatter: function (value) {
                        return value
                    },
                },
            },
            xaxis: {
                labels: {
                    formatter: function (value) {
                        return value
                    },
                },
                axisTicks: {
                    show: false,
                },
                axisBorder: {
                    show: false,
                },
            },
        }

        const userAbsenceChartOptions = {
            series: Object.values(daily.attendances),
            colors: ['#1C64F2', '#16BDCA', '#FDBA8C', '#F05252'],
            chart: {
                height: '320px',
                width: '100%',
                type: 'radialBar',
                sparkline: {
                    enabled: true,
                },
            },
            plotOptions: {
                radialBar: {
                    dataLabels: {
                        show: false,
                    },
                    hollow: {
                        margin: 0,
                        size: '40%',
                    }
                },
            },
            grid: {
                show: false,
                strokeDashArray: 4,
                padding: {
                    left: 2,
                    right: 2,
                    top: -23,
                    bottom: -20,
                },
            },
            labels: ['Attendance', 'Late', 'Early', 'Absence'],
            legend: {
                show: true,
                position: 'bottom',
            },
            tooltip: {
                enabled: true,
                x: {
                    show: false,
                },
            },
            yaxis: {
                show: false,
            }
        }

        const attendance = [];
        const late = [];
        const early = [];
        const absence = [];
        Object.entries(weekly).forEach(([key, val]) => {
            attendance.push({x: key, y: val.attendance});
            late.push({x: key, y: val.late});
            early.push({x: key, y: val.early});
            absence.push({x: key, y: val.absence});
        });

        const weeklyAttendanceChartOptions = {
            colors: ['#1C64F2', '#16BDCA'],
            series: [
                {
                    name: 'Attendance',
                    color: '#1C64F2',
                    data: attendance,
                },
                {
                    name: 'Late',
                    color: '#16BDCA',
                    data: late,
                },
                {
                    name: 'Early',
                    color: '#FDBA8C',
                    data: early,
                },
                {
                    name: 'Absence',
                    color: '#F05252',
                    data: absence,
                },
            ],
            chart: {
                type: 'bar',
                height: '320px',
                toolbar: {
                    show: false,
                },
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '70%',
                    borderRadiusApplication: 'end',
                    borderRadius: 8,
                },
            },
            tooltip: {
                shared: true,
                intersect: false,
            },
            states: {
                hover: {
                    filter: {
                        type: 'darken',
                        value: 1,
                    },
                },
            },
            stroke: {
                show: true,
                width: 0,
                colors: ['transparent'],
            },
            grid: {
                show: false,
                strokeDashArray: 4,
                padding: {
                    left: 2,
                    right: 2,
                    top: -14
                },
            },
            dataLabels: {
                enabled: false,
            },
            legend: {
                show: true,
                position: 'bottom',
            },
            xaxis: {
                floating: false,
                labels: {
                    show: true,
                },
                axisBorder: {
                    show: false,
                },
                axisTicks: {
                    show: false,
                },
                categories: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            },
            yaxis: {
                show: true,
            },
            fill: {
                opacity: 1,
            },
        }

        function updateChart(chartContainer, chartOptions, countContainer, countItem) {
            const chart = new ApexCharts(chartContainer, chartOptions);
            chart.render();

            countContainer.each(function (index) {
                let textContainer = $(this).children().first();
                let item = Object.values(countItem)[index];

                textContainer.text(item);
            });
        }

        $(document).ready(function() {
            updateChart($('#user-status-chart')[0], userStatusChartOptions, $('#check-in-out').children(), daily.checkInOut);
            updateChart($('#user-absence-chart')[0], userAbsenceChartOptions, $('#attendance').children(), daily.attendances);

            const weeklyAttendanceChart = new ApexCharts($('#weekly-attendance-chart')[0], weeklyAttendanceChartOptions);
            weeklyAttendanceChart.render();
        });
    </script>
@endsection
