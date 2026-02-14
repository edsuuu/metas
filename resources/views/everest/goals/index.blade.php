<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Minhas Metas') }}
        </h2>
    </x-slot>

    <livewire:goals.index />
</x-app-layout>
