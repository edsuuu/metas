<div class="flex flex-col min-h-screen bg-gray-50 text-[#111815] transition-colors duration-300">
    <main class="flex flex-col items-center flex-1">
        <!-- Hero Section -->
        <section class="w-full max-w-[1200px] px-4 md:px-10 py-16 md:py-24">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="flex flex-col gap-8 text-left hero-content">
                    <div class="flex flex-col gap-4">
                        <span class="inline-block px-4 py-1 rounded-full bg-primary/10 text-primary text-sm font-bold w-fit">
                            🚀 Gestão de Metas Reimaginada
                        </span>
                        <h1 class="text-[#111815] text-5xl md:text-6xl font-black leading-[1.1] tracking-tight">
                            Conquiste seus maiores objetivos, <span class="text-primary italic">um passo de cada vez</span>
                        </h1>
                        <p class="text-lg text-gray-600 max-w-[500px]">
                            O Everest ajuda você a transformar sonhos complexos em micro-tarefas acionáveis. Comece sua escalada hoje e alcance o topo da sua produtividade.
                        </p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('register') }}" class="flex min-w-[200px] cursor-pointer items-center justify-center rounded-full h-14 px-8 bg-primary text-[#111815] text-lg font-bold shadow-xl shadow-primary/30 hover:scale-105 transition-all" wire:navigate>
                            Crie sua conta agora
                        </a>
                    </div>
                    <div class="flex items-center gap-4 mt-2">
                        <div class="flex -space-x-2">
                            @foreach([1, 2, 3] as $i)
                                <div class="w-8 h-8 rounded-full border-2 border-white bg-gray-200 overflow-hidden">
                                    <img alt="Avatar" src="https://i.pravatar.cc/100?img={{ $i + 10 }}" />
                                </div>
                            @endforeach
                        </div>
                        <p class="text-sm font-medium text-gray-500">
                            Junte-se a mais de 10.000 usuários
                        </p>
                    </div>
                </div>
                <div class="relative hero-image">
                    <div class="absolute -top-10 -left-10 w-40 h-40 bg-primary/20 rounded-full blur-3xl"></div>
                    <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-blue-400/10 rounded-full blur-3xl"></div>
                    <div class="relative w-full aspect-[4/3] bg-white rounded-xl shadow-2xl border border-[#dbe6e1] overflow-hidden p-4">
                        <div class="flex flex-col h-full bg-gray-50 rounded-lg p-4 gap-4">
                            <div class="flex justify-between items-center border-b pb-3 border-gray-200">
                                <div class="h-4 w-32 bg-gray-200 rounded-full"></div>
                                <div class="flex gap-2">
                                    <div class="size-4 rounded-full bg-red-400"></div>
                                    <div class="size-4 rounded-full bg-yellow-400"></div>
                                    <div class="size-4 rounded-full bg-green-400"></div>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-3">
                                <div class="col-span-1 h-32 bg-white rounded-lg p-3 flex flex-col items-center justify-center gap-2 border border-gray-100 shadow-sm">
                                    <div class="size-16 rounded-full border-4 border-primary border-t-transparent flex items-center justify-center">
                                        <span class="text-xs font-bold">78%</span>
                                    </div>
                                    <div class="h-2 w-12 bg-gray-200 rounded-full"></div>
                                </div>
                                <div class="col-span-2 h-32 bg-white rounded-lg p-3 flex flex-col gap-2 border border-gray-100 shadow-sm">
                                    <div class="h-4 w-2/3 bg-primary/20 rounded-full"></div>
                                    <div class="flex flex-col gap-2 pt-2">
                                        <div class="h-2 w-full bg-gray-100 rounded-full"></div>
                                        <div class="h-2 w-5/6 bg-gray-100 rounded-full"></div>
                                        <div class="h-2 w-4/6 bg-gray-100 rounded-full"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-1 bg-white rounded-lg border border-gray-100 shadow-sm p-4">
                                <div class="flex items-center gap-3 mb-4">
                                    <span class="material-symbols-outlined text-orange-500 font-bold">local_fire_department</span>
                                    <div class="h-4 w-32 bg-gray-200 rounded-full"></div>
                                </div>
                                <div class="space-y-3">
                                    <div class="flex items-center gap-3">
                                        <div class="size-5 rounded-md border-2 border-primary"></div>
                                        <div class="h-3 w-40 bg-gray-100 rounded-full"></div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="size-5 rounded-md bg-primary flex items-center justify-center">
                                            <span class="material-symbols-outlined text-white text-[14px]">check</span>
                                        </div>
                                        <div class="h-3 w-48 bg-gray-100 rounded-full"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="w-full bg-white py-12 stats-section" id="stats">
            <div class="max-w-[1200px] mx-auto px-4 md:px-10">
                <div class="flex flex-wrap gap-6 justify-center">
                    <div class="flex min-w-[200px] flex-1 flex-col items-center gap-2 rounded-xl p-8 border border-[#dbe6e1] bg-gray-50 stats-card">
                        <p class="text-gray-500 text-sm font-medium">Usuários Ativos</p>
                        <p class="text-[#111815] tracking-light text-4xl font-black leading-tight">10.000+</p>
                    </div>
                    <div class="flex min-w-[200px] flex-1 flex-col items-center gap-2 rounded-xl p-8 border border-[#dbe6e1] bg-gray-50 stats-card">
                        <p class="text-gray-500 text-sm font-medium">Metas Concluídas</p>
                        <p class="text-[#111815] tracking-light text-4xl font-black leading-tight">1.2M</p>
                    </div>
                    <div class="flex min-w-[200px] flex-1 flex-col items-center gap-2 rounded-xl p-8 border border-[#dbe6e1] bg-gray-50 stats-card">
                        <p class="text-gray-500 text-sm font-medium">Taxa de Sucesso</p>
                        <p class="text-[#111815] tracking-light text-4xl font-black leading-tight">94%</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="w-full max-w-[1200px] px-4 md:px-10 py-20 features-section" id="features">
            <div class="flex flex-col gap-16 items-center">
                <div class="flex flex-col gap-4 text-center max-w-[800px]">
                    <h2 class="text-primary font-bold tracking-widest uppercase text-sm">Motor de Alta Performance</h2>
                    <h1 class="text-[#111815] tracking-tight text-4xl md:text-5xl font-black leading-tight">Quebre seus limites, não seu foco</h1>
                    <p class="text-gray-600 text-lg">Nosso sistema é projetado para manter você avançando com ferramentas baseadas em psicologia que eliminam a procrastinação.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 w-full">
                    @php
                        $features = [
                            ['icon' => 'bar_chart', 'title' => 'Insights Semanais', 'desc' => 'Reflexão baseada em dados sobre seu progresso para otimizar seu desempenho toda segunda-feira de manhã.'],
                            ['icon' => 'check_circle', 'title' => 'Micro-tarefas', 'desc' => 'Produtividade baseada em psicologia para evitar o burnout e manter o ritmo ao dividir grandes objetivos.'],
                            ['icon' => 'sync', 'title' => 'Metas Recorrentes', 'desc' => 'Construa hábitos que duram para sempre com cronogramas recorrentes automatizados e lembretes.'],
                            ['icon' => 'local_fire_department', 'title' => 'Gamificação', 'desc' => 'Mantenha a motivação com recompensas, marcos visuais e contadores de chamas que rastreiam sua constância.'],
                        ];
                    @endphp
                    @foreach($features as $feature)
                        <div class="flex flex-col gap-6 rounded-xl border border-[#dbe6e1] bg-white p-8 hover:border-primary/50 transition-all group feature-card">
                            <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined">{{ $feature['icon'] }}</span>
                            </div>
                            <div class="flex flex-col gap-2">
                                <h2 class="text-[#111815] text-xl font-bold leading-tight">{{ $feature['title'] }}</h2>
                                <p class="text-gray-500 text-sm leading-relaxed">{{ $feature['desc'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Product Showcase Section -->
        <section class="w-full bg-gray-50 py-24 overflow-hidden">
            <div class="max-w-[1200px] mx-auto px-4 md:px-10 flex flex-col gap-12 lg:flex-row items-center">
                <div class="flex flex-col gap-6 lg:w-1/2">
                    <h2 class="text-[#111815] text-4xl font-black leading-tight">Experimente o auge da produtividade pessoal</h2>
                    <p class="text-gray-600 text-lg">O Everest não é apenas uma lista de tarefas. É um motor de conquista de metas que prioriza sua energia mental e foca seu tempo no que realmente importa.</p>
                    <ul class="flex flex-col gap-4">
                        @foreach(['Matriz de Priorização', 'Lembretes de Pausas Conscientes', 'Sincronização entre todos os dispositivos'] as $item)
                            <li class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-primary">verified</span>
                                <span class="font-bold">{{ $item }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="lg:w-1/2 flex justify-center">
                    <div class="relative w-full max-w-[500px]">
                        <div class="bg-gradient-to-tr from-primary to-blue-400 p-8 rounded-lg shadow-2xl rotate-3 scale-95 opacity-50 absolute inset-0"></div>
                        <div class="relative bg-white rounded-lg shadow-2xl p-6 border border-[#dbe6e1]">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="font-black text-xl">Escalada de Hoje</h3>
                                <div class="flex items-center gap-1 bg-primary/20 px-3 py-1 rounded-full">
                                    <span class="material-symbols-outlined text-orange-500 text-sm font-bold">local_fire_department</span>
                                    <span class="text-xs font-bold">12 Dias</span>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div class="p-4 bg-gray-50 rounded-xl flex items-center gap-4">
                                    <div class="size-6 rounded-full border-2 border-primary"></div>
                                    <div class="h-4 w-40 bg-gray-200 rounded-full"></div>
                                </div>
                                <div class="p-4 bg-gray-50 rounded-xl flex items-center gap-4">
                                    <div class="size-6 rounded-full bg-primary flex items-center justify-center text-white">
                                        <span class="material-symbols-outlined text-xs">check</span>
                                    </div>
                                    <div class="h-4 w-32 bg-gray-200 rounded-full opacity-50"></div>
                                </div>
                                <div class="p-4 bg-gray-50 rounded-xl flex items-center gap-4">
                                    <div class="size-6 rounded-full border-2 border-primary"></div>
                                    <div class="h-4 w-48 bg-gray-200 rounded-full"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="w-full max-w-[1200px] px-4 py-24 cta-section">
            <div class="bg-gray-900 text-white rounded-xl p-8 md:p-16 flex flex-col items-center text-center gap-8 relative overflow-hidden cta-content">
                <div class="absolute top-0 right-0 w-64 h-64 bg-primary/20 rounded-full blur-[100px] -mr-32 -mt-32"></div>
                <div class="absolute bottom-0 left-0 w-64 h-64 bg-blue-500/10 rounded-full blur-[100px] -ml-32 -mb-32"></div>
                <h2 class="text-4xl md:text-5xl font-black max-w-[700px] relative z-10">Pronto para começar sua escalada?</h2>
                <p class="text-gray-400 text-lg max-w-[600px] relative z-10">Junte-se a milhares de pessoas de alta performance que estão alcançando suas metas com o Everest. Comece agora mesmo a escalar seus sonhos.</p>
                <div class="flex flex-col sm:flex-row gap-4 relative z-10 w-full sm:w-auto">
                    <a href="{{ route('register') }}" class="flex min-w-[200px] cursor-pointer items-center justify-center rounded-full h-14 px-8 bg-primary text-[#111815] text-lg font-bold shadow-xl shadow-primary/30 hover:scale-105 transition-all" wire:navigate>
                        Crie sua conta agora
                    </a>
                </div>
                <p class="text-sm text-gray-500 mt-4 relative z-10">Junte-se a 10.000+ usuários ativos. Cancele a qualquer momento.</p>
            </div>
        </section>
    </main>


</div>
