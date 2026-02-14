@props(['href', 'active', 'icon'])

<a href="{{ $href }}"
    {{ $attributes->merge([
        'class' =>
            'flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all group ' .
            ($active
                ? 'bg-primary text-[#111815] shadow-md shadow-primary/20'
                : 'text-gray-600 hover:text-primary hover:bg-primary/5'),
    ]) }}
    wire:navigate>
    <span
        class="material-symbols-outlined transition-colors {{ $active ? 'text-[#111815]' : 'group-hover:text-primary' }}">
        {{ $icon }}
    </span>
    <span class="font-bold text-sm">{{ $slot }}</span>
</a>
