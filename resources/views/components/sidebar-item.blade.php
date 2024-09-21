@props(['item_title', 'path', 'parameter' => null])

<a href="{{ route($path[0], $parameter) }}" @class([
    'w-full h-fit px-2 py-2 font-medium rounded-md transition-colors dark:text-white hover:bg-[#F1F5F9] dark:hover:bg-gray-600',
    'bg-[#F1F5F9] dark:bg-gray-600' => request()->routeIs($path),
])>{{ $item_title }}</a>
