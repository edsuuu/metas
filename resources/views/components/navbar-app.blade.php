<header x-data="{ isMenuOpen: false }"
    class="sticky top-0 z-50 w-full border-b border-solid border-[#dbe6e1] bg-white/80 backdrop-blur-md px-4 md:px-20 lg:px-40 py-3">
    <div class="max-w-[1200px] mx-auto flex items-center justify-between">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 hover:opacity-80 transition-opacity"
            wire:navigate>
            <div class="size-8 text-primary">
                <svg fill="currentColor" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                    <path clipRule="evenodd" d="M24 4H42V17.3333V30.6667H24V44H6V30.6667V17.3333H24V4Z"
                        fillRule="evenodd"></path>
                </svg>
            </div>
            <h2 class="text-[#111815] text-xl font-bold leading-tight tracking-tight">
                Everest
            </h2>
        </a>

        <!-- Desktop Navigation -->
        <nav class="hidden md:flex items-center gap-9">
            <a class="text-[#111815] text-sm font-bold border-b-2 transition-colors {{ request()->routeIs('dashboard') ? 'text-primary border-primary' : 'border-transparent hover:text-primary' }}"
                href="{{ route('dashboard') }}" wire:navigate>
                Dashboard
            </a>
            <a class="text-[#111815] text-sm font-bold border-b-2 transition-colors {{ request()->routeIs('goals.*') ? 'text-primary border-primary' : 'border-transparent hover:text-primary' }}"
                href="{{ route('goals.index') }}" wire:navigate>
                Metas
            </a>
            <a class="text-[#111815] text-sm font-bold border-b-2 transition-colors {{ request()->routeIs('social.feed') ? 'text-primary border-primary' : 'border-transparent hover:text-primary' }}"
                href="{{ route('social.feed') }}" wire:navigate>
                Social
            </a>
            <a class="text-[#111815] text-sm font-bold border-b-2 transition-colors {{ request()->routeIs('social.index') ? 'text-primary border-primary' : 'border-transparent hover:text-primary' }}"
                href="{{ route('social.index') }}" wire:navigate>
                Explorar
            </a>
        </nav>

        <!-- Desktop Actions -->
        <div class="hidden md:flex items-center gap-3">
            @php
                $streakCount = auth()->user()->streak ?? 0;
                $hasStreak = $streakCount > 0;
            @endphp

            {{-- Streak Badge --}}
            <div
                class="flex items-center gap-2 px-3 py-1.5 rounded-full border transition-colors {{ $hasStreak ? 'bg-orange-50 border-orange-100' : 'bg-gray-100 border-gray-200' }}">
                <span
                    class="material-symbols-outlined text-xl leading-none {{ $hasStreak ? 'text-orange-500' : 'text-gray-400' }}"
                    style="font-variation-settings: 'FILL' 1">
                    local_fire_department
                </span>
                <span class="font-extrabold text-sm {{ $hasStreak ? 'text-orange-600' : 'text-gray-500' }}">
                    {{ $streakCount }} {{ $streakCount <= 1 ? 'Dia' : 'Dias' }}
                </span>
            </div>

            {{-- Profile Dropdown --}}
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open"
                    class="flex size-10 rounded-full bg-gray-200 border-2 border-primary overflow-hidden cursor-pointer focus:outline-none">
                    @if (auth()->user()->avatar_url)
                        <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}"
                            class="w-full h-full object-cover">
                    @else
                        <div
                            class="w-full h-full flex items-center justify-center bg-primary text-[#111815] font-bold text-lg">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    @endif
                </button>

                <div x-show="open" @click.away="open = false"
                    class="absolute right-0 mt-2 w-48 bg-white rounded-2xl shadow-xl border border-[#dbe6e1] py-2 z-50"
                    x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100" style="display: none;">
                    <div class="px-4 py-3 border-b border-gray-100 mb-1">
                        <p class="text-sm font-bold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                    </div>
                    <a href="{{ route('social.profile', auth()->user()->nickname ?: auth()->id()) }}"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50" wire:navigate>Perfil</a>
                    @hasanyrole('Administrador|Suporte')
                        <a href="{{ route('admin.dashboard') }}"
                            class="block px-4 py-2 text-sm text-primary font-bold hover:bg-primary/5 transition-colors"
                            wire:navigate>Painel Admin</a>
                    @endhasanyrole
                    <div class="border-t border-gray-100 my-1"></div>
                    <livewire:auth.logout
                        class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors cursor-pointer" />
                </div>
            </div>
        </div>

        <!-- Mobile Menu Button -->
        <button class="md:hidden p-2 text-gray-600" @click="isMenuOpen = !isMenuOpen">
            <span class="material-symbols-outlined text-3xl" x-text="isMenuOpen ? 'close' : 'menu'">menu</span>
        </button>
    </div>

    <!-- Mobile Menu Overlay -->
    <div x-show="isMenuOpen" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="md:hidden absolute top-full left-0 w-full bg-white border-b border-[#dbe6e1] shadow-xl py-4 px-4 flex flex-col gap-4"
        style="display: none;">
        <div class="px-2 py-3 border-b border-gray-100">
            <div class="flex items-center gap-3 px-2">
                <div class="size-10 rounded-full bg-gray-200 border-2 border-primary overflow-hidden">
                    @if (auth()->user()->avatar_url)
                        <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}"
                            class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-primary text-[#111815] font-bold">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    @endif
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-900">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                </div>
            </div>
        </div>
        <a class="text-[#111815] text-lg font-medium p-2 hover:bg-gray-100 rounded-lg transition-colors flex items-center gap-2 {{ request()->routeIs('dashboard') ? 'text-primary bg-primary/5' : '' }}"
            href="{{ route('dashboard') }}" wire:navigate>
            <span class="material-symbols-outlined">dashboard</span> Dashboard
        </a>
        <a class="text-[#111815] text-lg font-medium p-2 hover:bg-gray-100 rounded-lg transition-colors flex items-center gap-2 {{ request()->routeIs('goals.*') ? 'text-primary bg-primary/5' : '' }}"
            href="{{ route('goals.index') }}" wire:navigate>
            <span class="material-symbols-outlined">flag</span> Metas
        </a>
        <a class="text-[#111815] text-lg font-medium p-2 hover:bg-gray-100 rounded-lg transition-colors flex items-center gap-2 {{ request()->routeIs('social.feed') ? 'text-primary bg-primary/5' : '' }}"
            href="{{ route('social.feed') }}" wire:navigate>
            <span class="material-symbols-outlined">forum</span> Social
        </a>
        <a class="text-[#111815] text-lg font-medium p-2 hover:bg-gray-100 rounded-lg transition-colors flex items-center gap-2 {{ request()->routeIs('social.index') ? 'text-primary bg-primary/5' : '' }}"
            href="{{ route('social.index') }}" wire:navigate>
            <span class="material-symbols-outlined">explore</span> Explorar
        </a>
        <div class="h-px bg-gray-100 my-2"></div>
        <a href="{{ route('social.profile', auth()->user()->nickname ?: auth()->id()) }}"
            class="text-[#111815] text-lg font-medium p-2 hover:bg-gray-100 rounded-lg transition-colors flex items-center gap-2"
            wire:navigate>
            <span class="material-symbols-outlined">person</span> Meu Perfil
        </a>
        @hasanyrole('Administrador|Suporte')
            <a href="{{ route('admin.dashboard') }}"
                class="text-primary text-lg font-bold p-2 hover:bg-primary/5 rounded-lg transition-colors flex items-center gap-2"
                wire:navigate>
                <span class="material-symbols-outlined">admin_panel_settings</span> Painel Admin
            </a>
        @endhasanyrole
        <livewire:auth.logout
            class="flex w-full items-center justify-start p-2 text-red-600 text-lg font-medium hover:bg-red-50 transition-colors cursor-pointer" />
    </div>
</header>
