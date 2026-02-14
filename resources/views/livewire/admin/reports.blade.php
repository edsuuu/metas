<div>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-black uppercase tracking-tighter">Denúncias Pendentes</h2>
            <span class="bg-primary text-black text-xs font-black px-3 py-1 rounded-full">{{ count($reports) }}
                pendentes</span>
        </div>

        <div class="bg-white rounded-3xl border border-[#dbe6e1] shadow-sm overflow-hidden">
            <div class="divide-y divide-[#dbe6e1]">
                @forelse($reports as $report)
                    @php
                        $reasonLabel = match ($report->reason) {
                            'spam' => 'Spam / Propaganda',
                            'harassment' => 'Assédio / Ofensa',
                            'inappropriate' => 'Inadequado',
                            'hate_speech' => 'Discurso de Ódio',
                            'other' => 'Outro',
                            default => $report->reason,
                        };
                    @endphp
                    <div class="p-6">
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                            <div class="lg:col-span-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <span
                                        class="bg-orange-100 text-orange-600 text-[10px] font-black px-2 py-0.5 rounded-full uppercase">{{ $reasonLabel }}</span>
                                    <span
                                        class="text-[10px] text-gray-400 font-bold">{{ $report->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <p class="text-sm font-bold mb-1">Denunciado por: <span
                                        class="font-normal text-gray-500">{{ $report->user->name }}</span></p>
                                <p class="text-xs text-gray-500 italic">
                                    "{{ $report->details ?? 'Sem detalhes adicionais' }}"</p>
                            </div>

                            <div class="lg:col-span-5 bg-gray-50 p-4 rounded-2xl border border-dashed border-gray-200">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Conteúdo
                                    do Post (de {{ $report->post->user->name }})</p>
                                <p class="text-sm mb-3 line-clamp-3">{{ $report->post->content }}</p>
                                @if ($report->post->files && count($report->post->files) > 0)
                                    <div class="size-20 rounded-lg overflow-hidden border border-gray-200">
                                        <img src="{{ route('files.show', $report->post->files[0]->uuid) }}"
                                            class="w-full h-full object-cover" alt="Post file" />
                                    </div>
                                @endif
                            </div>

                            <div class="lg:col-span-3 flex flex-col justify-center gap-2">
                                <button wire:click="resolve({{ $report->id }}, 'resolved', true)"
                                    wire:confirm="Tem certeza que deseja apagar este post e resolver a denúncia?"
                                    class="w-full bg-red-600 hover:bg-red-700 text-white text-xs font-black py-3 rounded-xl transition-all shadow-lg shadow-red-500/20 flex items-center justify-center gap-2 cursor-pointer">
                                    <span class="material-symbols-outlined text-sm">delete</span>
                                    Apagar Post e Resolver
                                </button>
                                <button wire:click="resolve({{ $report->id }}, 'dismissed', false)"
                                    wire:confirm="Tem certeza que deseja ignorar esta denúncia?"
                                    class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-black py-3 rounded-xl transition-all flex items-center justify-center gap-2 cursor-pointer">
                                    <span class="material-symbols-outlined text-sm">close</span>
                                    Ignorar Denúncia
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-20 text-center">
                        <span class="material-symbols-outlined text-gray-200 text-6xl mb-4">check_circle</span>
                        <p class="text-gray-400 italic">Nenhuma denúncia pendente. Tudo limpo!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
