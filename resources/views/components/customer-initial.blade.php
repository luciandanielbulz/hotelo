@props([
    'name' => '',
    'size' => 'md', // sm, md, lg
    'class' => ''
])

@php
    $sizes = [
        'sm' => ['container' => 'w-8 h-8 text-xs', 'span' => 'leading-[2rem]'],
        'md' => ['container' => 'w-10 h-10 text-sm', 'span' => 'leading-[2.5rem]'],
        'lg' => ['container' => 'w-12 h-12 text-base', 'span' => 'leading-[3rem]'],
    ];
    $sizeConfig = $sizes[$size] ?? $sizes['md'];
    
    $initial = strtoupper(substr($name ?: 'K', 0, 1));
@endphp

<div class="{{ $sizeConfig['container'] }} bg-blue-500 rounded-lg flex items-center justify-center text-white font-bold shadow-lg flex-shrink-0 {{ $class }}">
    <span class="block {{ $sizeConfig['span'] }} text-center w-full">{{ $initial }}</span>
</div>

