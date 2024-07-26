<!DOCTYPE html>
<html lang="en" class="dark">
@include('layouts.head')
<body>
    @include('components.navbar')
    <section class="
        min-h-screen py-32 flex justify-center items-center
        dark:bg-gray-900 dark:text-white
    ">
        @yield('content')
    </section>
</body>
</html>
