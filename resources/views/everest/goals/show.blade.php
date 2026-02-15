<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalhes da Meta') }}
        </h2>
    </x-slot>

    <livewire:goals.show :uuid="request()->route('uuid')" />
</x-app-layout>
