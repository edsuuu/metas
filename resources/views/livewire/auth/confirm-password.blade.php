<div class="flex-1 flex items-center justify-center p-4 py-12">
    @section('title', 'Confirmar Senha')
    <div class="w-full max-w-[480px] bg-white rounded-3xl shadow-2xl border border-[#dbe6e1] overflow-hidden">
        <div class="p-8 md:p-12">
            <div class="flex flex-col gap-2 mb-8 text-center">
                <h1 class="text-[#111815] text-3xl font-black tracking-tight">
                    Área Segura
                </h1>
                <p class="text-gray-500 text-sm">
                    Esta é uma área segura do aplicativo. Por favor, confirme sua senha antes de continuar.
                </p>
            </div>

            @if ($errors->any())
                <div class="mb-4 text-sm text-red-600 text-center">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form wire:submit.prevent="confirm" class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-[#111815] mb-2" for="password">
                        Senha
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

                <div class="flex flex-col gap-3">
                    <button type="submit" wire:loading.attr="disabled"
                        class="w-full cursor-pointer flex items-center justify-center rounded-full h-12 px-5 bg-primary text-[#111815] text-sm font-bold shadow-lg shadow-primary/20 hover:scale-[1.02] transition-transform disabled:opacity-50">
                        <span wire:loading.remove>Confirmar</span>
                        <span wire:loading class="material-symbols-outlined animate-spin text-sm">sync</span>
                    </button>

                    <button type="button" wire:click="logout"
                        class="text-sm font-medium text-gray-500 hover:text-primary transition-colors bg-transparent border-0 cursor-pointer text-center">
                        Sair
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
