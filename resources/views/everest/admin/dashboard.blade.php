<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-gray-400">dashboard</span>
            <h2 class="font-black text-2xl uppercase tracking-tighter">{{ __('Admin Dashboard') }}</h2>
        </div>
    </x-slot>

    <livewire:admin.dashboard />
</x-admin-layout>
