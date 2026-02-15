<div wire:poll.15s.keep-alive class="h-full flex flex-col overflow-hidden">
    <div class="max-w-[1000px] w-full mx-auto space-y-4 flex flex-col h-full">
        <div class="flex-none">
            <div class="mb-4 flex items-center gap-4">
                <a class="flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-primary transition-colors"
                    href="{{ route('admin.tickets.index') }}" wire:navigate>
                    <span class="material-symbols-outlined text-lg">arrow_back</span>
                    Voltar para Listagem
                </a>
            </div>

            <div class="bg-white rounded-3xl p-6 border border-[#dbe6e1] shadow-sm">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <span
                                class="text-xs font-bold uppercase tracking-widest text-primary bg-primary/10 px-3 py-1 rounded-full">Protocolo
                                {{ $ticket->protocol }}</span>
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $ticket->status_color }}">{{ $ticket->status_label }}</span>
                        </div>
                        <h1 class="text-xl md:text-2xl font-black text-[#111815] tracking-tight break-all">
                            {{ $ticket->subject }}</h1>
                        <div class="flex items-center gap-4 mt-1">
                            <p class="text-xs font-bold text-gray-700">De: {{ $ticket->name }}</p>
                            <p class="text-[10px] text-gray-400">Aberto em
                                {{ $ticket->created_at->format('d/m/Y H:i') }}
                            </p>
                        </div>
                    </div>
                    <div>
                        @if ($ticket->status !== 'resolved' && $ticket->status !== 'closed')
                            <button x-on:click="$dispatch('open-modal', 'confirm-close-ticket')"
                                class="w-full md:w-auto px-6 py-2 bg-gray-100 text-gray-600 font-bold rounded-full hover:bg-red-500 hover:text-white transition-all flex items-center justify-center gap-2 shadow-sm cursor-pointer text-sm">
                                <span class="material-symbols-outlined text-sm">check_circle</span>
                                Finalizar
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Messages --}}
        <div class="flex-1 overflow-y-auto pr-2 scroll-smooth custom-scrollbar" x-data="{
            scrollToBottom() {
                this.$nextTick(() => {
                    this.$el.scrollTo({
                        top: this.$el.scrollHeight,
                        behavior: 'smooth'
                    });
                });
            }
        }"
            x-init="setTimeout(() => scrollToBottom(), 100)" x-on:message-sent.window="setTimeout(() => scrollToBottom(), 200)"
            @echo:support.{{ $ticket->id }},SupportTicketReplySent="setTimeout(() => scrollToBottom(), 200)">
            <div class="space-y-6 pb-4">
                {{-- Initial message --}}
                <div class="flex flex-col items-start">
                    <div class="flex items-center gap-2 mb-2 ml-4">
                        <div
                            class="size-6 bg-gray-200 rounded-full flex items-center justify-center text-[10px] font-bold">
                            U</div>
                        <span class="text-xs font-bold text-gray-700">{{ $ticket->name }}</span>
                        <span class="text-[10px] text-gray-400">{{ $ticket->created_at->format('H:i') }}</span>
                    </div>
                    <div
                        class="max-w-[85%] p-5 rounded-3xl shadow-md bg-white text-gray-800 border border-[#dbe6e1] rounded-bl-sm">
                        <p class="text-sm leading-relaxed whitespace-pre-wrap break-all">{{ $ticket->message }}</p>
                    </div>
                </div>

                {{-- Replies --}}
                @foreach ($ticket->replies as $reply)
                    <div class="flex flex-col {{ $reply->is_admin ? 'items-end' : 'items-start' }}">
                        <div class="flex items-center gap-2 mb-2 {{ $reply->is_admin ? 'mr-4' : 'ml-4' }}">
                            @if (!$reply->is_admin)
                                <div
                                    class="size-6 bg-gray-200 rounded-full flex items-center justify-center text-[10px] font-bold">
                                    U</div>
                                <span
                                    class="text-xs font-bold text-gray-700">{{ $reply->user->name ?? 'Usuário' }}</span>
                            @else
                                <span class="text-xs font-bold text-gray-700">Suporte</span>
                            @endif
                            <span class="text-[10px] text-gray-400">{{ $reply->created_at->format('H:i') }}</span>
                        </div>
                        @php
                            $msgColor = $reply->is_admin
                                ? 'bg-primary text-white shadow-black/10 rounded-br-sm'
                                : 'bg-white text-gray-800 border border-[#dbe6e1] rounded-bl-sm';
                        @endphp
                        <div class="max-w-[85%] p-5 rounded-3xl shadow-md break-words {{ $msgColor }}">
                            <p class="text-sm leading-relaxed whitespace-pre-wrap break-all">{{ $reply->message }}</p>

                            @if ($reply->file)
                                <div class="mt-3">
                                    <a href="{{ asset('storage/' . $reply->file->path) }}" target="_blank"
                                        class="block group/img relative overflow-hidden rounded-2xl border border-black/5">
                                        <img src="{{ asset('storage/' . $reply->file->path) }}"
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
            </div>
        </div>

        {{-- Reply form --}}
        @if ($ticket->status !== 'resolved' && $ticket->status !== 'closed')
            <div class="bg-white rounded-3xl border-2 border-primary/20 p-4 md:p-6 shadow-xl shadow-primary/5">
                <div class="flex items-center gap-2 mb-4">
                    <span class="material-symbols-outlined text-primary">reply</span>
                    <h3 class="font-bold text-[#111815]">Responder ao Usuário</h3>
                </div>
                <form wire:submit="reply">
                    <textarea wire:model="message"
                        class="w-full rounded-2xl border-[#dbe6e1] bg-gray-50 focus:ring-primary focus:border-primary text-sm min-h-[120px] mb-4 p-4 placeholder-gray-400 resize-none"
                        placeholder="Digite sua resposta técnica aqui..."></textarea>
                    @error('message')
                        <p class="text-red-500 text-xs font-bold mb-4">{{ $message }}</p>
                    @enderror

                    <div class="mb-6">
                        <label
                            class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-600 rounded-xl cursor-pointer hover:bg-gray-200 transition-colors text-xs font-bold">
                            <span class="material-symbols-outlined text-sm">image</span>
                            {{ $attachment ? 'Imagem selecionada' : 'Anexar imagem' }}:
                            <input type="file" wire:model="attachment" class="hidden" accept="image/*">
                        </label>
                        @if ($attachment)
                            <div class="mt-2 relative inline-block">
                                <img src="{{ $attachment->temporaryUrl() }}"
                                    class="size-20 rounded-xl object-cover border-2 border-black" />
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
                        <button type="submit" wire:loading.attr="disabled" wire:target="reply"
                            class="w-full md:w-auto px-8 py-3 bg-primary text-white font-bold rounded-full hover:scale-[1.02] active:scale-95 transition-transform shadow-lg shadow-black/20 disabled:opacity-50 cursor-pointer">
                            <span wire:loading.remove wire:target="reply">Enviar Resposta</span>
                            <span wire:loading wire:target="reply">Enviando...</span>
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>

    {{-- Modals --}}
    @if ($ticket->status !== 'resolved' && $ticket->status !== 'closed')
        <x-modal name="confirm-close-ticket" focusable>
            <div class="p-6">
                <h2 class="text-lg font-bold text-gray-900">
                    Finalizar Chamado
                </h2>
                <p class="mt-1 text-sm text-gray-500 font-medium">
                    Tem certeza que deseja finalizar este chamado? Esta ação não pode ser desfeita.
                </p>
                <div class="mt-8 flex justify-end gap-3">
                    <button x-on:click="$dispatch('close-modal', 'confirm-close-ticket')"
                        class="px-6 py-2.5 bg-gray-100 text-gray-600 font-bold rounded-xl hover:bg-gray-200 transition-colors cursor-pointer text-sm">
                        Cancelar
                    </button>
                    <button wire:click="closeTicket" x-on:click="$dispatch('close-modal', 'confirm-close-ticket')"
                        class="px-6 py-2.5 bg-red-500 text-white font-bold rounded-xl hover:bg-red-600 transition-colors shadow-lg shadow-red-500/20 cursor-pointer text-sm">
                        Sim, Finalizar
                    </button>
                </div>
            </div>
        </x-modal>
    @endif
</div>
