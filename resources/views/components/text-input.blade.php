@props(['disabled' => false])

@php
    $name = $attributes->get('name') ?? $attributes->whereStartsWith('wire:model')->first();
    $hasError = $name && $errors->has($name);

    $classes =
        'w-full h-12 px-4 rounded-2xl bg-gray-50 border transition-all text-sm focus:ring-1! focus:ring-primary! focus:border-primary! outline-hidden! ';

    if ($hasError) {
        $classes .= 'border-red-500 bg-red-50/50 focus:border-red-500!';
    } else {
        $classes .= 'border-gray-100';
    }
@endphp

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => $classes]) !!}>
