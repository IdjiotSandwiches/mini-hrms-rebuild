<!DOCTYPE html>
<html lang="en" class="">
@include('layouts.head')
<body>
    @include('components.navbar')
    @include('components.toggle-darkmode')
    <section class="
        min-h-screen py-32 flex justify-center items-center
        dark:bg-[#121212] dark:text-white
    ">
        @yield('content')
    </section>

    @include('components.cdn')
    @include('components.common-js')
</body>
</html>
