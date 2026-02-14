<div>
    <div class="space-y-10">
        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @php
                $statCards = [
                    [
                        'title' => 'Total Usuários',
                        'value' => $stats['total_users'],
                        'icon' => 'person',
                        'color' => 'blue',
                    ],
                    [
                        'title' => 'Equipe Suporte',
                        'value' => $stats['total_supports'],
                        'icon' => 'support_agent',
                        'color' => 'purple',
                    ],
                    [
                        'title' => 'Tickets Pendentes',
                        'value' => $stats['pending_tickets'],
                        'icon' => 'pending',
                        'color' => 'orange',
                    ],
                    [
                        'title' => 'Tickets Ativos',
                        'value' => $stats['active_tickets'],
                        'icon' => 'forum',
                        'color' => 'green',
                    ],
                ];
            @endphp

            @foreach ($statCards as $card)
                <div class="bg-white p-8 rounded-3xl border border-[#dbe6e1] shadow-sm transition-all hover:shadow-md">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="size-12 rounded-2xl flex items-center justify-center text-{{ $card['color'] }}-500 bg-{{ $card['color'] }}-50">
                            <span class="material-symbols-outlined font-bold">{{ $card['icon'] }}</span>
                        </div>
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Resumo</span>
                    </div>
                    <h3 class="text-gray-500 text-sm font-bold">{{ $card['title'] }}</h3>
                    <p class="text-3xl font-black mt-1 uppercase tracking-tighter">{{ $card['value'] }}</p>
                </div>
            @endforeach
        </div>

        {{-- Recent Security Activity --}}
        <div class="bg-white rounded-3xl border border-[#dbe6e1] shadow-sm overflow-hidden">
            <div class="p-8 border-b border-[#dbe6e1] flex items-center justify-between bg-gray-50/30">
                <h3 class="text-lg font-black flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary">security</span>
                    Atividade Recente de Segurança
                </h3>
                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Últimos 10 eventos</span>
            </div>

            <div class="divide-y divide-[#dbe6e1]">
                @forelse($recentActivity as $activity)
                    @php
                        $config = match ($activity['type']) {
                            'login' => [
                                'icon' => 'login',
                                'color' => 'text-green-500',
                                'bg' => 'bg-green-50',
                                'label' => 'Login realizado',
                            ],
                            'logout' => [
                                'icon' => 'logout',
                                'color' => 'text-gray-500',
                                'bg' => 'bg-gray-50',
                                'label' => 'Logout realizado',
                            ],
                            'login_failed' => [
                                'icon' => 'error',
                                'color' => 'text-orange-500',
                                'bg' => 'bg-orange-50',
                                'label' => 'Falha de autenticação',
                            ],
                            'lockout' => [
                                'icon' => 'lock',
                                'color' => 'text-red-500',
                                'bg' => 'bg-red-50',
                                'label' => 'Bloqueio de IP (Brute Force)',
                            ],
                            'unauthorized_access' => [
                                'icon' => 'security_update_warning',
                                'color' => 'text-red-600',
                                'bg' => 'bg-red-50',
                                'label' => 'Acesso negado ao Admin',
                            ],
                            default => [
                                'icon' => 'info',
                                'color' => 'text-blue-500',
                                'bg' => 'bg-blue-50',
                                'label' => 'Evento de sistema',
                            ],
                        };
                    @endphp
                    <div class="p-5 flex items-center justify-between hover:bg-gray-50 transition-colors group">
                        <div class="flex items-center gap-4">
                            <div
                                class="size-10 rounded-xl flex items-center justify-center {{ $config['bg'] }} {{ $config['color'] }}">
                                <span class="material-symbols-outlined text-xl">{{ $config['icon'] }}</span>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <p class="text-sm font-bold">{{ $config['label'] }}</p>
                                    <span
                                        class="text-[10px] bg-gray-200 px-1.5 py-0.5 rounded text-gray-500 font-bold tracking-tight">{{ $activity['ip'] }}</span>
                                </div>
                                <p class="text-xs text-gray-500">
                                    {{ $activity['user_name'] }} •
                                    {{ $activity['type'] === 'unauthorized_access' ? $activity['details']['url'] ?? 'N/A' : $activity['details']['email'] ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                {{ $activity['created_at_time'] }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-20 text-gray-400 italic text-sm">
                        Nenhuma atividade de segurança registrada até o momento.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
