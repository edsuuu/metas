<header x-data="{ isMenuOpen: false }"
    class="sticky top-0 z-50 w-full border-b border-solid border-[#dbe6e1] bg-white/80 backdrop-blur-md px-4 md:px-20 lg:px-40 py-3">
    <div class="max-w-[1200px] mx-auto flex items-center justify-between">
        <a href="{{ route('home') }}" class="flex items-center gap-3 hover:opacity-80 transition-opacity" wire:navigate>
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
            <a class="text-[#111815] text-sm font-bold border-b-2 transition-colors {{ request()->routeIs('achievements') ? 'text-primary border-primary' : 'border-transparent hover:text-primary' }}"
                href="{{ route('achievements') }}" wire:navigate>
                Conquistas
            </a>
            <a class="text-[#111815] text-sm font-bold border-b-2 transition-colors {{ request()->routeIs('blog') ? 'text-primary border-primary' : 'border-transparent hover:text-primary' }}"
                href="{{ route('blog') }}" wire:navigate>
                Blog
            </a>
            <a class="text-[#111815] text-sm font-bold border-b-2 transition-colors {{ request()->routeIs('support.index') ? 'text-primary border-primary' : 'border-transparent hover:text-primary' }}"
                href="{{ route('support.index') }}" wire:navigate>
                Contato
            </a>
        </nav>

        <!-- Desktop Actions -->
        <div class="hidden md:flex items-center gap-3">
            @auth
                <a href="{{ route('dashboard') }}"
                    class="flex min-w-[100px] cursor-pointer items-center justify-center rounded-full h-10 px-5 bg-primary text-[#111815] text-sm font-bold shadow-lg shadow-primary/20 hover:scale-105 transition-transform"
                    wire:navigate>
                    Dashboard
                </a>
                @hasanyrole('Administrador|Suporte')
                    <a href="{{ route('admin.dashboard') }}"
                        class="flex min-w-[100px] cursor-pointer items-center justify-center rounded-full h-10 px-5 bg-white text-primary text-sm font-bold border border-primary hover:bg-primary/5 transition-colors"
                        wire:navigate>
                        Admin
                    </a>
                @endhasanyrole
                <livewire:auth.logout
                    class="flex min-w-[84px] cursor-pointer items-center justify-center rounded-full h-10 px-5 bg-gray-50 text-[#111815] text-sm font-bold border border-[#dbe6e1] hover:bg-gray-100 transition-colors"
                    label="Sair" />
            @else
                <a href="{{ route('login') }}"
                    class="hidden sm:flex min-w-[84px] cursor-pointer items-center justify-center rounded-full h-10 px-5 bg-gray-50 text-[#111815] text-sm font-bold border border-[#dbe6e1] hover:bg-gray-100 transition-colors"
                    wire:navigate>
                    Login
                </a>
                <a href="{{ route('register') }}"
                    class="flex min-w-[100px] cursor-pointer items-center justify-center rounded-full h-10 px-5 bg-primary text-[#111815] text-sm font-bold shadow-lg shadow-primary/20 hover:scale-105 transition-transform"
                    wire:navigate>
                    Crie sua conta
                </a>
            @endauth
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
        <a class="text-[#111815] text-lg font-medium p-2 hover:bg-gray-100 rounded-lg transition-colors flex items-center gap-2 {{ request()->routeIs('achievements') ? 'text-primary bg-primary/5' : '' }}"
            href="{{ route('achievements') }}" wire:navigate>
            <span class="material-symbols-outlined">emoji_events</span> Conquistas
        </a>
        <a class="text-[#111815] text-lg font-medium p-2 hover:bg-gray-100 rounded-lg transition-colors flex items-center gap-2 {{ request()->routeIs('blog') ? 'text-primary bg-primary/5' : '' }}"
            href="{{ route('blog') }}" wire:navigate>
            <span class="material-symbols-outlined">article</span> Blog
        </a>
        <a class="text-[#111815] text-lg font-medium p-2 hover:bg-gray-100 rounded-lg transition-colors flex items-center gap-2 {{ request()->routeIs('support.index') ? 'text-primary bg-primary/5' : '' }}"
            href="{{ route('support.index') }}" wire:navigate>
            <span class="material-symbols-outlined">contact_support</span> Contato
        </a>

        @auth
            <div class="h-px bg-gray-100 my-2"></div>
            <a class="text-[#111815] text-lg font-medium p-2 hover:bg-gray-100 rounded-lg transition-colors flex items-center gap-2 {{ request()->routeIs('dashboard') ? 'text-primary bg-primary/5' : '' }}"
                href="{{ route('dashboard') }}" wire:navigate>
                <span class="material-symbols-outlined">dashboard</span> Dashboard
            </a>
            @hasanyrole('Administrador|Suporte')
                <a class="text-primary text-lg font-bold p-2 hover:bg-primary/5 rounded-lg transition-colors flex items-center gap-2"
                    href="{{ route('admin.dashboard') }}" wire:navigate>
                    <span class="material-symbols-outlined">admin_panel_settings</span> Painel Admin
                </a>
            @endhasanyrole
            <livewire:auth.logout
                class="flex w-full items-center justify-start p-2 text-red-600 text-lg font-medium hover:bg-red-50 transition-colors cursor-pointer"
                label="Sair" />
        @else
            <div class="h-px bg-gray-100 my-2"></div>
            <a href="{{ route('login') }}"
                class="flex items-center justify-center rounded-xl h-12 bg-gray-50 text-[#111815] text-lg font-bold border border-[#dbe6e1]"
                wire:navigate>
                Login
            </a>
            <a href="{{ route('register') }}"
                class="flex items-center justify-center rounded-xl h-12 bg-primary text-[#111815] text-lg font-bold shadow-lg shadow-primary/20"
                wire:navigate>
                Crie sua conta
            </a>
        @endauth
    </div>
</header>
