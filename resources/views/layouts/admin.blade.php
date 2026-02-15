<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-behavior: smooth">

@include('layouts._head', ['title' => config('app.name', 'Laravel') . ' - Admin'])

<body class="bg-gray-50 text-[#111815] font-sans antialiased min-h-screen" x-data="{ sidebarOpen: false }">
    {{-- Mobile Overlay --}}
    <div x-show="sidebarOpen" x-on:click="sidebarOpen = false"
        class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 lg:hidden transition-opacity"
        x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    </div>

    <x-admin-sidebar />

    <div class="lg:ml-64 flex flex-col h-screen overflow-hidden">
        {{-- Mobile Header --}}
        <header
            class="lg:hidden flex items-center justify-between p-4 bg-white border-b border-[#dbe6e1] sticky top-0 z-30">
            <div class="flex items-center gap-3">
                <div class="size-8 text-primary">
                    <svg fill="currentColor" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                        <path d="M24 4H42V17.3333V30.6667H24V44H6V30.6667V17.3333H24V4Z"></path>
                    </svg>
                </div>
                <span class="text-xl font-bold tracking-tight">Admin</span>
            </div>
            <button x-on:click="sidebarOpen = true" class="p-2 text-gray-600">
                <span class="material-symbols-outlined">menu</span>
            </button>
        </header>

        <main class="p-4 md:p-8 flex-1 w-full max-w-full overflow-y-auto custom-scrollbar">
            @if (isset($header))
                <header class="mb-8">
                    {{ $header }}
                </header>
            @endif

            {{ $slot }}
        </main>
    </div>

    <x-toast />
    @livewireScripts
    @stack('scripts')
</body>

</html>
