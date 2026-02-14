<div class="bg-gray-50 text-[#111815] transition-colors duration-300 min-h-screen flex flex-col font-display">
    <main class="flex-1 w-full max-w-[1000px] mx-auto px-4 py-8 md:py-12">
        <div class="mb-8 flex items-center gap-4">
            <a class="flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-primary transition-colors" href="{{ route('support.my-tickets') }}">
                <span class="material-symbols-outlined text-lg">arrow_back</span>
                Voltar para Meus Chamados
            </a>
        </div>

        <div class="bg-white rounded-3xl p-6 md:p-8 border border-[#dbe6e1] shadow-sm mb-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <span class="text-xs font-bold uppercase tracking-widest text-primary bg-primary/10 px-3 py-1 rounded-full">Protocolo {{ $ticket['protocol'] }}</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $ticket['status_color'] }}">
                            {{ $ticket['status_label'] }}
                        </span>
                    </div>
                    <h1 class="text-2xl md:text-3xl font-black text-[#111815] tracking-tight">{{ $ticket['subject'] }}</h1>
                    <p class="text-sm text-gray-500 mt-1">Criado em {{ $ticket['created_at_formatted'] }}</p>
                </div>
            </div>
        </div>
        
        @if(session()->has('success'))
            <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-2xl font-bold text-sm animate-fade-in flex items-center gap-2">
                <span class="material-symbols-outlined text-lg">check_circle</span>
                {{ session('success') }}
            </div>
        @endif
        @if(session()->has('error'))
            <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-2xl font-bold text-sm animate-fade-in flex items-center gap-2">
                <span class="material-symbols-outlined text-lg">error</span>
                {{ session('error') }}
            </div>
        @endif

        <div 
            class="flex-1 overflow-y-auto pr-2 max-h-[600px] mb-10 custom-scrollbar scroll-smooth"
            x-data="{ 
                scrollToBottom() { 
                    this.$refs.messagesEnd?.scrollIntoView({ behavior: 'smooth' }) 
                } 
            }"
            x-init="scrollToBottom()"
            x-effect="$wire.messages.length && setTimeout(() => scrollToBottom(), 100)"
            wire:poll.5s="loadMessages"
        >
            <div class="space-y-6 pb-4">
                @foreach($messages as $msg)
                    <div class="flex flex-col {{ $msg['is_admin'] ? 'items-start' : 'items-end' }}">
                        <div class="flex items-center gap-2 mb-2 {{ $msg['is_admin'] ? 'ml-4' : 'mr-4' }}">
                            @if($msg['is_admin'])
                                <div class="size-6 bg-primary rounded-full flex items-center justify-center text-[10px] font-bold">E</div>
                                <span class="text-xs font-bold text-gray-700">{{ $msg['sender_name'] }}</span>
                            @else
                                <span class="text-xs font-bold text-gray-500">Você</span>
                            @endif
                            <span class="text-[10px] text-gray-400">{{ $msg['created_at_time'] }}</span>
                        </div>
                        <div class="max-w-[85%] p-5 rounded-3xl shadow-md break-words {{ $msg['is_admin'] ? 'bg-white text-gray-800 border border-[#dbe6e1] rounded-bl-sm' : 'bg-primary text-[#111815] shadow-primary/10 rounded-br-sm' }}">
                            <p class="text-sm leading-relaxed whitespace-pre-wrap break-all">{{ $msg['message'] }}</p>
                        </div>
                    </div>
                @endforeach
                <div x-ref="messagesEnd"></div>
            </div>
        </div>

        @if($ticket['status'] !== 'resolved' && $ticket['status'] !== 'closed')
            <div class="bg-white rounded-3xl border-2 border-primary/20 p-4 md:p-6 shadow-xl shadow-primary/5">
                <div class="flex items-center gap-2 mb-4">
                    <span class="material-symbols-outlined text-primary">reply</span>
                    <h3 class="font-bold text-[#111815]">Enviar uma réplica</h3>
                </div>
                <form wire:submit="submit">
                    <textarea 
                        class="w-full rounded-2xl border-[#dbe6e1] bg-gray-50 focus:ring-primary focus:border-primary text-sm min-h-[120px] mb-4 p-4 placeholder-gray-400 resize-none" 
                        placeholder="Digite sua mensagem aqui..."
                        wire:model="message"
                    ></textarea>
                    @error('message') <p class="text-red-500 text-xs font-bold mb-4">{{ $message }}</p> @enderror
                    <div class="flex justify-end">
                        <button 
                            class="w-full md:w-auto px-8 py-3 bg-primary text-[#111815] font-bold rounded-full hover:scale-[1.02] active:scale-95 transition-transform shadow-lg shadow-primary/20 disabled:opacity-50"
                            disabled
                            wire:loading.attr="disabled"
                        >
                            <span wire:loading.remove>Enviar Réplica</span>
                            <span wire:loading>Enviando...</span>
                        </button>
                    </div>
                </form>
            </div>
        @else
            <div class="bg-gray-50 rounded-3xl border border-[#dbe6e1] p-6 shadow-sm text-center">
                <span class="material-symbols-outlined text-gray-400 text-4xl mb-3">lock</span>
                <h3 class="font-bold text-gray-700 mb-2">Este chamado está finalizado</h3>
                <p class="text-sm text-gray-500">Não é mais possível enviar mensagens para este chamado pois ele já foi resolvido.</p>
            </div>
        @endif
    </main>
</div>
