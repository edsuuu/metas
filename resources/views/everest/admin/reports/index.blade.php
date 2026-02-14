<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-gray-400">report</span>
            <h2 class="font-black text-2xl uppercase tracking-tighter">{{ __('Denúncias') }}</h2>
        </div>
    </x-slot>

    <livewire:admin.reports />
</x-admin-layout>
