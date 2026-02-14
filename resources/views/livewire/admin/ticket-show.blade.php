<div>
    <div class="max-w-[1000px] mx-auto space-y-8">
        <div class="mb-4 flex items-center gap-4">
            <a class="flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-primary transition-colors"
                href="{{ route('admin.tickets.index') }}" wire:navigate>
                <span class="material-symbols-outlined text-lg">arrow_back</span>
                Voltar para Listagem
            </a>
        </div>

        <div class="bg-white rounded-3xl p-6 md:p-8 border border-[#dbe6e1] shadow-sm">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <span
                            class="text-xs font-bold uppercase tracking-widest text-primary bg-primary/10 px-3 py-1 rounded-full">Protocolo
                            {{ $ticket->protocol }}</span>
                        @php
                            $statusColor = match ($ticket->status) {
                                'pending' => 'bg-yellow-100 text-yellow-700',
                                'in_progress' => 'bg-blue-100 text-blue-700',
                                'resolved' => 'bg-green-100 text-green-700',
                                'closed' => 'bg-gray-100 text-gray-700',
                                default => 'bg-gray-100 text-gray-700',
                            };
                        @endphp
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $statusColor }}">{{ ucfirst($ticket->status) }}</span>
                    </div>
                    <h1 class="text-2xl md:text-3xl font-black text-[#111815] tracking-tight break-all">
                        {{ $ticket->subject }}</h1>
                    <div class="flex flex-col mt-2">
                        <p class="text-sm font-bold text-gray-700">De: {{ $ticket->name }}</p>
                        <p class="text-xs text-gray-500 break-all">{{ $ticket->email }}</p>
                        <p class="text-xs text-gray-400 mt-1">Aberto em {{ $ticket->created_at->format('d/m/Y H:i') }}
                        </p>
                    </div>
                </div>
                <div>
                    @if ($ticket->status !== 'resolved' && $ticket->status !== 'closed')
                        <button wire:click="closeTicket" wire:confirm="Tem certeza que deseja finalizar este chamado?"
                            class="w-full md:w-auto px-6 py-3 bg-gray-100 text-gray-600 font-bold rounded-full hover:bg-green-500 hover:text-white transition-all flex items-center justify-center gap-2 shadow-sm cursor-pointer">
                            <span class="material-symbols-outlined text-sm">check_circle</span>
                            Finalizar Chamado
                        </button>
                    @endif
                </div>
            </div>
        </div>

        {{-- Messages --}}
        <div class="flex-1 overflow-y-auto pr-2 max-h-[600px] mb-10 scroll-smooth">
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
                        <div
                            class="max-w-[85%] p-5 rounded-3xl shadow-md break-words {{ $reply->is_admin ? 'bg-primary text-[#111815] shadow-primary/10 rounded-br-sm' : 'bg-white text-gray-800 border border-[#dbe6e1] rounded-bl-sm' }}">
                            <p class="text-sm leading-relaxed whitespace-pre-wrap break-all">{{ $reply->message }}</p>
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
                    <div class="flex justify-end">
                        <button type="submit" wire:loading.attr="disabled"
                            class="w-full md:w-auto px-8 py-3 bg-primary text-[#111815] font-bold rounded-full hover:scale-[1.02] active:scale-95 transition-transform shadow-lg shadow-primary/20 disabled:opacity-50 cursor-pointer">
                            <span wire:loading.remove>Enviar Resposta</span>
                            <span wire:loading>Enviando...</span>
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>
</div>
