<header x-data="{ isMenuOpen: false }" class="sticky top-0 z-50 w-full border-b border-solid border-[#dbe6e1] bg-white/80 backdrop-blur-md px-4 md:px-20 lg:px-40 py-3">
    <div class="max-w-[1200px] mx-auto flex items-center justify-between">
        <a href="{{ route('home') }}" class="flex items-center gap-3 hover:opacity-80 transition-opacity" wire:navigate>
            <div class="size-8 text-primary">
                <svg fill="currentColor" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                    <path clipRule="evenodd" d="M24 4H42V17.3333V30.6667H24V44H6V30.6667V17.3333H24V4Z" fillRule="evenodd"></path>
                </svg>
            </div>
            <h2 class="text-[#111815] text-xl font-bold leading-tight tracking-tight">
                Everest
            </h2>
        </a>

        <!-- Desktop Navigation -->
        <nav class="hidden md:flex items-center gap-9">
            @guest
                <a class="text-[#111815] text-sm font-medium hover:text-primary transition-colors" href="{{ route('pricing') }}" wire:navigate>
                    Planos
                </a>
            @endguest
            @auth
                <a class="text-[#111815] text-sm font-medium hover:text-primary transition-colors" href="{{ route('achievements') }}" wire:navigate>
                    Conquistas
                </a>
            @endauth
            <a class="text-[#111815] text-sm font-medium hover:text-primary transition-colors" href="{{ route('blog') }}" wire:navigate>
                Blog
            </a>
            <a class="text-[#111815] text-sm font-medium hover:text-primary transition-colors" href="{{ route('support') }}" wire:navigate>
                Contato
            </a>
        </nav>

        <!-- Desktop Actions -->
        <div class="hidden md:flex items-center gap-3">
            @auth
                <a href="{{ route('dashboard') }}" class="flex min-w-[100px] cursor-pointer items-center justify-center rounded-full h-10 px-5 bg-primary text-[#111815] text-sm font-bold shadow-lg shadow-primary/20 hover:scale-105 transition-transform" wire:navigate>
                    Dashboard
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex cursor-pointer items-center justify-center rounded-full h-10 px-5 bg-gray-100 text-gray-700 text-sm font-bold hover:bg-gray-200 transition-colors">
                        Sair
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="hidden sm:flex min-w-[84px] cursor-pointer items-center justify-center rounded-full h-10 px-5 bg-gray-50 text-[#111815] text-sm font-bold border border-[#dbe6e1] hover:bg-gray-100 transition-colors" wire:navigate>
                    Login
                </a>
                <a href="{{ route('register') }}" class="flex min-w-[100px] cursor-pointer items-center justify-center rounded-full h-10 px-5 bg-primary text-[#111815] text-sm font-bold shadow-lg shadow-primary/20 hover:scale-105 transition-transform" wire:navigate>
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
    <div x-show="isMenuOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" class="md:hidden absolute top-full left-0 w-full bg-white border-b border-[#dbe6e1] shadow-xl py-4 px-4 flex flex-col gap-4" style="display: none;">
        @guest
            <a class="text-[#111815] text-lg font-medium p-2 hover:bg-gray-100 rounded-lg transition-colors" href="{{ route('pricing') }}" wire:navigate>
                Planos
            </a>
        @endguest
        @auth
            <a class="text-[#111815] text-lg font-medium p-2 hover:bg-gray-100 rounded-lg transition-colors" href="{{ route('achievements') }}" wire:navigate>
                Conquistas
            </a>
        @endauth
        <a class="text-[#111815] text-lg font-medium p-2 hover:bg-gray-100 rounded-lg transition-colors" href="{{ route('blog') }}" wire:navigate>
            Blog
        </a>
        <a class="text-[#111815] text-lg font-medium p-2 hover:bg-gray-100 rounded-lg transition-colors" href="{{ route('support') }}" wire:navigate>
            Contato
        </a>
        <div class="h-px bg-gray-100 my-2"></div>
        @auth
            <a href="{{ route('dashboard') }}" class="flex items-center justify-center rounded-xl h-12 bg-primary text-[#111815] text-lg font-bold shadow-lg shadow-primary/20" wire:navigate>
                Dashboard
            </a>
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" class="flex w-full items-center justify-center rounded-xl h-12 bg-gray-100 text-gray-700 text-lg font-bold hover:bg-gray-200 transition-colors">
                    Sair
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" class="flex items-center justify-center rounded-xl h-12 bg-gray-50 text-[#111815] text-lg font-bold border border-[#dbe6e1]" wire:navigate>
                Login
            </a>
            <a href="{{ route('register') }}" class="flex items-center justify-center rounded-xl h-12 bg-primary text-[#111815] text-lg font-bold shadow-lg shadow-primary/20" wire:navigate>
                Crie sua conta
            </a>
        @endauth
    </div>
</header>
