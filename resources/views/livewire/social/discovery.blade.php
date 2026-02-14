<div class="max-w-[1200px] mx-auto px-4 md:px-10 py-10">
    <div class="max-w-3xl mx-auto space-y-12">
        <section class="text-center space-y-6">
            <h1 class="text-3xl md:text-4xl font-black text-[#111815]">Expandir Círculo</h1>
            <p class="text-gray-500">Encontre novos parceiros de jornada para subir a montanha juntos.</p>

            <div
                class="relative max-w-2xl mx-auto bg-white rounded-2xl shadow-sm border border-[#dbe6e1] p-2 transition-all focus-within:ring-2 focus-within:ring-primary focus-within:ring-opacity-50">
                <div class="flex items-center px-4">
                    <span class="material-symbols-outlined text-gray-400">search</span>
                    <input wire:model.live.debounce.300ms="search"
                        class="w-full border-none focus:ring-0 bg-transparent text-[#111815] py-3 px-3 text-lg font-medium"
                        placeholder="Buscar amigos pelo nickname ou e-mail" type="text" />
                </div>
            </div>
        </section>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <div class="bg-gray-100 p-1 rounded-full flex w-full max-w-md border border-gray-200">
                <button wire:click="$set('tab', 'discovery')"
                    class="flex-1 py-2.5 px-4 font-bold rounded-full transition-all {{ $tab === 'discovery' ? 'bg-white text-[#111815] shadow-sm' : 'text-gray-500 hover:text-primary' }}">
                    Descobrir
                </button>
                <button wire:click="$set('tab', 'sent')"
                    class="flex-1 py-2.5 px-4 font-bold rounded-full transition-all {{ $tab === 'sent' ? 'bg-white text-[#111815] shadow-sm' : 'text-gray-500 hover:text-primary' }}">
                    Enviados ({{ $pendingSent->count() }})
                </button>
                <button wire:click="$set('tab', 'received')"
                    class="flex-1 py-2.5 px-4 font-bold rounded-full transition-all relative {{ $tab === 'received' ? 'bg-white text-[#111815] shadow-sm' : 'text-gray-500 hover:text-primary' }}">
                    Recebidos ({{ $pendingReceived->count() }})
                    @if ($pendingReceived->count() > 0)
                        <span class="absolute top-2 right-2 size-2 bg-secondary rounded-full"></span>
                    @endif
                </button>
            </div>
        </div>

        <section class="space-y-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold flex items-center gap-2">
                    @if ($tab === 'discovery')
                        Sugestões para você
                    @elseif($tab === 'sent')
                        Pedidos Enviados
                    @else
                        Pedidos Recebidos
                    @endif

                    @if ($tab === 'discovery')
                        <span
                            class="px-2 py-0.5 bg-primary/10 text-primary text-[10px] rounded uppercase tracking-wider">Baseado
                            no seu XP</span>
                    @endif
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @if ($tab === 'discovery')
                    @foreach ($users as $user)
                        <div
                            class="bg-white p-5 rounded-3xl border border-[#dbe6e1] flex items-center gap-4 transition-all hover:-translate-y-1">
                            <a href="{{ route('social.profile', $user->nickname ?: $user->id) }}"
                                class="flex-1 flex items-center gap-4 group" wire:navigate>
                                <div class="relative shrink-0">
                                    <div
                                        class="size-16 rounded-2xl overflow-hidden bg-gray-100 group-hover:scale-105 transition-transform">
                                        <img alt="{{ $user->name }}" class="w-full h-full object-cover"
                                            src="{{ $user->avatar_url ?: 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}" />
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-bold group-hover:underline">{{ $user->name }}</h3>
                                    <p class="text-xs text-gray-500 font-medium">
                                        @ {{ $user->nickname ?: str_replace(' ', '_', strtolower($user->name)) }}</p>
                                </div>
                            </a>
                            <button wire:click="sendRequest({{ $user->id }})" wire:loading.attr="disabled"
                                class="px-4 py-2 bg-primary text-[#111815] font-bold text-sm rounded-xl hover:scale-105 transition-transform">
                                Adicionar
                            </button>
                        </div>
                    @endforeach
                @elseif($tab === 'sent')
                    @foreach ($pendingSent as $f)
                        <div
                            class="bg-white p-5 rounded-3xl border border-[#dbe6e1] flex items-center gap-4 opacity-75">
                            <a href="{{ route('social.profile', $f->friend->nickname ?: $f->friend->id) }}"
                                class="flex-1 flex items-center gap-4 group" wire:navigate>
                                <div
                                    class="size-16 rounded-2xl overflow-hidden bg-gray-100 group-hover:scale-105 transition-transform shrink-0">
                                    <img alt="{{ $f->friend->name }}" class="w-full h-full object-cover"
                                        src="{{ $f->friend->avatar_url ?: 'https://ui-avatars.com/api/?name=' . urlencode($f->friend->name) }}" />
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-bold group-hover:underline">{{ $f->friend->name }}</h3>
                                    <p class="text-xs text-gray-500">Aguardando aceitação...</p>
                                </div>
                            </a>
                        </div>
                    @endforeach
                @elseif($tab === 'received')
                    @foreach ($pendingReceived as $f)
                        <div class="bg-white p-5 rounded-3xl border border-[#dbe6e1] flex items-center gap-4">
                            <a href="{{ route('social.profile', $f->user->nickname ?: $f->user->id) }}"
                                class="flex-1 flex items-center gap-4 group" wire:navigate>
                                <div
                                    class="size-16 rounded-2xl overflow-hidden bg-gray-100 group-hover:scale-105 transition-transform shrink-0">
                                    <img alt="{{ $f->user->name }}" class="w-full h-full object-cover"
                                        src="{{ $f->user->avatar_url ?: 'https://ui-avatars.com/api/?name=' . urlencode($f->user->name) }}" />
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-bold group-hover:underline">{{ $f->user->name }}</h3>
                                    <p class="text-xs text-gray-500">Quer ser seu amigo</p>
                                </div>
                            </a>
                            <div class="flex gap-2">
                                <button wire:click="acceptRequest({{ $f->id }})"
                                    class="size-10 flex items-center justify-center bg-primary text-black rounded-full hover:scale-110 transition-all shadow-lg shadow-primary/20"
                                    title="Aceitar">
                                    <span class="material-symbols-outlined">check</span>
                                </button>
                                <button wire:click="declineRequest({{ $f->id }})"
                                    class="size-10 flex items-center justify-center bg-red-100 text-red-500 rounded-full hover:scale-110 transition-all hover:bg-red-200"
                                    title="Recusar">
                                    <span class="material-symbols-outlined">close</span>
                                </button>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            @if ($tab === 'discovery' && $users->isEmpty())
                <div class="text-center py-10">
                    <p class="text-gray-500">Nenhum usuário encontrado.</p>
                </div>
            @elseif($tab === 'sent' && $pendingSent->isEmpty())
                <div class="text-center py-10">
                    <p class="text-gray-500">Nenhum pedido enviado.</p>
                </div>
            @elseif($tab === 'received' && $pendingReceived->isEmpty())
                <div class="text-center py-10">
                    <p class="text-gray-500">Nenhum pedido recebido.</p>
                </div>
            @endif
        </section>

        @if (!$hasReceivedBonus)
            <section
                class="bg-gradient-to-r from-primary/20 to-blue-500/20 rounded-3xl p-8 border border-primary/20 relative overflow-hidden">
                <div class="absolute right-[-20px] top-[-20px] opacity-10">
                    <span class="material-symbols-outlined text-[160px]">group</span>
                </div>
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div>
                        <h2 class="text-2xl font-black mb-2 text-[#111815]">Subir é melhor acompanhado!</h2>
                        <p class="text-gray-600 max-w-[480px]">Usuários que adicionam pelo menos 3 amigos têm 60% mais
                            chances de manter sua ofensiva por mais de 30 dias.</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-sm font-bold text-primary">+200 XP Bônus</span>
                    </div>
                </div>
            </section>
        @endif
    </div>

    <section class="mt-16 text-center space-y-8 py-12 border-t border-[#dbe6e1]">
        <div class="inline-block p-4 rounded-full bg-primary/10 text-primary mb-4">
            <span class="material-symbols-outlined text-5xl">share</span>
        </div>
        <h2 class="text-4xl font-black leading-tight">Não encontrou quem procurava? <br /><span
                class="text-primary italic">Convide por link!</span></h2>
        <div class="flex justify-center gap-4">
            <button
                onclick="navigator.clipboard.writeText('{{ route('social.profile', Auth::user()->nickname ?: Auth::user()->id) }}'); window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Link copiado para a área de transferência!', type: 'success' } }));"
                class="px-8 py-4 bg-primary text-[#111815] font-bold rounded-full shadow-lg shadow-primary/20 hover:scale-105 transition-all flex items-center gap-2">
                <span class="material-symbols-outlined">link</span>
                Copiar Link de Convite
            </button>
        </div>
    </section>
</div>
