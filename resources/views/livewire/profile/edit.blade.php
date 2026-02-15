<div class="max-w-3xl mx-auto px-4 py-10 space-y-8">
    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-black uppercase tracking-tighter">Editar Perfil</h1>
        <p class="text-sm text-gray-500 font-medium mt-1">Gerencie suas informações pessoais e configurações da conta.
        </p>
    </div>

    {{-- Informações do Perfil --}}
    <section class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm space-y-6">
        <div class="flex items-center gap-2 mb-2">
            <span class="material-symbols-outlined text-primary" style="font-size: 20px">person</span>
            <h2 class="text-lg font-black uppercase tracking-tighter">Informações Pessoais</h2>
        </div>

        <form wire:submit.prevent="updateProfile" class="space-y-5">
            <div class="space-y-2">
                <label for="name" class="block text-sm font-bold text-[#111815]">Nome</label>
                <x-text-input id="name" type="text" wire:model="name" required autocomplete="name" />
                @error('name')
                    <p class="text-red-500 text-xs font-bold">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label for="email" class="block text-sm font-bold text-[#111815]">E-mail</label>
                <x-text-input id="email" type="email" value="{{ Auth::user()->email }}"
                    class="bg-gray-50 text-gray-400 cursor-not-allowed" disabled />
                <p class="text-xs text-gray-400 font-medium">O e-mail não pode ser alterado.</p>
            </div>

            @if ($mustVerifyEmail && !Auth::user()->hasVerifiedEmail())
                <div class="bg-amber-50 border border-amber-100 rounded-xl p-3 flex items-center gap-2">
                    <span class="material-symbols-outlined text-amber-500" style="font-size: 18px">warning</span>
                    <p class="text-sm text-amber-700 font-medium">Seu e-mail ainda não foi verificado.</p>
                </div>
            @endif

            <div class="flex justify-end">
                <x-primary-button>
                    Salvar Alterações
                </x-primary-button>
            </div>
        </form>
    </section>

    {{-- Excluir Conta --}}
    <section class="bg-white border border-red-100 rounded-2xl p-6 shadow-sm space-y-4">
        <div class="flex items-center gap-2 mb-2">
            <span class="material-symbols-outlined text-red-500" style="font-size: 20px">delete_forever</span>
            <h2 class="text-lg font-black uppercase tracking-tighter text-red-600">Excluir Conta</h2>
        </div>

        <p class="text-sm text-gray-500 font-medium">
            Após excluir sua conta, todos os dados serão permanentemente removidos.
            Antes de prosseguir, salve qualquer informação que deseja manter.
        </p>

        <button wire:click="$set('showDeleteModal', true)"
            class="px-5 py-2.5 bg-red-500 hover:bg-red-600 text-white font-bold rounded-xl transition-colors text-sm">
            Excluir Minha Conta
        </button>
    </section>

    {{-- Modal de Confirmação --}}
    @if ($showDeleteModal)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4" x-data
            x-on:keydown.escape.window="$wire.set('showDeleteModal', false)">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 space-y-4">
                <div class="text-center">
                    <span class="material-symbols-outlined text-red-500 text-4xl mb-2">warning</span>
                    <h2 class="text-xl font-black uppercase tracking-tighter">Tem certeza?</h2>
                    <p class="text-sm text-gray-500 font-medium mt-2">
                        Esta ação é irreversível. Digite sua senha para confirmar.
                    </p>
                </div>

                <form wire:submit.prevent="deleteAccount" class="space-y-4">
                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-bold text-[#111815]">Senha</label>
                        <x-text-input id="password" type="password" wire:model="password"
                            placeholder="Digite sua senha" required />
                        @error('password')
                            <p class="text-red-500 text-xs font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-3">
                        <x-secondary-button wire:click="$set('showDeleteModal', false)">
                            Cancelar
                        </x-secondary-button>
                        <x-danger-button>
                            Excluir Permanentemente
                        </x-danger-button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
