@props(['title', 'desc'])

<!DOCTYPE html>
<html lang="en">
@include('layouts.head')
<body>
    @include('components.navbar')
    <section class="px-28 py-6 pt-20 min-h-screen">
        <!-- Title -->
        <div class="border-gray-200 border-b-2 py-5">
            <h1 class="text-2xl font-semibold">Profile</h1>
            <p>Manage your account settings here.</p>
        </div>
        <content-section class="grid grid-cols-[20%_minmax(20%,_1fr)] gap-10 py-10">
            <!-- Sidebar -->
            <aside class="grid gap-2 h-fit">
                @include('components.sidebar-item', with(['item_title' => 'Edit Profile']))
                @include('components.sidebar-item', with(['item_title' => 'Change Password']))
            </aside>
            <section>
                <div class="border-gray-200 border-b-2 pb-5">
                    <h2 class="text-xl font-semibold">{{ $title }}</h2>
                    <p>{{ $desc }}</p>
                </div>
                @yield('content')
            </section>
        </content-section>
    </section>
</body>
</html>
