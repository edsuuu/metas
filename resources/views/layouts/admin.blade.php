<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-behavior: smooth">

<head>
    @include('layouts._head', ['title' => config('app.name', 'Laravel') . ' - Admin'])
</head>

<body class="bg-gray-50 text-[#111815] font-sans antialiased min-h-screen">
    <div class="flex">
        {{-- Admin Sidebar --}}
        <x-admin-sidebar />

        <div class="flex-1 min-h-screen">
            <main class="p-4 md:p-8">
                @if (isset($header))
                    <header class="mb-8">
                        {{ $header }}
                    </header>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>

    <x-toast />
    @livewireScripts
    @stack('scripts')
</body>

</html>
