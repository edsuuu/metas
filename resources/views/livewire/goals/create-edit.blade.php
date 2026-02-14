<div class="max-w-[1200px] mx-auto px-4 py-12 flex-grow w-full">
    <div class="max-w-3xl mx-auto">
        <div class="mb-10 flex flex-col items-center text-center">
            <h1 class="text-3xl font-black text-[#111815] mb-2">
                @if ($isEditing)
                    Editar Meta
                @else
                    @if ($step === 1)
                        Cadastro de Nova Meta
                    @else
                        Cadastro de Meta: Micro-tarefas
                    @endif
                @endif
            </h1>
            <p class="text-gray-500">
                @if ($step === 1)
                    Transforme seus sonhos em passos acionáveis.
                @else
                    Divida sua jornada em conquistas diárias.
                @endif
            </p>
        </div>

        <!-- Stepper -->
        <div class="relative mb-12 w-full px-4 md:px-10">
            <div class="absolute top-5 left-0 w-full h-[2px] bg-gray-100 z-0"></div>

            <div class="relative z-10 flex items-center justify-between w-full max-w-sm mx-auto">
                <!-- Step 1 Node -->
                <div class="flex flex-col items-center gap-2 cursor-pointer group" wire:click="$set('step', 1)">
                    <div
                        class="size-10 rounded-full flex items-center justify-center font-bold border-2 transition-all group-hover:scale-110 {{ $step >= 1 ? 'bg-primary text-[#111815] border-primary shadow-lg shadow-primary/20' : 'bg-white border-gray-100 text-gray-400' }}">
                        @if ($step > 1)
                            <span class="material-symbols-outlined text-base">check</span>
                        @else
                            1
                        @endif
                    </div>
                    <span
                        class="text-[10px] font-bold uppercase tracking-widest {{ $step >= 1 ? 'text-primary' : 'text-gray-400' }}">
                        Objetivo
                    </span>
                </div>

                <!-- Step 2 Node -->
                <div class="flex flex-col items-center gap-2 cursor-pointer group" wire:click="nextStep">
                    <div
                        class="size-10 rounded-full flex items-center justify-center font-bold border-2 transition-all group-hover:scale-110 {{ $step >= 2 ? 'bg-primary text-[#111815] border-primary shadow-lg shadow-primary/20' : 'bg-white border-gray-100 text-gray-400' }}">
                        2
                    </div>
                    <span
                        class="text-[10px] font-bold uppercase tracking-widest {{ $step >= 2 ? 'text-[#111815]' : 'text-gray-400' }}">
                        Passos
                    </span>
                </div>
            </div>
        </div>

        <div class="bg-white border border-[#dbe6e1] rounded-2xl shadow-xl p-8 md:p-10">
            <form wire:submit.prevent="save" class="space-y-8">
                <!-- Step 1 Content -->
                @if ($step === 1)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="col-span-1 md:col-span-2 space-y-2">
                            <label class="block text-sm font-bold text-[#111815]">
                                Qual o nome da sua meta?
                            </label>
                            <input wire:model="title"
                                class="w-full h-12 px-4 rounded-xl border-2 focus:ring-primary focus:border-primary transition-all {{ $errors->has('title') ? 'border-red-500' : 'border-gray-100' }}"
                                placeholder="Ex: Economizar para viagem, Correr 5km..." type="text" maxlength="50" />
                            @error('title')
                                <p class="text-red-500 text-xs font-bold">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-[#111815]">
                                Categoria
                            </label>
                            <select wire:model="category"
                                class="w-full h-12 px-4 rounded-xl border-gray-200 focus:ring-primary focus:border-primary transition-all appearance-none">
                                <option value="saude">💪 Saúde</option>
                                <option value="financeiro">💰 Financeiro</option>
                                <option value="carreira">🚀 Carreira</option>
                                <option value="pessoal">🧠 Pessoal</option>
                            </select>
                        </div>
                        <div class="col-span-1 md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Streak Option -->
                            <div
                                class="p-5 rounded-2xl border transition-all {{ $is_streak_enabled ? 'bg-orange-50 border-orange-100' : 'bg-gray-50 border-gray-100 opacity-60 hover:opacity-100' }}">
                                <div class="flex items-center justify-between w-full h-full">
                                    <div class="flex items-center gap-4 flex-1">
                                        <div
                                            class="size-12 rounded-full flex items-center justify-center transition-colors {{ $is_streak_enabled ? 'bg-orange-100 text-orange-500' : 'bg-gray-200 text-gray-400' }}">
                                            <span class="material-symbols-outlined !text-[28px]"
                                                style="font-variation-settings: 'FILL' 1">local_fire_department</span>
                                        </div>
                                        <div>
                                            <label
                                                class="flex items-center gap-2 text-sm font-extrabold text-[#111815] cursor-pointer"
                                                for="enable-streak">
                                                Habilitar Ofensiva?
                                            </label>
                                            <p class="text-xs text-gray-500">Foco diário contínuo.</p>
                                        </div>
                                    </div>
                                    <div
                                        class="relative inline-block w-12 h-6 transition duration-200 ease-in-out shrink-0">
                                        <input id="enable-streak" type="checkbox" wire:model.live="is_streak_enabled"
                                            class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 border-gray-300 appearance-none cursor-pointer z-10 transition-all duration-200 {{ $is_streak_enabled ? 'right-0 border-orange-500' : 'left-0' }}" />
                                        <label for="enable-streak"
                                            class="toggle-label block overflow-hidden h-6 rounded-full cursor-pointer transition-colors duration-200 {{ $is_streak_enabled ? 'bg-orange-500' : 'bg-gray-300' }}"></label>
                                    </div>
                                </div>
                            </div>

                            <!-- Deadline Option -->
                            <div
                                class="p-5 rounded-2xl border transition-all {{ $deadline ? 'bg-blue-50 border-blue-100' : 'bg-gray-50 border-gray-100 opacity-60 hover:opacity-100' }}">
                                <div class="flex flex-col gap-4 w-full">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-4">
                                            <div
                                                class="size-12 rounded-full flex items-center justify-center transition-colors {{ $deadline ? 'bg-blue-100 text-blue-500' : 'bg-gray-200 text-gray-400' }}">
                                                <span class="material-symbols-outlined !text-[28px]">event</span>
                                            </div>
                                            <div>
                                                <label
                                                    class="flex items-center gap-2 text-sm font-extrabold text-[#111815] cursor-pointer">
                                                    Habilitar Data Final?
                                                </label>
                                                <p class="text-xs text-gray-500">Defina um prazo para conclusão.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="relative">
                                        <input type="date" wire:model.live="deadline"
                                            class="w-full h-10 px-4 rounded-lg border text-sm transition-all {{ $deadline ? 'border-blue-200 focus:ring-blue-500' : 'border-gray-200 text-gray-400' }}" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($step === 2)
                    <div class="space-y-6">
                        <div class="flex justify-between items-center border-b border-gray-100 pb-4">
                            <div>
                                <h3 class="text-lg font-bold text-[#111815]">Checklist de Passos</h3>
                                <p class="text-sm text-gray-500">Defina os marcos para atingir seu objetivo</p>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <div class="flex flex-col md:flex-row gap-4">
                                <div
                                    class="flex-grow bg-white rounded-xl px-4 h-12 flex items-center border {{ $errors->has('new_task_title') ? 'border-red-500' : 'border-gray-200' }} focus-within:border-primary focus-within:ring-1 focus-within:ring-primary transition-all">
                                    <input wire:model="new_task_title"
                                        class="w-full bg-transparent border-none focus:ring-0 p-0 text-sm text-[#111815] placeholder:text-gray-400"
                                        placeholder="Ex: Abrir conta poupança..." type="text"
                                        wire:keydown.enter="addMicroTask" />
                                </div>
                                <button type="button" wire:click="addMicroTask"
                                    class="shrink-0 flex items-center justify-center gap-2 bg-primary text-[#111815] h-12 px-6 rounded-xl text-sm font-bold shadow-lg shadow-primary/20 hover:scale-105 transition-all">
                                    <span class="material-symbols-outlined text-[18px]">add</span>
                                    Add
                                </button>
                            </div>
                            @error('new_task_title')
                                <p class="text-red-500 text-xs font-bold pl-2">{{ $message }}</p>
                            @enderror
                        </div>

                        @if (!empty($micro_tasks))
                            <div class="space-y-4">
                                @foreach ($micro_tasks as $index => $task)
                                    <div
                                        class="flex items-center gap-4 p-4 bg-gray-50 rounded-2xl border border-gray-100 group hover:border-primary/50 transition-colors">
                                        <div class="flex-1 space-y-1">
                                            <label class="text-[10px] uppercase tracking-wider font-bold text-gray-400">
                                                Título da Tarefa
                                            </label>
                                            <div class="text-sm font-bold text-[#111815]">
                                                {{ $task['title'] }}
                                            </div>
                                        </div>
                                        <button type="button" wire:click="removeMicroTask({{ $index }})"
                                            class="text-gray-300 hover:text-red-500 transition-colors opacity-0 group-hover:opacity-100">
                                            <span class="material-symbols-outlined">delete</span>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div class="mt-8 p-6 bg-blue-50 rounded-2xl border border-blue-100 flex gap-4">
                            <span class="material-symbols-outlined text-blue-500">lightbulb</span>
                            <div>
                                <h4 class="text-sm font-bold text-blue-900">Dica de Especialista</h4>
                                <p class="text-xs text-blue-700 mt-1">
                                    Metas quebradas em tarefas menores de até 30 minutos são 3x mais propensas a serem
                                    concluídas sem procrastinação.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($errors->has('is_streak_enabled') || $errors->has('micro_tasks') || $errors->has('deadline'))
                    <div
                        class="mt-6 p-4 bg-red-50 border border-red-200 rounded-2xl flex items-center gap-3 text-red-600">
                        <span class="material-symbols-outlined">error</span>
                        <div>
                            @error('is_streak_enabled')
                                <p class="text-sm font-bold">{{ $message }}</p>
                            @enderror
                            @error('micro_tasks')
                                <p class="text-sm font-bold">{{ $message }}</p>
                            @enderror
                            @error('deadline')
                                <p class="text-sm font-bold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                @endif

                <div
                    class="pt-8 flex flex-col sm:flex-row gap-4 items-center justify-between mt-8 border-t border-gray-100">
                    @if ($step > 1)
                        <button type="button" wire:click="prevStep"
                            class="order-2 sm:order-1 flex items-center justify-center gap-2 h-14 px-8 text-gray-500 font-bold hover:text-gray-800 transition-colors">
                            <span class="material-symbols-outlined">arrow_back</span>
                            Voltar
                        </button>
                    @else
                        <a href="{{ route('dashboard') }}"
                            class="order-2 sm:order-1 flex items-center justify-center gap-2 h-14 px-8 text-gray-500 font-bold hover:text-gray-800 transition-colors"
                            wire:navigate>
                            Cancelar
                        </a>
                    @endif

                    <div class="order-1 sm:order-2 flex gap-3 w-full sm:w-auto">
                        @if ($step < 2)
                            <button type="button" wire:click="nextStep"
                                class="w-full sm:min-w-[200px] flex items-center justify-center rounded-full h-14 px-8 bg-primary text-[#111815] text-lg font-bold shadow-xl shadow-primary/30 hover:scale-[1.02] transition-all">
                                Próximo Passo
                            </button>
                        @else
                            <button type="submit" wire:loading.attr="disabled"
                                class="w-full sm:min-w-[200px] flex items-center justify-center rounded-full h-14 px-8 bg-primary text-[#111815] text-lg font-bold shadow-xl shadow-primary/30 hover:scale-[1.02] transition-all">
                                {{ $isEditing ? 'Salvar Alterações' : 'Finalizar e Criar Meta' }}
                            </button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
