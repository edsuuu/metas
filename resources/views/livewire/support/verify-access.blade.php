<div class="bg-gray-50 text-[#111815] transition-colors duration-300 min-h-screen flex flex-col font-display">
    <main class="flex-1 flex flex-col items-center justify-center p-4">
         <div class="w-full max-w-md bg-white rounded-[2rem] border border-[#dbe6e1] shadow-xl p-8 md:p-12 text-center">
            <div class="size-16 bg-primary/10 text-primary rounded-full flex items-center justify-center mx-auto mb-6">
                <span class="material-symbols-outlined text-3xl">lock_open</span>
            </div>
            
            <h1 class="text-2xl md:text-3xl font-black text-[#111815] mb-4">Verifique seu acesso</h1>
            
            <p class="text-gray-600 mb-8">
                Enviamos um código de acesso para <strong>{{ $email }}</strong>. Digite-o abaixo para ver seus chamados.
            </p>

            <form wire:submit="submit" class="space-y-6">
                <div>
                     <input 
                        class="w-full h-16 text-center text-3xl font-black tracking-[0.5em] rounded-2xl bg-gray-50 border-2 border-[#dbe6e1] focus:border-primary focus:ring-0 transition-all outline-none" 
                        placeholder="000000" 
                        maxlength="6"
                        type="text"
                        wire:model="code"
                        x-data x-mask="999999"
                    />
                    @error('code') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                </div>

                <button 
                    class="w-full h-14 rounded-full bg-primary text-[#111815] text-base font-bold shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-transform disabled:opacity-50" 
                    type="submit"
                >
                    <span wire:loading.remove>Confirmar Acesso</span>
                    <span wire:loading>Verificando...</span>
                </button>
            </form>
            
            <p class="mt-8 text-sm text-gray-500">
                Não recebeu o código? <a href="{{ route('support.my-tickets') }}" class="text-primary font-bold hover:underline">Tentar novamente</a>
            </p>
        </div>
    </main>
</div>
