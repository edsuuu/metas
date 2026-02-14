<div class="bg-gray-50 text-[#111815] transition-colors duration-300 min-h-screen flex flex-col font-display">
    <main class="flex-1">
        <section class="pt-20 pb-8 px-4" style="background: radial-gradient(circle at top right, rgba(19, 236, 146, 0.15), transparent), radial-gradient(circle at bottom left, rgba(19, 236, 146, 0.05), transparent)">
            <div class="max-w-[800px] mx-auto text-center">
                <h1 class="text-4xl md:text-5xl font-black text-[#111815] mb-4 tracking-tight">
                    Acompanhe seus chamados
                </h1>
                <p class="text-lg text-gray-600 mb-6">
                    @if($is_verified)
                        Mostrando chamados associados a {{ $email }}
                    @else
                        Digite seu e-mail para ver o status de suas solicitações
                    @endif
                </p>

                @if(!$is_verified)
                    <div class="max-w-xl mx-auto">
                        <form wire:submit="submit" class="flex flex-col md:flex-row gap-4">
                            <div class="relative flex-1 group">
                                <span class="absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 material-symbols-outlined text-xl group-focus-within:text-primary transition-colors">mail</span>
                                <input class="w-full pl-14 pr-6 h-14 rounded-full bg-white border-2 border-[#dbe6e1] focus:border-primary focus:ring-0 text-base shadow-xl shadow-primary/5 transition-all outline-none" placeholder="seu@email.com" required type="email" wire:model="email" />
                            </div>
                            <button class="px-8 h-14 rounded-full bg-primary text-[#111815] text-sm font-bold shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-transform whitespace-nowrap disabled:opacity-50" type="submit">
                                <span wire:loading.remove>Buscar Chamados</span>
                                <span wire:loading>Enviando...</span>
                            </button>
                        </form>
                        @error('email') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                    </div>
                @endif
            </div>
        </section>

        @if($is_verified)
            <section class="max-w-[1000px] mx-auto px-4 py-8">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                    <div class="flex flex-1 max-w-md gap-3">
                        <div class="relative flex-1 group">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 material-symbols-outlined text-xl group-focus-within:text-primary transition-colors">search</span>
                            <input wire:model.live.debounce.300ms="search" class="w-full pl-12 pr-6 h-12 rounded-2xl bg-white border-2 border-[#dbe6e1] focus:border-primary focus:ring-0 text-sm shadow-sm transition-all outline-none" placeholder="Buscar por protocolo..." type="text" />
                        </div>
                    </div>
                </div>

                @if(count($tickets) > 0)
                    <div class="bg-white rounded-3xl border border-[#dbe6e1] shadow-sm overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Protocolo</th>
                                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Assunto</th>
                                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Data</th>
                                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-4 text-right"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[#dbe6e1]">
                                    @foreach($tickets as $ticket)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-5 text-sm font-medium text-gray-900">{{ $ticket['protocol'] }}</td>
                                            <td class="px-6 py-5 text-sm text-gray-600">{{ $ticket['subject'] }}</td>
                                            <td class="px-6 py-5 text-sm text-gray-500">{{ $ticket['created_at_formatted'] }}</td>
                                            <td class="px-6 py-5 text-sm">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $ticket['status_color'] }}">
                                                    {{ $ticket['status_label'] }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-5 text-right">
                                                <a href="{{ route('support.ticket.show', $ticket['protocol']) }}" class="inline-flex items-center justify-center size-9 rounded-xl bg-primary text-[#111815] shadow-sm hover:scale-110 transition-all font-bold group" title="Ver detalhes">
                                                    <span class="material-symbols-outlined text-sm">visibility</span>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-20 px-4 text-center">
                        <div class="size-16 bg-gray-50 rounded-full flex items-center justify-center text-gray-400 mb-4">
                            <span class="material-symbols-outlined text-3xl">search_off</span>
                        </div>
                        <h3 class="text-lg font-bold text-[#111815] mb-1">Nenhum chamado encontrado</h3>
                        <p class="text-sm text-gray-500">Não encontramos chamados associados a este e-mail.</p>
                    </div>
                @endif

                <p class="text-center mt-8 text-sm text-gray-500">
                    Precisa de um novo atendimento?
                    <a class="text-primary font-bold hover:underline ml-1" href="{{ route('support.index') }}">Abra um novo chamado</a>
                </p>
            </section>
        @endif
    </main>
</div>
