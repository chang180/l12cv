@props([
    'variant' => 'primary',
    'icon' => null,
    'href' => null,
])

@php
    $baseClasses = 'inline-flex items-center px-4 py-2 border rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150';

    $variantClasses = [
        'primary' => 'border-transparent text-white bg-primary-600 hover:bg-primary-500 active:bg-primary-700 focus:ring-primary-500',
        'secondary' => 'border-transparent text-white bg-gray-600 hover:bg-gray-500 active:bg-gray-700 focus:ring-gray-500',
    ][$variant];

    $tag = $href ? 'a' : 'button';
@endphp

<{{ $tag }}
    {{ $attributes->merge(['class' => $baseClasses . ' ' . $variantClasses]) }}
    @if($href) href="{{ $href }}" @endif
>
    @if($icon)
        <x-icon :name="$icon" class="w-4 h-4 mr-2" />
    @endif
    {{ $slot }}
</{{ $tag }}>
