<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($uuid) ? __('Editar Meta') : __('Nova Meta') }}
        </h2>
    </x-slot>

    <livewire:goals.create-edit :uuid="$uuid ?? null" />
</x-app-layout>
