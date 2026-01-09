@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-3 py-0.5 rounded-md bg-slate-700 dark:bg-slate-600 text-white text-sm font-medium leading-5 hover:bg-slate-600 dark:hover:bg-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-3 py-0.5 rounded-md bg-slate-800 dark:bg-slate-900 text-gray-100 dark:text-gray-200 text-sm font-medium leading-5 hover:bg-slate-700 dark:hover:bg-slate-800 hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
