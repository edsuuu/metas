<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-behavior: smooth">

@include('layouts._head')

<body class="bg-gray-50 text-[#111815] font-sans antialiased">
    <x-navbar-app />
    {{ $slot }}
    <x-toast />
    @livewireScripts
</body>

</html>
