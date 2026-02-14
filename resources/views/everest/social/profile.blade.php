<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Social - Perfil de ' . ($profileUser->name ?? 'Usuário')) }}
        </h2>
    </x-slot>

    <livewire:social.profile :identifier="request('identifier')" />
</x-app-layout>
