<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Social - Expandir Círculo') }}
        </h2>
    </x-slot>

    <livewire:social.discovery :tab="request('tab')" :search="request('search')" />
</x-app-layout>
