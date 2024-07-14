@props(['name', 'route', 'path'])

<a href={{ $route }} class="flex justify-center items-center rounded-md px-8 py-2 transition-colors {{ Request::is($path) ? 'bg-blue-600 hover:bg-blue-500 text-white' : 'hover:bg-[#F1F5F9] hover:text-black' }}">{{ $name }}</a>
