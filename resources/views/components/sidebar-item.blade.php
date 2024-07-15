@props(['item_title', 'path'])

<a href="{{ route($path) }}" class="w-full h-fit px-2 py-2 font-medium rounded-md transition-colors
{{ request()->routeIs($path) ?
    'bg-[#F1F5F9]'
    :
    'hover:bg-[#F1F5F9] hover:text-black'
}}
">{{ $item_title }}</a>
