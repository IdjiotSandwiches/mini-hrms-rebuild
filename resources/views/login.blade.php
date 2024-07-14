@extends('layouts.login-register')
@section('title', 'Login')

@section('content')
    <form action="" class="grid gap-5 w-1/3">
        @csrf
        <email-form class="grid border-2 rounded-md px-2 py-1">
            <label for="email" class="text-gray-400 text-xs font-medium">E-mail</label>
            <input type="email" name="email" id="email" class="focus:outline-none text-gray-900 py-1">
        </email-form>
        <password-form class="grid border-2 rounded-md px-2 py-1">
            <label for="password" class="text-gray-400 text-xs font-medium">Password</label>
            <input type="password" name="password" id="password" autocomplete="off" class="focus:outline-none text-gray-900 py-1">
        </password-form>
        <button class="py-2 text-white text-lg rounded-md bg-blue-600 hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-colors">Login</button>
        <p class="text-center text-sm">
            Don't have an account?
            <a href="{{ route('register-page') }}" class="font-semibold leading-6 text-blue-600 transition-colors hover:text-blue-500 underline decoration-2 underline-offset-2">Register</a>
        </p>
    </form>
    <script type="module">
        @if(Session::has('status'))
            toastr.success('{{ Session::get('status') }}');
        @endif
    </script>
@endsection
