@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-4 py-2 bg-indigo-50/50 text-indigo-700 rounded-xl text-sm font-bold tracking-tight transition duration-300 ease-in-out'
            : 'inline-flex items-center px-4 py-2 text-sm font-semibold text-slate-500 hover:text-indigo-600 hover:bg-white/50 rounded-xl transition duration-300 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
