<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Feed da Comunidade') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-5 sm:px-6 lg:px-8">
        <livewire:social.feed />
    </div>
</x-app-layout>
