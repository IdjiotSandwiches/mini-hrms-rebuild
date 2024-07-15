@extends('attendance.layouts.attendance-layout', with(['title' => 'Take Attendance', 'desc' => 'This is where you verify your attendance.']))
@section('title', 'Attendance - Take Attendance')

@section('content')

    <script type="module">
        @if (Session::has('status'))
            toastr.{{ Session::get('status') }}('{{ Session::get('message') }}')
        @endif
    </script>
@endsection
