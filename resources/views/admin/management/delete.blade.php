@extends('admin.management.layout.edit-layout', with(['title' => 'Delete User', 'desc' => 'Remove user from database.']))
@section('title', 'Admin - Users Management')

@section('content')
    <delete-section class="flex flex-col gap-4 py-10">
        <form action="{{ route('admin.management.delete', $user->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="mb-5">
                <label for="password" class="flex items-center mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Password
                    <svg data-popover-target="user-status-chart-info" data-popover-placement="bottom" class="w-3.5 h-3.5 text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white cursor-pointer ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm0 16a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3Zm1-5.034V12a1 1 0 0 1-2 0v-1.418a1 1 0 0 1 1.038-.999 1.436 1.436 0 0 0 1.488-1.441 1.501 1.501 0 1 0-3-.116.986.986 0 0 1-1.037.961 1 1 0 0 1-.96-1.037A3.5 3.5 0 1 1 11 11.466Z"/>
                    </svg>
                    <div data-popover id="user-status-chart-info" role="tooltip" class="absolute z-10 invisible inline-block text-sm text-center text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 w-72 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                        <div class="p-3 space-y-2">
                            <p>Current admin password.</p>
                        </div>
                    </div>
                </label>
                <input type="password" id="password" name="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
            </div>
            <button class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-blue-800">Delete</button>
        </form>
    </delete-section>
@endsection

@section('extra-js')
    <script>
        function ajaxRequest() {
            const url = '{{ route('admin.management.delete', $user->id) }}';
            $.ajax({
                type: 'DELETE',
                url: url,
                data: {
                    password: password,
                },
                success: function(response, textStatus, xhr) {

                },
                error: function(xhr, textStatus, errorThrown) {

                }
            });
        }

        $(document).ready(function () {

        });
    </script>
@endsection
