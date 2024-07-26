@props(['name', 'path'])

<a href={{ route($path[0]) }} class="flex justify-center items-center rounded-md px-8 py-2 transition-colors
{{ request()->routeIs($path) ?
    'bg-blue-600 hover:bg-blue-500 text-white'
    :
    'hover:bg-[#F1F5F9] dark:hover:bg-gray-500 hover:text-black dark:hover:text-white'
}}">{{ $name }}</a>
