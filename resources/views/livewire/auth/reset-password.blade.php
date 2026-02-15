<div class="flex-1 flex items-center justify-center p-4 py-12">
    @section('title', 'Redefinir Senha')
    <div class="w-full max-w-[480px] bg-white rounded-3xl shadow-2xl border border-[#dbe6e1] overflow-hidden">
        <div class="p-8 md:p-12">
            <div class="flex flex-col gap-2 mb-8 text-center">
                <h1 class="text-[#111815] text-3xl font-black tracking-tight">
                    Redefinir Senha
                </h1>
                <p class="text-gray-500 text-sm">
                    Crie uma nova senha segura para sua conta.
                </p>
            </div>



            <form wire:submit.prevent="submit" class="space-y-4">
                <input type="hidden" wire:model="token">

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
                            class="w-full pl-11 pr-4 h-12 rounded-2xl bg-gray-100 text-[#111815] border-transparent focus:border-primary focus:ring-primary text-sm transition-all cursor-not-allowed opacity-75"
                            readonly disabled>
                    </div>
                    @error('email')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-[#111815] mb-2" for="password">
                        Nova Senha
                    </label>
                    <div class="relative">
                        <span
                            class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 material-symbols-outlined text-xl">
                            lock
                        </span>
                        <input wire:model="password" id="password" type="password"
                            class="w-full pl-11 pr-4 h-12 rounded-2xl bg-gray-50 border-transparent focus:border-primary focus:ring-primary text-sm transition-all"
                            placeholder="••••••••" required autofocus>
                    </div>
                    @error('password')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-[#111815] mb-2" for="password_confirmation">
                        Confirmar Nova Senha
                    </label>
                    <div class="relative">
                        <span
                            class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 material-symbols-outlined text-xl">
                            lock
                        </span>
                        <input wire:model="password_confirmation" id="password_confirmation" type="password"
                            class="w-full pl-11 pr-4 h-12 rounded-2xl bg-gray-50 border-transparent focus:border-primary focus:ring-primary text-sm transition-all"
                            placeholder="••••••••" required>
                    </div>
                </div>

                <button type="submit" wire:loading.attr="disabled"
                    class="w-full cursor-pointer flex items-center justify-center rounded-full h-12 px-5 bg-primary text-[#111815] text-sm font-bold shadow-lg shadow-primary/20 hover:scale-[1.02] transition-transform mt-2 disabled:opacity-50">
                    <span wire:loading.remove>Redefinir Senha</span>
                    <span wire:loading class="material-symbols-outlined animate-spin text-sm">sync</span>
                </button>
            </form>
        </div>
    </div>
</div>
