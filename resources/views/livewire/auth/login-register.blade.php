<div class="bg-gray-50 text-[#111815] min-h-screen flex flex-col">
    @section('title', $isLogin ? 'Entrar' : 'Cadastro')

    <main class="flex-1 flex items-center justify-center p-4 py-12">
        <div class="w-full max-w-[480px] bg-white rounded-3xl shadow-2xl border border-[#dbe6e1] overflow-hidden">
            <div class="p-8 md:p-12">
                <div class="flex flex-col gap-2 mb-8 text-center">
                    <h1 class="text-[#111815] text-3xl font-black tracking-tight">
                        @if ($socialUser)
                            Quase lá!
                        @elseif($isLogin)
                            Bem-vindo de volta
                        @else
                            Crie sua conta
                        @endif
                    </h1>
                    <p class="text-gray-500 text-sm">
                        @if ($socialUser)
                            Escolha um nickname para personalizar sua experiência.
                        @elseif($isLogin)
                            Continue sua jornada rumo ao topo.
                        @else
                            Comece sua escalada hoje mesmo.
                        @endif
                    </p>
                </div>

                <!-- Session Status / Errors -->
                @if (session('status'))
                    <div class="mb-4 font-medium text-sm text-green-600 text-center">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 text-sm text-red-600 text-center">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="flex flex-col gap-4">
                    @if (!$socialUser)
                        <a href="{{ route('auth.google') }}"
                            class="w-full flex items-center justify-center gap-3 rounded-full h-12 px-6 bg-white border border-[#dbe6e1] text-[#111815] text-sm font-bold hover:bg-gray-50 transition-colors">
                            <svg class="w-5 h-5" viewBox="0 0 24 24">
                                <path
                                    d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                                    fill="#4285F4"></path>
                                <path
                                    d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                                    fill="#34A853"></path>
                                <path
                                    d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
                                    fill="#FBBC05"></path>
                                <path
                                    d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                                    fill="#EA4335"></path>
                            </svg>
                            Continuar com Google
                        </a>

                        <div class="relative my-4">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-[#dbe6e1]"></div>
                            </div>
                            <div class="relative flex justify-center text-xs uppercase">
                                <span class="bg-white px-4 text-gray-500">
                                    ou entre com e-mail
                                </span>
                            </div>
                        </div>
                    @endif

                    <form wire:submit.prevent="{{ $isLogin ? 'login' : 'register' }}" class="space-y-4">
                        @if (!$isLogin || $socialUser)
                            <div>
                                <label class="block text-sm font-bold text-[#111815] mb-2" for="nickname">
                                    Escolha seu Nickname
                                </label>
                                <div class="relative">
                                    <span
                                        class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 material-symbols-outlined text-xl">
                                        alternate_email
                                    </span>
                                    <input wire:model="nickname" id="nickname" type="text"
                                        class="w-full pl-11 pr-4 h-12 rounded-2xl bg-gray-50 border-transparent focus:border-primary focus:ring-primary text-sm transition-all"
                                        placeholder="ex: alpinista_urbano">
                                </div>
                                @error('nickname')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif

                        @if (!$socialUser)
                            <div>
                                <label class="block text-sm font-bold text-[#111815] mb-2" for="email">
                                    E-mail
                                </label>
                                <div class="relative">
                                    <span
                                        class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 material-symbols-outlined text-xl">
                                        mail
                                    </span>
                                    <input wire:model="email" id="email" type="email"
                                        class="w-full pl-11 pr-4 h-12 rounded-2xl bg-gray-50 border-transparent focus:border-primary focus:ring-primary text-sm transition-all"
                                        placeholder="seu@email.com">
                                </div>
                                @error('email')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <label class="block text-sm font-bold text-[#111815]" for="password">
                                        Senha
                                    </label>
                                    @if ($isLogin)
                                        <a href="{{ route('password.request') }}"
                                            class="text-xs text-primary font-bold hover:underline">
                                            Esqueceu a senha?
                                        </a>
                                    @endif
                                </div>
                                <div class="relative">
                                    <span
                                        class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 material-symbols-outlined text-xl">
                                        lock
                                    </span>
                                    <input wire:model="password" id="password" type="password"
                                        class="w-full pl-11 pr-4 h-12 rounded-2xl bg-gray-50 border-transparent focus:border-primary focus:ring-primary text-sm transition-all"
                                        placeholder="••••••••">
                                </div>
                                @error('password')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror

                                @if (!$isLogin)
                                    <!-- Password Confirmation for Registration -->
                                    <div class="mt-4">
                                        <label class="block text-sm font-bold text-[#111815] mb-2"
                                            for="password_confirmation">
                                            Confirmar Senha
                                        </label>
                                        <div class="relative">
                                            <span
                                                class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 material-symbols-outlined text-xl">
                                                lock
                                            </span>
                                            <input wire:model="password_confirmation" id="password_confirmation"
                                                type="password"
                                                class="w-full pl-11 pr-4 h-12 rounded-2xl bg-gray-50 border-transparent focus:border-primary focus:ring-primary text-sm transition-all"
                                                placeholder="••••••••">
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <button type="submit" wire:loading.attr="disabled"
                            class="w-full cursor-pointer flex items-center justify-center rounded-full h-12 px-5 bg-primary text-[#111815] text-sm font-bold shadow-lg shadow-primary/20 hover:scale-[1.02] transition-transform mt-2 disabled:opacity-50">
                            <span wire:loading.remove>
                                {{ $socialUser ? 'Concluir Cadastro' : ($isLogin ? 'Entrar' : 'Criar Conta') }}
                            </span>
                            <span wire:loading class="material-symbols-outlined animate-spin text-sm">sync</span>
                        </button>
                    </form>

                    @if (!$socialUser)
                        <p class="text-center text-sm text-gray-500 mt-6">
                            {{ $isLogin ? 'Não tem uma conta?' : 'Já tem uma conta?' }}
                            <button wire:click="toggleMode"
                                class="text-primary font-bold hover:underline bg-transparent border-0 cursor-pointer p-0">
                                {{ $isLogin ? 'Cadastre-se' : 'Faça Login' }}
                            </button>
                        </p>
                    @endif
                </div>
            </div>
            <div class="bg-gray-50 p-6 text-center border-t border-[#dbe6e1]">
                <p class="text-xs text-gray-500 leading-relaxed">
                    Ao continuar, você concorda com nossos
                    <a href="{{ route('legal.terms.index') }}" class="underline">Termos de Uso</a>
                    e
                    <a href="{{ route('legal.privacy') }}" class="underline">Política de Privacidade</a>.
                </p>
            </div>
        </div>
    </main>
</div>
