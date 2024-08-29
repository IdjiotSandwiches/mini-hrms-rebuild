@props(['name', 'path'])

<a href="{{ route($path) }}" class="
    py-2 px-4 hover:bg-gray-100 transition-colors text-sm
    dark:hover:bg-gray-600
">
    {{ $name }}
</a>
