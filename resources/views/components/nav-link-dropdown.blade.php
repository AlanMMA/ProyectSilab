@props(['active'])

@php
$classes = ($active ?? false)
            ? 'flex items-center w-full h-16 px-1 pt-1 cursor-pointer border-b-2 border-blue-tec dark:border-indigo-600 text-sm font-bold leading-5 text-blue-tec dark:text-gray-100 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out'
            : 'flex items-center w-full h-16 px-1 pt-1 cursor-pointer border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-700 focus:outline-none focus:text-gray-700 dark:focus:text-gray-300 focus:border-gray-300 dark:focus:border-gray-700 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>