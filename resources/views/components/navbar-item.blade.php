@props(['name', 'path'])

<a href={{ route($path) }} @class([
    'flex justify-center items-center rounded-md px-8 py-2 transition-colors hover:bg-[#F1F5F9] dark:hover:bg-gray-500 hover:text-black dark:hover:text-white',
    'bg-blue-600 hover:bg-blue-500 hover:text-white text-white' => request()->routeIs($path),
])>{{ $name }}</a>
