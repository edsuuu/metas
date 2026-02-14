<div class="flex-1 flex items-center justify-center p-4 py-12">
    @section('title', 'Verificar E-mail')
    <div class="w-full max-w-[480px] bg-white rounded-3xl shadow-2xl border border-[#dbe6e1] overflow-hidden">
        <div class="p-8 md:p-12">
            <div class="flex flex-col gap-2 mb-8 text-center">
                <h1 class="text-[#111815] text-3xl font-black tracking-tight">
                    Verifique seu E-mail
                </h1>
                <p class="text-gray-500 text-sm">
                    Obrigado por se inscrever! Antes de começar, você poderia verificar seu endereço de e-mail
                    clicando no link que acabamos de enviar para você? Se você não recebeu o e-mail, teremos prazer
                    em enviar outro.
                </p>
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-4 font-medium text-sm text-green-600 text-center">
                    Um novo link de verificação foi enviado para o endereço de e-mail fornecido durante o registro.
                </div>
            @endif

            <div class="flex flex-col gap-4">
                <button wire:click="sendVerification" wire:loading.attr="disabled"
                    class="w-full cursor-pointer flex items-center justify-center rounded-full h-12 px-5 bg-primary text-[#111815] text-sm font-bold shadow-lg shadow-primary/20 hover:scale-[1.02] transition-transform disabled:opacity-50">
                    <span wire:loading.remove>Reenviar E-mail de Verificação</span>
                    <span wire:loading class="material-symbols-outlined animate-spin text-sm">sync</span>
                </button>

                <button wire:click="logout"
                    class="w-full cursor-pointer flex items-center justify-center rounded-full h-12 px-5 bg-transparent border border-gray-300 text-gray-600 text-sm font-bold hover:bg-gray-50 transition-colors">
                    Sair
                </button>
            </div>
        </div>
    </div>
</div>
