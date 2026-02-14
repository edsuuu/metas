<div class="max-w-[1200px] mx-auto px-4 md:px-10 py-12">
    <div class="mb-8">
        <a href="{{ route('goals.index') }}"
            class="text-gray-500 hover:text-primary transition-colors flex items-center gap-2 text-sm font-bold"
            wire:navigate>
            <span class="material-symbols-outlined text-lg">arrow_back</span>
            Voltar para minhas metas
        </a>
    </div>

    @php
        $styles = [
            'saude' => [
                'icon' => 'fitness_center',
                'color' => 'text-green-500',
                'bg' => 'bg-green-100',
                'bar' => 'bg-green-500',
            ],
            'financeiro' => [
                'icon' => 'payments',
                'color' => 'text-blue-500',
                'bg' => 'bg-blue-100',
                'bar' => 'bg-blue-500',
            ],
            'carreira' => [
                'icon' => 'rocket_launch',
                'color' => 'text-purple-500',
                'bg' => 'bg-purple-100',
                'bar' => 'bg-purple-500',
            ],
            'pessoal' => [
                'icon' => 'psychology',
                'color' => 'text-orange-500',
                'bg' => 'bg-orange-100',
                'bar' => 'bg-orange-500',
            ],
        ][$goal->category] ?? [
            'icon' => 'flag',
            'color' => 'text-primary',
            'bg' => 'bg-primary/10',
            'bar' => 'bg-primary',
        ];

        $totalTasks = $goal->microTasks->count();
        $completedTasks = $goal->microTasks->where('is_completed', true)->count();
        $progress = $totalTasks > 0 ? (int) round(($completedTasks / $totalTasks) * 100) : 0;
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-3xl p-8 border border-[#dbe6e1] shadow-sm relative overflow-hidden">
                <div class="flex items-center gap-6 mb-8 relative z-10">
                    <div
                        class="size-16 rounded-2xl {{ $styles['bg'] }} flex items-center justify-center {{ $styles['color'] }} shadow-lg shadow-black/5">
                        <span class="material-symbols-outlined !text-[32px]">{{ $styles['icon'] }}</span>
                    </div>
                    <div>
                        <h1 class="text-3xl font-black text-[#111815] capitalize leading-tight">{{ $goal->title }}</h1>
                        <div class="flex items-center gap-3 mt-1">
                            <span
                                class="text-xs font-bold uppercase tracking-widest {{ $styles['color'] }}">{{ $goal->category }}</span>
                            <span class="size-1 bg-gray-300 rounded-full"></span>
                            <span
                                class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ $goal->status === 'active' ? 'Ativa' : $goal->status }}</span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 relative z-10">
                    @if ($goal->deadline)
                        <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">Prazo Final</p>
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary">calendar_today</span>
                                <span
                                    class="text-sm font-bold text-[#111815]">{{ $goal->deadline->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    @endif
                    @if ($goal->target_value)
                        <div class="p-4 bg-blue-50/50 rounded-2xl border border-blue-100">
                            <p class="text-[10px] text-blue-400 font-bold uppercase tracking-wider mb-1">Valor Alvo</p>
                            <div class="flex items-center gap-2 text-blue-600">
                                <span class="material-symbols-outlined">payments</span>
                                <span class="text-sm font-black">R$
                                    {{ number_format($goal->target_value, 2, ',', '.') }}</span>
                            </div>
                        </div>
                    @endif
                    @if ($goal->is_streak_enabled)
                        <div class="p-4 bg-orange-50/50 rounded-2xl border border-orange-100">
                            <p class="text-[10px] text-orange-400 font-bold uppercase tracking-wider mb-1">Ofensiva
                                Atual
                            </p>
                            <div class="flex items-center gap-2 text-orange-500 text-lg">
                                <span class="material-symbols-outlined !text-2xl"
                                    style="font-variation-settings: 'FILL' 1">local_fire_department</span>
                                <span class="font-black">{{ $goal->current_streak }} dias</span>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Abstract Background Decoration -->
                <div
                    class="absolute top-0 right-0 w-64 h-64 {{ $styles['bg'] }} rounded-full blur-3xl opacity-20 translate-x-1/2 -translate-y-1/2">
                </div>
            </div>

            <!-- Checklist Section -->
            <div class="bg-white rounded-3xl p-8 border border-[#dbe6e1] shadow-sm">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-xl font-bold">Checklist de Passos</h3>
                        <p class="text-sm text-gray-500">Defina os marcos para atingir seu objetivo</p>
                    </div>
                    @if ($totalTasks > 0)
                        <div class="text-right">
                            <p class="text-2xl font-black">{{ $progress }}%</p>
                            <p class="text-xs text-gray-400 font-bold uppercase">Concluído</p>
                        </div>
                    @endif
                </div>

                @if ($totalTasks > 0)
                    <div class="w-full h-3 bg-gray-100 rounded-full mb-8 overflow-hidden relative">
                        <div class="h-full {{ $styles['bar'] }} rounded-full transition-all duration-1000 ease-out shadow-lg"
                            style="width: {{ $progress }}%"></div>
                    </div>
                @endif

                <div class="space-y-4">
                    @forelse ($goal->microTasks as $task)
                        <div class="flex items-center gap-4 p-5 bg-gray-50 rounded-2xl border border-transparent hover:border-primary/30 transition-all cursor-pointer group"
                            wire:click="toggleMicroTask({{ $task->id }})">
                            <input type="checkbox" @checked($task->is_completed)
                                class="size-6 rounded-lg {{ str_replace('text-', 'text-', $styles['color']) }} border-gray-300 focus:ring-primary transition-all group-hover:scale-110 pointer-events-none" />
                            <div class="flex-1">
                                <p
                                    class="font-bold transition-all {{ $task->is_completed ? 'text-gray-400 line-through' : 'text-[#111815]' }}">
                                    {{ $task->title }}
                                </p>
                            </div>
                            <span
                                class="material-symbols-outlined text-gray-300 group-hover:text-primary transition-colors">chevron_right</span>
                        </div>
                    @empty
                        <div class="text-center py-10 text-gray-400">
                            <p class="text-sm">Nenhuma tarefa cadastrada para esta meta.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar / Streak Progress -->
        <div class="space-y-8">
            @if ($goal->is_streak_enabled)
                <div
                    class="bg-[#111815] rounded-3xl p-8 text-white relative overflow-hidden shadow-2xl shadow-orange-500/10">
                    <div class="relative z-10 text-center">
                        <div
                            class="size-24 rounded-full bg-gradient-to-tr from-orange-600 to-yellow-500 mx-auto flex items-center justify-center mb-6 shadow-xl shadow-orange-500/40 animate-pulse">
                            <span class="material-symbols-outlined !text-[48px]"
                                style="font-variation-settings: 'FILL' 1">local_fire_department</span>
                        </div>
                        <h3 class="text-2xl font-black mb-2">Ofensiva: {{ $goal->current_streak }} Dias</h3>
                        <p class="text-orange-200/60 text-sm font-medium mb-8">Mantenha o foco diário para não quebrar
                            sua sequência!</p>

                        @if ($canCompleteStreak)
                            <button wire:click="confirmStreak" wire:loading.attr="disabled"
                                class="w-full h-14 bg-white text-[#111815] rounded-2xl font-black text-lg hover:bg-orange-50 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-3 shadow-xl">
                                <span class="material-symbols-outlined">check_circle</span>
                                Confirmar Hoje!
                            </button>
                        @else
                            <div
                                class="w-full h-14 bg-white/10 rounded-2xl flex items-center justify-center gap-3 text-white/50 border border-white/5">
                                <span class="material-symbols-outlined text-green-500">verified</span>
                                <span class="font-bold">Concluído Hoje</span>
                            </div>
                        @endif
                    </div>
                    <div class="absolute top-0 right-0 w-40 h-40 bg-orange-500/20 rounded-full blur-[80px]"></div>
                </div>
            @endif

            @if ($goal->deadline)
                <div
                    class="bg-[#111815] rounded-3xl p-8 text-white relative overflow-hidden shadow-2xl shadow-blue-500/10">
                    <div class="relative z-10 text-center">
                        <div
                            class="size-24 rounded-full bg-gradient-to-tr from-blue-600 to-cyan-500 mx-auto flex items-center justify-center mb-6 shadow-xl shadow-blue-500/40">
                            <span class="material-symbols-outlined !text-[48px]">flag</span>
                        </div>
                        <h3 class="text-2xl font-black mb-2">
                            {{ $goal->status === 'completed' ? 'Meta Concluída!' : 'Meta com Prazo' }}</h3>
                        <p class="text-blue-200/60 text-sm font-medium mb-8">
                            @if ($goal->status === 'completed')
                                Parabéns! Você alcançou seu objetivo.
                            @else
                                Prazo final: {{ $goal->deadline->format('d/m/Y') }}
                            @endif
                        </p>

                        @if ($goal->status !== 'completed')
                            <button wire:click="completeGoal" wire:loading.attr="disabled"
                                class="w-full h-14 bg-white text-[#111815] rounded-2xl font-black text-lg hover:bg-blue-50 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-3 shadow-xl">
                                <span class="material-symbols-outlined">check_circle</span>
                                Concluir Meta
                            </button>
                        @else
                            <div
                                class="w-full h-14 bg-white/10 rounded-2xl flex items-center justify-center gap-3 text-white/50 border border-white/5">
                                <span class="material-symbols-outlined text-green-500">verified</span>
                                <span class="font-bold">Concluída em
                                    {{ $goal->completed_at ? $goal->completed_at->format('d/m/Y') : '' }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="absolute top-0 right-0 w-40 h-40 bg-blue-500/20 rounded-full blur-[80px]"></div>
                </div>
            @endif

            <div class="bg-white rounded-3xl p-8 border border-[#dbe6e1] shadow-sm">
                <h4 class="font-bold mb-6">Informações</h4>
                <div class="space-y-4">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-400 font-medium">Criada em</span>
                        <span class="font-bold text-[#111815]">{{ $goal->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-400 font-medium">Última atualização</span>
                        <span class="font-bold text-[#111815]">{{ $goal->updated_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-400 font-medium">Categoria</span>
                        <span
                            class="px-3 py-1 {{ $styles['bg'] }} {{ $styles['color'] }} rounded-full font-bold text-[10px] uppercase tracking-wider">
                            {{ $goal->category }}
                        </span>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 space-y-3">
                    <a href="{{ route('goals.edit', $goal->uuid) }}"
                        class="w-full h-12 rounded-xl text-sm font-bold text-gray-500 hover:bg-gray-50 transition-colors flex items-center justify-center gap-2"
                        wire:navigate>
                        <span class="material-symbols-outlined text-lg">edit</span>
                        Editar Meta
                    </a>

                    @if (!($goal->deadline && $goal->status === 'completed'))
                        <button wire:click="$set('showDeactivateModal', true)"
                            class="w-full h-12 rounded-xl text-sm font-bold text-orange-600 hover:bg-orange-50 transition-colors flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-lg">archive</span>
                            Desativar Meta
                        </button>
                        <button wire:click="$set('showDeleteModal', true)"
                            class="w-full h-12 rounded-xl text-sm font-bold text-red-500 hover:bg-red-50 transition-colors flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-lg">delete</span>
                            Excluir Meta
                        </button>
                    @endif

                    @if ($goal->deadline && $goal->status === 'completed')
                        <div class="p-4 bg-gray-50 rounded-xl text-center">
                            <p class="text-xs text-gray-400">Esta meta foi concluída e arquivada no seu histórico de
                                conquistas.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Deactivate Modal -->
    <x-modal wire:model="showDeactivateModal">
        <div class="p-6">
            <h2 class="text-xl font-bold mb-4">Desativar Meta</h2>
            <p class="text-gray-500 mb-6">
                Tem certeza que deseja desativar esta meta? Sua ofensiva (streak) atual será resetada.
            </p>
            <div class="flex justify-end gap-3">
                <button @click="$dispatch('close')"
                    class="px-4 py-2 text-sm font-bold text-gray-500 hover:bg-gray-100 rounded-lg transition-colors">
                    Cancelar
                </button>
                <button wire:click="deactivateGoal" wire:loading.attr="disabled"
                    class="px-4 py-2 text-sm font-bold text-white bg-orange-500 hover:bg-orange-600 rounded-lg transition-colors">
                    Confirmar Desativação
                </button>
            </div>
        </div>
    </x-modal>

    <!-- Delete Modal -->
    <x-modal wire:model="showDeleteModal">
        <div class="p-6">
            <h2 class="text-xl font-bold mb-4 text-red-600">Excluir Meta</h2>
            <p class="text-gray-500 mb-2">
                Tem certeza que deseja excluir esta meta permanentemente?
            </p>
            <p class="text-sm text-red-500/80 mb-6 bg-red-50 p-3 rounded-lg border border-red-100">
                <span class="font-bold block mb-1">Atenção:</span>
                Todo o histórico de conquistas, tarefas e XP associados a esta meta serão removidos da sua conta. Esta
                ação não pode ser desfeita.
            </p>
            <div class="flex justify-end gap-3">
                <button @click="$dispatch('close')"
                    class="px-4 py-2 text-sm font-bold text-gray-500 hover:bg-gray-100 rounded-lg transition-colors">
                    Cancelar
                </button>
                <button wire:click="deleteGoal" wire:loading.attr="disabled"
                    class="px-4 py-2 text-sm font-bold text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors">
                    Excluir Permanentemente
                </button>
            </div>
        </div>
    </x-modal>
</div>
