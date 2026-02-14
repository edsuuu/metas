<div class="max-w-[1200px] mx-auto px-4 md:px-10 py-12">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
        <div>
            <h1 class="text-3xl font-black text-[#111815]">Minhas Metas</h1>
            <p class="text-gray-500 mt-1">Acompanhe seu progresso e conquiste o topo.</p>
        </div>
        <div class="flex flex-col sm:flex-row items-center gap-4 w-full md:w-auto">
            <div class="relative w-full sm:w-64 group">
                <span
                    class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-primary transition-colors">search</span>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar metas..."
                    class="w-full h-11 pl-12 pr-4 bg-white border border-[#dbe6e1] rounded-2xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" />
            </div>
            <a href="{{ route('goals.create') }}"
                class="bg-primary text-[#111815] h-11 px-6 rounded-xl text-sm font-bold shadow-lg shadow-primary/20 flex items-center justify-center gap-2 hover:scale-105 transition-all w-full sm:w-auto shrink-0"
                wire:navigate>
                <span class="material-symbols-outlined text-[18px]">add</span>
                Criar Nova Meta
            </a>
        </div>
    </div>

    @if ($goals->isEmpty())
        @if ($search)
            <div class="bg-white rounded-3xl p-12 text-center border-2 border-dashed border-gray-200">
                <div
                    class="size-20 rounded-full bg-gray-50 mx-auto flex items-center justify-center mb-6 text-gray-300">
                    <span class="material-symbols-outlined !text-4xl">search_off</span>
                </div>
                <h3 class="text-xl font-bold mb-2">Nenhum resultado</h3>
                <p class="text-gray-500 max-w-xs mx-auto">Não encontramos metas que correspondam à sua busca
                    "{{ $search }}".</p>
            </div>
        @else
            <div class="bg-white rounded-3xl p-12 text-center border-2 border-dashed border-gray-200">
                <div
                    class="size-20 rounded-full bg-gray-50 mx-auto flex items-center justify-center mb-6 text-gray-300">
                    <span class="material-symbols-outlined !text-4xl">flag</span>
                </div>
                <h3 class="text-xl font-bold mb-2">Nenhuma meta ativa</h3>
                <p class="text-gray-500 mb-8 max-w-xs mx-auto">Sua jornada começa com o primeiro passo. Defina um
                    objetivo hoje!</p>
                <a href="{{ route('goals.create') }}"
                    class="inline-flex items-center gap-2 text-primary font-bold hover:underline" wire:navigate>
                    Começar agora
                    <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </a>
            </div>
        @endif
    @else
        <div class="columns-1 md:columns-2 lg:columns-3 gap-6">
            @foreach ($goals as $goal)
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

                <div x-data="{ expanded: false }"
                    class="break-inside-avoid mb-6 bg-white rounded-3xl p-6 border border-[#dbe6e1] shadow-sm hover:shadow-xl transition-all group">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-4">
                            <div
                                class="size-12 rounded-xl flex items-center justify-center shadow-sm {{ $styles['bg'] }} {{ $styles['color'] }}">
                                <span class="material-symbols-outlined">{{ $styles['icon'] }}</span>
                            </div>
                            <a href="{{ route('goals.show', $goal->uuid) }}" wire:navigate>
                                <h4 class="font-bold group-hover:text-primary transition-colors">{{ $goal->title }}
                                </h4>
                                <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">
                                    {{ $goal->category }}</p>
                            </a>
                        </div>
                        <div class="text-right">
                            @if ($totalTasks > 0)
                                <span class="text-sm font-black {{ $styles['color'] }}">{{ $progress }}%</span>
                            @endif
                            @if ($goal->is_streak_enabled)
                                <div class="flex items-center justify-end gap-1 text-orange-500 mt-1">
                                    <span class="material-symbols-outlined text-sm"
                                        style="font-variation-settings: 'FILL' 1">local_fire_department</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if ($totalTasks > 0)
                        <div
                            class="w-full h-2.5 bg-gray-200/60 rounded-full mb-6 relative overflow-hidden border border-gray-100">
                            <div class="h-full {{ $styles['bar'] }} rounded-full transition-all duration-1000 ease-out shadow-[0_0_10px_rgba(0,0,0,0.1)]"
                                style="width: {{ $progress }}%"></div>
                        </div>
                    @endif

                    @if ($goal->target_value)
                        <div class="mb-4">
                            <p class="text-[10px] text-gray-400 font-bold mb-1 uppercase">Valor Alvo</p>
                            <p class="text-sm font-black text-blue-600">R$
                                {{ number_format($goal->target_value, 2, ',', '.') }}</p>
                        </div>
                    @endif

                    @if ($goal->deadline)
                        <div class="mb-4">
                            <p class="text-[10px] text-gray-400 font-bold mb-1 uppercase">Deadline</p>
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-xs text-gray-400">calendar_today</span>
                                <span class="text-xs font-bold text-gray-600">
                                    {{ $goal->deadline->format('d/m/Y') }}
                                </span>
                            </div>
                        </div>
                    @endif

                    @if ($totalTasks > 0)
                        <div class="space-y-3 border-t border-gray-50 pt-4 mb-6">
                            @foreach ($goal->microTasks->take(3) as $task)
                                <div class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded-lg cursor-pointer transition-colors"
                                    wire:click="toggleMicroTask({{ $task->id }})">
                                    <input type="checkbox" @checked($task->is_completed)
                                        class="rounded border-gray-300 {{ str_replace('text-', 'text-', $styles['color']) }} focus:ring-current pointer-events-none" />
                                    <span
                                        class="text-[11px] font-medium truncate {{ $task->is_completed ? 'line-through opacity-50' : '' }}">
                                        {{ $task->title }}
                                    </span>
                                </div>
                            @endforeach

                            <div x-show="expanded" x-collapse>
                                @foreach ($goal->microTasks->skip(3) as $task)
                                    <div class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded-lg cursor-pointer transition-colors"
                                        wire:click="toggleMicroTask({{ $task->id }})">
                                        <input type="checkbox" @checked($task->is_completed)
                                            class="rounded border-gray-300 {{ str_replace('text-', 'text-', $styles['color']) }} focus:ring-current pointer-events-none" />
                                        <span
                                            class="text-[11px] font-medium truncate {{ $task->is_completed ? 'line-through opacity-50' : '' }}">
                                            {{ $task->title }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>

                            @if ($totalTasks > 3)
                                <button @click="expanded = !expanded"
                                    class="w-full mt-2 py-2 px-3 flex items-center justify-between text-[11px] font-bold text-gray-400 hover:text-primary hover:bg-primary/5 rounded-lg transition-all border border-transparent hover:border-primary/10">
                                    <span
                                        x-text="expanded ? 'Ver menos' : '+ {{ $totalTasks - 3 }} outras tarefas'"></span>
                                    <span class="material-symbols-outlined text-sm transition-transform"
                                        :class="expanded ? 'rotate-180' : ''">
                                        keyboard_arrow_down
                                    </span>
                                </button>
                            @endif
                        </div>
                    @endif

                    <div class="mt-auto">
                        <a href="{{ route('goals.show', $goal->uuid) }}"
                            class="w-full h-10 border-2 border-gray-100 rounded-xl text-xs font-bold text-gray-500 hover:border-primary hover:text-primary transition-all flex items-center justify-center"
                            wire:navigate>
                            Ver Detalhes
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
