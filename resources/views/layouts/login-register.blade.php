<!DOCTYPE html>
<html lang="en">
@include('layouts.head')
<body>
    @include('components.navbar')
    <section class="min-h-screen py-32 flex justify-center items-center">
        @yield('content')
    </section>
</body>
</html>
