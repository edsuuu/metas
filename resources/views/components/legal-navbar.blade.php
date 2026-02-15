<header x-data="{ isMenuOpen: false }" class="w-full border-b border-solid border-[#dbe6e1]  bg-white/80  backdrop-blur-md px-4 md:px-20 lg:px-40 py-4 sticky top-0 z-50">
    <div class="max-w-[1200px] mx-auto flex items-center justify-between">
        <a href="{{ route('home') }}" class="flex items-center gap-3 hover:opacity-80 transition-opacity">
            <div class="size-8 text-primary">
                <svg fill="currentColor" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                    <path clipRule="evenodd" d="M24 4H42V17.3333V30.6667H24V44H6V30.6667V17.3333H24V4Z" fillRule="evenodd"></path>
                </svg>
            </div>
            <h2 class="text-[#111815]  text-xl font-bold leading-tight tracking-tight">
                Everest
            </h2>
        </a>

        <nav class="hidden md:flex items-center gap-9">
            @guest
                <a class="text-[#111815]  text-sm font-medium hover:text-primary transition-colors" href="{{ route('pricing') }}">
                    Planos
                </a>
            @endguest
{{--            @auth--}}
{{--                <a class="text-[#111815]  text-sm font-medium hover:text-primary transition-colors" href="{{ route('achievements') }}">--}}
{{--                    Conquistas--}}
{{--                </a>--}}
{{--            @endauth--}}
            <a class="text-[#111815]  text-sm font-medium hover:text-primary transition-colors" href="{{ route('blog') }}">
                Blog
            </a>
            <a class="text-[#111815]  text-sm font-medium hover:text-primary transition-colors" href="{{ route('support.index') }}">
                Contato
            </a>
        </nav>

        <div class="hidden md:flex items-center gap-6">
            @if(isset($slot) && $slot->isNotEmpty())
                {{ $slot }}
            @elseif(auth()->check())
                <div class="flex items-center gap-3">
                    <a class="text-sm font-bold text-primary px-4 py-2 rounded-full border border-primary hover:bg-primary hover:text-white transition-all" href="{{ route('dashboard') }}">
                        Dashboard
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex cursor-pointer items-center justify-center rounded-full h-10 px-5 bg-gray-100  text-gray-700  text-sm font-bold hover:bg-gray-200  transition-colors">
                            Sair
                        </button>
                    </form>
                </div>
            @else
                <a class="text-sm font-bold text-primary px-4 py-2 rounded-full border border-primary hover:bg-primary hover:text-white transition-all" href="{{ route('dashboard') }}">
                    Ir para o App
                </a>
            @endif
        </div>

        {{-- Mobile Menu Button --}}
        <button class="md:hidden p-2 text-gray-600 " @click="isMenuOpen = !isMenuOpen">
            <span class="material-symbols-outlined text-3xl" x-text="isMenuOpen ? 'close' : 'menu'">menu</span>
        </button>
    </div>

    {{-- Mobile Menu Overlay --}}
    <div x-show="isMenuOpen"
         x-transition:enter="animate-in slide-in-from-top-2"
         class="md:hidden absolute top-full left-0 w-full bg-white  border-b border-[#dbe6e1]  shadow-xl py-4 px-4 flex flex-col gap-4"
         style="display: none;">
        @guest
            <a class="text-[#111815]  text-lg font-medium p-2 hover:bg-gray-100  rounded-lg transition-colors" href="{{ route('pricing') }}">
                Planos
            </a>
        @endguest
{{--        @auth--}}
{{--            <a class="text-[#111815]  text-lg font-medium p-2 hover:bg-gray-100  rounded-lg transition-colors" href="{{ route('achievements') }}">--}}
{{--                Conquistas--}}
{{--            </a>--}}
{{--        @endauth--}}
        <a class="text-[#111815]  text-lg font-medium p-2 hover:bg-gray-100  rounded-lg transition-colors" href="{{ route('blog') }}">
            Blog
        </a>
        <a class="text-[#111815]  text-lg font-medium p-2 hover:bg-gray-100  rounded-lg transition-colors" href="{{ route('support.index') }}">
            Contato
        </a>
        <div class="h-px bg-gray-100  my-2"></div>

        @auth
            <a href="{{ route('dashboard') }}" class="flex items-center justify-center rounded-xl h-12 bg-primary text-[#111815] text-lg font-bold shadow-lg shadow-primary/20">
                Dashboard
            </a>

            <a href="{{ route('support.my-tickets') }}" class="flex items-center justify-center rounded-xl h-12 bg-white  text-[#111815]  text-lg font-bold border border-[#dbe6e1] ">
                Meus Chamados
            </a>

            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" class="flex w-full items-center justify-center rounded-xl h-12 bg-gray-100  text-gray-700  text-lg font-bold hover:bg-gray-200  transition-colors">
                    Sair
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" class="flex items-center justify-center rounded-xl h-12 bg-background-light  text-[#111815]  text-lg font-bold border border-[#dbe6e1] ">
                Login
            </a>
            <a href="{{ route('register') }}" class="flex items-center justify-center rounded-xl h-12 bg-primary text-[#111815] text-lg font-bold shadow-lg shadow-primary/20">
                Crie sua conta
            </a>
        @endauth
    </div>
</header>
