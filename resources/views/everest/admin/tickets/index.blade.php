<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-gray-400">confirmation_number</span>
            <h2 class="font-black text-2xl uppercase tracking-tighter">{{ __('Gerenciar Chamados') }}</h2>
        </div>
    </x-slot>

    <livewire:admin.tickets />
</x-admin-layout>
