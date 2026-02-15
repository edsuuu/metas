<x-guest-layout>
    <livewire:auth.reset-password :token="request()->route('token')" :email="request()->query('email')" />
</x-guest-layout>
