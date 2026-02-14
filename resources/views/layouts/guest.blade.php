<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-behavior: smooth">

<head>
    @include('layouts._head')
</head>

<body class="bg-gray-50 text-[#111815] font-sans antialiased flex flex-col min-h-screen">
    @unless (request()->routeIs('legal.*'))
        <x-navbar-guest />
    @endunless

    <main class="flex-1">
        {{ $slot }}
    </main>

    <x-footer />
    @livewireScripts
</body>

</html>
