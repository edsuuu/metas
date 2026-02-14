<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-behavior: smooth">

<head>
    @hasSection('title')
        @include('layouts._head', [
            'title' => trim($__env->yieldContent('title')) . ' - ' . config('app.name', 'Everest'),
        ])
    @else
        @include('layouts._head')
    @endif
</head>

<body class="bg-gray-50 text-[#111815] font-sans antialiased flex flex-col min-h-screen">

    <main class="flex-1 flex flex-col">
        {{ $slot }}
    </main>

    @livewireScripts
</body>

</html>
