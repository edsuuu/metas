<div class="bg-gray-50 text-[#111815] transition-colors duration-300 min-h-screen flex flex-col font-display">
    <main class="flex-1 w-full max-w-[1000px] mx-auto px-4 py-8 md:py-12">
        <div class="mb-8 flex items-center gap-4">
            <a class="flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-primary transition-colors"
                href="{{ route('support.my-tickets') }}">
                <span class="material-symbols-outlined text-lg">arrow_back</span>
                Voltar para Meus Chamados
            </a>
        </div>

        <div class="bg-white rounded-3xl p-6 md:p-8 border border-[#dbe6e1] shadow-sm mb-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <span
                            class="text-xs font-bold uppercase tracking-widest text-primary bg-primary/10 px-3 py-1 rounded-full">Protocolo
                            {{ $ticket['protocol'] }}</span>
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $ticket['status_color'] }}">
                            {{ $ticket['status_label'] }}
                        </span>
                    </div>
                    <h1 class="text-2xl md:text-3xl font-black text-[#111815] tracking-tight">{{ $ticket['subject'] }}
                    </h1>
                    <p class="text-sm text-gray-500 mt-1">Criado em {{ $ticket['created_at_formatted'] }}</p>
                </div>
            </div>
        </div>

        @if (session()->has('success'))
            <div
                class="mb-6 p-4 bg-green-100 text-green-700 rounded-2xl font-bold text-sm animate-fade-in flex items-center gap-2">
                <span class="material-symbols-outlined text-lg">check_circle</span>
                {{ session('success') }}
            </div>
        @endif
        @if (session()->has('error'))
            <div
                class="mb-6 p-4 bg-red-100 text-red-700 rounded-2xl font-bold text-sm animate-fade-in flex items-center gap-2">
                <span class="material-symbols-outlined text-lg">error</span>
                {{ session('error') }}
            </div>
        @endif

        <div class="flex-1 overflow-y-auto pr-2 max-h-[600px] mb-10 custom-scrollbar scroll-smooth"
            x-data="{
                scrollToBottom() {
                    this.$el.scrollTop = this.$el.scrollHeight;
                }
            }" x-init="scrollToBottom()"
            x-on:message-sent.window="setTimeout(() => scrollToBottom(), 100)"
            @echo:support.{{ $ticket['id'] }},SupportTicketReplySent="setTimeout(() => scrollToBottom(), 100)"
            wire:poll.15s.keep-alive>
            <div class="space-y-6 pb-4">
                @foreach ($messages as $msg)
                    <div class="flex flex-col {{ $msg['is_admin'] ? 'items-start' : 'items-end' }}">
                        <div class="flex items-center gap-2 mb-2 {{ $msg['is_admin'] ? 'ml-4' : 'mr-4' }}">
                            @if ($msg['is_admin'])
                                <div
                                    class="size-6 bg-primary rounded-full flex items-center justify-center text-[10px] font-bold">
                                    E</div>
                                <span class="text-xs font-bold text-gray-700">{{ $msg['sender_name'] }}</span>
                            @else
                                <span class="text-xs font-bold text-gray-500">Você</span>
                            @endif
                            <span class="text-[10px] text-gray-400">{{ $msg['created_at_time'] }}</span>
                        </div>
                        <div
                            class="max-w-[85%] p-5 rounded-3xl shadow-md break-words {{ $msg['is_admin'] ? 'bg-white text-gray-800 border border-[#dbe6e1] rounded-bl-sm' : 'bg-primary text-[#111815] shadow-primary/10 rounded-br-sm' }}">
                            <p class="text-sm leading-relaxed whitespace-pre-wrap break-all">{{ $msg['message'] }}</p>

                            @if (isset($msg['attachment']) && $msg['attachment'])
                                <div class="mt-3">
                                    <a href="{{ $msg['attachment'] }}" target="_blank"
                                        class="block group/img relative overflow-hidden rounded-2xl border border-black/5">
                                        <img src="{{ $msg['attachment'] }}"
                                            class="max-h-64 rounded-2xl object-cover hover:scale-105 transition-transform duration-500" />
                                        <div
                                            class="absolute inset-0 bg-black/20 opacity-0 group-hover/img:opacity-100 transition-opacity flex items-center justify-center">
                                            <span class="material-symbols-outlined text-white">open_in_new</span>
                                        </div>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
                <div x-ref="messagesEnd"></div>
            </div>
        </div>

        @if ($ticket['status'] !== 'resolved' && $ticket['status'] !== 'closed')
            <div class="bg-white rounded-3xl border-2 border-primary/20 p-4 md:p-6 shadow-xl shadow-primary/5">
                <div class="flex items-center gap-2 mb-4">
                    <span class="material-symbols-outlined text-primary">reply</span>
                    <h3 class="font-bold text-[#111815]">Responder</h3>
                </div>
                <form wire:submit="reply">
                    <textarea
                        class="w-full rounded-2xl border-[#dbe6e1] bg-gray-50 focus:ring-primary focus:border-primary text-sm min-h-[120px] mb-4 p-4 placeholder-gray-400 resize-none"
                        placeholder="Digite sua mensagem aqui..." wire:model="message"></textarea>
                    @error('message')
                        <p class="text-red-500 text-xs font-bold mb-4">{{ $message }}</p>
                    @enderror

                    <div class="mb-6">
                        <label
                            class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-600 rounded-xl cursor-pointer hover:bg-gray-200 transition-colors text-xs font-bold">
                            <span class="material-symbols-outlined text-sm">image</span>
                            {{ $attachment ? 'Imagem selecionada' : 'Anexar imagem' }}
                            <input type="file" wire:model="attachment" class="hidden" accept="image/*">
                        </label>
                        @if ($attachment)
                            <div class="mt-2 relative inline-block">
                                <img src="{{ $attachment->temporaryUrl() }}"
                                    class="size-20 rounded-xl object-cover border-2 border-primary" />
                                <button type="button" wire:click="$set('attachment', null)"
                                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full size-5 flex items-center justify-center shadow-lg">
                                    <span class="material-symbols-outlined text-[10px]">close</span>
                                </button>
                            </div>
                        @endif
                        @error('attachment')
                            <p class="text-red-500 text-xs font-bold mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end pt-2 border-t border-gray-100">
                        <button
                            class="w-full md:w-auto px-8 py-3 bg-[#111815] text-white font-bold rounded-full hover:scale-[1.02] active:scale-95 transition-transform shadow-lg shadow-black/20 cursor-pointer"
                            wire:loading.attr="disabled" wire:target="reply">
                            <span wire:loading.remove wire:target="reply">Enviar Resposta</span>
                            <span wire:loading wire:target="reply">Enviando...</span>
                        </button>
                    </div>
                </form>
            </div>
        @else
            <div class="bg-gray-50 rounded-3xl border border-[#dbe6e1] p-6 shadow-sm text-center">
                <span class="material-symbols-outlined text-gray-400 text-4xl mb-3">lock</span>
                <h3 class="font-bold text-gray-700 mb-2">Este chamado está finalizado</h3>
                <p class="text-sm text-gray-500">Não é mais possível enviar mensagens para este chamado pois ele já foi
                    resolvido.</p>
            </div>
        @endif
    </main>
</div>
