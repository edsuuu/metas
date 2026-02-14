<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Feed da Comunidade') }}
        </h2>
    </x-slot>

    <livewire:social.feed />
</x-app-layout>
