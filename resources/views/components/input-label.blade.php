@props(['value'])

<label
    {{ $attributes->merge(['class' => 'block font-extrabold text-xs text-gray-400 uppercase tracking-widest mb-1']) }}>
    {{ $value ?? $slot }}
</label>
