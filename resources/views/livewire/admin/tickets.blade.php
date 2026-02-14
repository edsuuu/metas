<div>
    <div class="space-y-6">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div>
                <h3 class="text-2xl font-black tracking-tight">Central de Chamados</h3>
                <p class="text-sm text-gray-500 mt-1">Atenda as solicitações de suporte e dúvidas dos usuários.</p>
            </div>

            <div class="flex flex-col sm:flex-row w-full lg:w-auto gap-3">
                <div class="relative flex-1 sm:w-80 group">
                    <span
                        class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 material-symbols-outlined text-xl group-focus-within:text-primary transition-colors">search</span>
                    <input wire:model.live.debounce.300ms="search" type="search" id="admin-protocol-search"
                        class="w-full pl-12 pr-6 h-11 rounded-2xl bg-white border border-[#dbe6e1] focus:border-primary focus:ring-0 text-xs shadow-sm transition-all outline-none"
                        placeholder="Buscar protocolo, nome ou e-mail..." />
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-[#dbe6e1] shadow-sm overflow-hidden">
            {{-- Desktop Table --}}
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-8 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Protocolo
                            </th>
                            <th class="px-8 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Assunto</th>
                            <th class="px-8 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Usuário</th>
                            <th class="px-8 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Data</th>
                            <th class="px-8 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Status</th>
                            <th class="px-8 py-4 text-right"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#dbe6e1]">
                        @foreach ($tickets as $ticket)
                            @php
                                $statusColor = match ($ticket->status) {
                                    'pending' => 'bg-yellow-100 text-yellow-700',
                                    'in_progress' => 'bg-blue-100 text-blue-700',
                                    'resolved' => 'bg-green-100 text-green-700',
                                    'closed' => 'bg-gray-100 text-gray-700',
                                    default => 'bg-gray-100 text-gray-700',
                                };
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors group">
                                <td class="px-8 py-5 text-sm font-bold text-gray-900">{{ $ticket->protocol }}</td>
                                <td class="px-8 py-5">
                                    <p class="text-sm font-bold">{{ $ticket->subject }}</p>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="text-sm font-bold text-gray-900">{{ $ticket->name }}</div>
                                    <div class="text-xs text-gray-400 break-all">{{ $ticket->email }}</div>
                                </td>
                                <td class="px-8 py-5 text-sm text-gray-500">{{ $ticket->created_at->format('d M Y') }}
                                </td>
                                <td class="px-8 py-5">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $statusColor }}">{{ ucfirst($ticket->status) }}</span>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <a href="{{ route('admin.tickets.show', $ticket->protocol) }}"
                                        class="inline-flex items-center justify-center size-9 rounded-xl bg-primary text-gray-900 shadow-sm hover:scale-110 transition-all font-bold"
                                        title="Ver detalhes" wire:navigate>
                                        <span class="material-symbols-outlined text-sm">visibility</span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Mobile Cards --}}
            <div class="lg:hidden divide-y divide-[#dbe6e1]">
                @foreach ($tickets as $ticket)
                    @php
                        $statusColor = match ($ticket->status) {
                            'pending' => 'bg-yellow-100 text-yellow-700',
                            'in_progress' => 'bg-blue-100 text-blue-700',
                            'resolved' => 'bg-green-100 text-green-700',
                            'closed' => 'bg-gray-100 text-gray-700',
                            default => 'bg-gray-100 text-gray-700',
                        };
                    @endphp
                    <div class="p-4 space-y-3">
                        <div class="flex items-center justify-between">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $statusColor }}">{{ ucfirst($ticket->status) }}</span>
                            <span class="text-xs font-bold text-gray-400">{{ $ticket->protocol }}</span>
                        </div>
                        <div>
                            <h4 class="font-black text-gray-900 text-lg mb-1">{{ $ticket->subject }}</h4>
                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                <span class="font-bold text-gray-700">{{ $ticket->name }}</span>
                                <span>•</span>
                                <span>{{ $ticket->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between pt-2">
                            <div class="text-xs text-gray-400 break-all pr-4">{{ $ticket->email }}</div>
                            <a href="{{ route('admin.tickets.show', $ticket->protocol) }}"
                                class="shrink-0 inline-flex items-center justify-center h-9 px-4 rounded-xl bg-primary text-gray-900 shadow-sm font-bold text-xs"
                                wire:navigate>
                                Ver Detalhes
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($tickets->isEmpty())
                <div class="flex flex-col items-center justify-center py-20 px-4 text-center">
                    <div class="size-16 bg-gray-50 rounded-full flex items-center justify-center text-gray-400 mb-4">
                        <span class="material-symbols-outlined text-3xl">mail_outline</span>
                    </div>
                    <h3 class="text-lg font-bold text-[#111815] mb-1">Nenhum chamado</h3>
                    <p class="text-sm text-gray-500">Não há chamados registrados no momento.</p>
                </div>
            @endif
        </div>

        <div class="p-6">
            {{ $tickets->links() }}
        </div>
    </div>
</div>
