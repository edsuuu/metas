<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-gray-400">notifications</span>
            <h2 class="font-black text-2xl uppercase tracking-tighter">{{ __('Teste de Notificações') }}</h2>
        </div>
    </x-slot>

    <livewire:admin.notifications />
</x-admin-layout>
