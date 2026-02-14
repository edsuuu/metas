<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-gray-400">group</span>
            <h2 class="font-black text-2xl uppercase tracking-tighter">{{ __('Gerenciar Usuários') }}</h2>
        </div>
    </x-slot>

    <livewire:admin.users />
</x-admin-layout>
