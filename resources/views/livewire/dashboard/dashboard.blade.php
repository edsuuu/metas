<div class="max-w-[1440px] mx-auto px-4 py-8">
    {{-- XP Display Section --}}
    <section class="mb-6">
        <div class="bg-gray-50 rounded-2xl p-4 flex flex-col md:flex-row items-center gap-4 border border-[#dbe6e1]">
            <div class="flex items-center gap-3 min-w-fit">
                <div class="size-12 rounded-xl bg-gray-900 flex items-center justify-center text-white shadow-sm">
                    <span class="text-xs font-black">Lvl {{ $level }}</span>
                </div>
            </div>
            <div class="flex-1 w-full space-y-1.5">
                <div class="flex justify-between items-end">
                    <span class="text-[11px] font-extrabold text-gray-500">{{ $levelXp }} /
                        {{ $levelTarget }} XP</span>
                    <span class="text-[11px] font-black text-primary">Nível {{ $level + 1 }} em
                        {{ $levelTarget - $levelXp }} XP</span>
                </div>
                <div class="w-full h-2.5 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-primary to-green-400 rounded-full shadow-[0_0_10px_rgba(19,236,146,0.3)] transition-all duration-1000"
                        style="width: {{ $progressPercentage }}%"></div>
                </div>
            </div>
        </div>
    </section>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Active Goals Section --}}
        <div class="lg:col-span-2 order-2 lg:order-1 space-y-8">
            <section class="space-y-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <h2 class="text-2xl font-bold shrink-0">Metas Ativas</h2>

                    <div class="flex flex-1 items-center gap-3">
                        <div class="relative flex-1 group">
                            <span
                                class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-primary transition-colors z-10 pointer-events-none">search</span>
                            <x-text-input wire:model.live.debounce.300ms="search" type="text"
                                placeholder="Pesquisar metas..." class="pl-12 bg-white/50 backdrop-blur-md" />
                        </div>
                        <a href="{{ route('goals.form') }}"
                            class="bg-primary hover:bg-primary/90 text-gray-900 h-11 px-5 rounded-2xl font-black text-sm transition-all shadow-md shadow-primary/20 flex items-center gap-2 shrink-0"
                            wire:navigate>
                            <span class="material-symbols-outlined text-lg">add_circle</span>
                            Nova
                        </a>
                    </div>
                </div>

                @if ($activeGoals->isNotEmpty())
                    <div class="columns-1 md:columns-2 gap-4">
                        @foreach ($activeGoals as $goal)
                            <div x-data="{ expanded: false }"
                                class="break-inside-avoid mb-4 bg-white rounded-3xl p-6 border transition-all hover:shadow-md {{ $goal->is_streak_enabled ? 'border-orange-200 bg-gradient-to-br from-white to-orange-50/30' : 'border-[#dbe6e1] shadow-sm' }}">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="size-12 rounded-xl {{ $goal->styles['bg'] }} flex items-center justify-center {{ $goal->styles['color'] }}">
                                            <span class="material-symbols-outlined">{{ $goal->styles['icon'] }}</span>
                                        </div>
                                        <a href="{{ route('goals.show', $goal->uuid) }}" class="group" wire:navigate>
                                            <h4 class="font-bold capitalize group-hover:text-primary transition-colors">
                                                {{ $goal->title }}</h4>
                                            <p class="text-xs text-gray-500 capitalize">{{ $goal->category }}</p>
                                        </a>
                                    </div>
                                    <div class="text-right">
                                        @if ($goal->total_tasks > 0)
                                            <span
                                                class="text-sm font-black {{ $goal->styles['color'] }}">{{ $goal->progress }}%</span>
                                        @endif
                                        @if ($goal->is_streak_enabled)
                                            <div
                                                class="flex items-center justify-end gap-1.5 text-lg font-black text-orange-500 mt-1">
                                                <span class="material-symbols-outlined !text-2xl"
                                                    style="font-variation-settings: 'FILL' 1">local_fire_department</span>
                                                {{ $goal->current_streak }}
                                                {{ $goal->current_streak <= 1 ? 'dia' : 'dias' }}
                                            </div>
                                        @endif
                                        @if ($goal->deadline)
                                            <div
                                                class="flex items-center justify-end gap-1.5 text-xs font-bold text-gray-400 mt-1 group-hover:text-blue-500 transition-colors">
                                                <span class="material-symbols-outlined text-sm">event</span>
                                                {{ $goal->deadline->format('d/m/Y') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                @if ($goal->total_tasks > 0)
                                    <div
                                        class="w-full h-2.5 bg-gray-200/60 rounded-full mb-6 relative overflow-hidden border border-gray-100">
                                        <div class="h-full {{ $goal->styles['bar'] }} rounded-full transition-all duration-1000 ease-out shadow-[0_0_10px_rgba(0,0,0,0.1)]"
                                            style="width: {{ $goal->progress }}%"></div>
                                    </div>
                                @endif

                                <div class="space-y-3">
                                    @foreach ($goal->microTasks->take(3) as $task)
                                        <div class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded-lg cursor-pointer transition-colors"
                                            wire:click="toggleMicroTask({{ $task->id }})">
                                            <input type="checkbox" @checked($task->is_completed)
                                                class="rounded border-gray-300 {{ str_replace('text-', 'text-', $goal->styles['color']) }} focus:ring-current pointer-events-none" />
                                            <span
                                                class="text-sm text-gray-600 {{ $task->is_completed ? 'line-through opacity-60' : '' }}">
                                                {{ $task->title }}
                                            </span>
                                        </div>
                                    @endforeach

                                    <div x-show="expanded" x-collapse>
                                        @foreach ($goal->microTasks->skip(3) as $task)
                                            <div class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded-lg cursor-pointer transition-colors"
                                                wire:click="toggleMicroTask({{ $task->id }})">
                                                <input type="checkbox" @checked($task->is_completed)
                                                    class="rounded border-gray-300 {{ str_replace('text-', 'text-', $goal->styles['color']) }} focus:ring-current pointer-events-none" />
                                                <span
                                                    class="text-sm text-gray-600 {{ $task->is_completed ? 'line-through opacity-60' : '' }}">
                                                    {{ $task->title }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>

                                    @if ($goal->total_tasks > 3)
                                        <button @click="expanded = !expanded"
                                            class="w-full mt-2 py-2 px-3 flex items-center justify-between text-[11px] font-bold text-gray-400 hover:text-primary hover:bg-primary/5 rounded-lg transition-all border border-transparent hover:border-primary/10">
                                            <span
                                                x-text="expanded ? 'Ver menos' : '+ {{ $goal->total_tasks - 3 }} outras tarefas'"></span>
                                            <span class="material-symbols-outlined text-sm transition-transform"
                                                :class="expanded ? 'rotate-180' : ''">
                                                keyboard_arrow_down
                                            </span>
                                        </button>
                                    @endif
                                </div>

                                @if ($goal->is_streak_enabled)
                                    <div class="mt-6 pt-4 border-t border-gray-50">
                                        @if ($goal->can_complete_streak)
                                            <button wire:click="confirmStreak({{ $goal->id }})"
                                                class="w-full h-10 bg-orange-500 hover:bg-orange-600 text-white rounded-xl text-xs font-bold flex items-center justify-center gap-2 transition-all shadow-lg shadow-orange-500/20">
                                                <span class="material-symbols-outlined text-sm"
                                                    style="font-variation-settings: 'FILL' 1">local_fire_department</span>
                                                Confirmar Ofensiva Hoje + 12 xp
                                            </button>
                                        @else
                                            <div
                                                class="w-full h-10 bg-green-50 text-green-600 rounded-xl text-xs font-bold flex items-center justify-center gap-2 border border-green-100">
                                                <span class="material-symbols-outlined text-sm">verified</span>
                                                Ofensiva Concluída!
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                @if ($goal->deadline)
                                    <div class="mt-6 pt-4 border-t border-gray-50">
                                        <button wire:click="completeGoal({{ $goal->id }})"
                                            class="w-full h-10 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold flex items-center justify-center gap-2 transition-all shadow-lg shadow-blue-500/20 hover:scale-[1.02] active:scale-[0.98]">
                                            <span class="material-symbols-outlined text-sm">check_circle</span>
                                            Concluir Meta +500 XP
                                        </button>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white rounded-3xl p-10 border border-dashed border-[#dbe6e1] text-center space-y-4">
                        <div
                            class="size-16 rounded-full bg-gray-50/50 flex items-center justify-center mx-auto text-gray-300">
                            <span class="material-symbols-outlined !text-3xl">add_task</span>
                        </div>
                        <div>
                            <h4 class="font-bold">
                                {{ $search ? 'Nenhuma meta encontrada' : 'Nenhuma meta ativa' }}
                            </h4>
                            <p class="text-sm text-gray-500">
                                {{ $search ? "Não encontramos metas para \"$search\"" : 'Que tal começar algo novo hoje?' }}
                            </p>
                        </div>
                        @if (!$search)
                            <a href="{{ route('goals.form') }}"
                                class="inline-flex h-10 px-6 bg-primary text-[#111815] rounded-full text-xs font-bold items-center hover:scale-105 transition-transform"
                                wire:navigate>
                                Criar Minha Primeira Meta
                            </a>
                        @endif
                    </div>
                @endif
            </section>
        </div>

        <div class="space-y-6 order-1 lg:order-2">
            <div class="bg-white rounded-3xl border border-[#dbe6e1] shadow-sm overflow-hidden">
                <div class="p-5 border-b border-[#dbe6e1] bg-gray-50/50 flex items-center justify-between">
                    <h2 class="font-bold">Ranking XP</h2>
                    <span
                        class="material-symbols-outlined icon-gradient-trophy text-2xl text-yellow-500">leaderboard</span>
                </div>
                <div class="p-4 space-y-3">
                    @foreach ($ranking as $index => $rankUser)
                        @php
                            $isCurrentUser = $rankUser->id === auth()->id();
                        @endphp
                        <a href="{{ route('social.profile', $rankUser->nickname ?: $rankUser->id) }}"
                            class="flex items-center gap-3 p-2 rounded-xl transition-all {{ $isCurrentUser ? 'bg-primary/10 border border-primary/20' : 'hover:bg-gray-50' }} group"
                            wire:navigate>
                            <div class="flex items-center justify-center size-5 text-[10px] font-black text-gray-400">
                                {{ $index + 1 }}
                            </div>
                            <div
                                class="size-8 rounded-full overflow-hidden border border-gray-100 group-hover:scale-110 transition-transform">
                                <img alt="{{ $rankUser->name }}" class="w-full h-full object-cover"
                                    src="{{ $rankUser->avatar_url ?: 'https://ui-avatars.com/api/?name=' . urlencode($rankUser->name) }}" />
                            </div>
                            <div class="flex-1 min-w-0">
                                <p
                                    class="text-xs font-bold truncate group-hover:underline {{ $isCurrentUser ? 'text-blue-600' : 'text-gray-700' }}">
                                    {{ $isCurrentUser ? 'Você' : $rankUser->name }}
                                </p>
                                <p class="text-[10px] text-gray-400 font-bold">
                                    {{ $rankUser->experiences_sum_amount ?: 0 }} XP</p>
                            </div>
                            @if ($index < 3)
                                <span class="material-symbols-outlined text-sm text-yellow-500">
                                    {{ $index === 0 ? 'military_tech' : 'workspace_premium' }}
                                </span>
                            @endif
                        </a>
                    @endforeach
                    @if ($ranking->isEmpty())
                        <p class="text-center py-4 text-xs text-gray-400 font-medium">Você ainda não tem amigos no
                            ranking.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
