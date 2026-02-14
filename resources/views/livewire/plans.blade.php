<div class="bg-gray-50 text-[#111815] font-display min-h-screen flex flex-col">
    <main class="flex-1 flex flex-col items-center">
        <section class="w-full max-w-[1200px] px-4 md:px-10 py-16 md:py-24" id="pricing">
            <div class="flex flex-col gap-4 text-center mb-16 pricing-header">
                <h1 class="text-[#111815] text-4xl md:text-5xl font-black tracking-tight">Planos e Preços <span class="text-primary">Individuais</span></h1>
                <p class="text-gray-600 text-lg max-w-[700px] mx-auto">
                    Foco total na sua evolução pessoal. Escolha o nível de suporte que você precisa para chegar ao topo.
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch pricing-grid">
                <div class="flex flex-col p-8 bg-white rounded-xl border border-[#dbe6e1] shadow-sm transition-all hover:border-primary/50 pricing-card">
                    <div class="mb-8">
                        <h3 class="text-xl font-bold mb-2">Iniciante</h3>
                        <div class="flex items-baseline gap-1">
                            <span class="text-4xl font-black">R$ 0</span>
                            <span class="text-gray-500 text-sm">/sempre</span>
                        </div>
                        <p class="text-sm text-gray-500 mt-2">Para quem está começando a jornada.</p>
                    </div>
                    <ul class="flex flex-col gap-4 mb-8 flex-1">
                        <li class="flex items-center gap-3 text-sm">
                            <span class="material-symbols-outlined text-primary text-[20px]">check_circle</span>
                            Até 5 metas ativas
                        </li>
                        <li class="flex items-center gap-3 text-sm">
                            <span class="material-symbols-outlined text-primary text-[20px]">check_circle</span>
                            Ofensivas (Streaks) básicas
                        </li>
                        <li class="flex items-center gap-3 text-sm text-gray-400 line-through">
                            <span class="material-symbols-outlined text-gray-300 text-[20px]">block</span>
                            Insights por IA
                        </li>
                        <li class="flex items-center gap-3 text-sm text-gray-400 line-through">
                            <span class="material-symbols-outlined text-gray-300 text-[20px]">block</span>
                            Ranking Global
                        </li>
                    </ul>
                    <a href="{{ route('register') }}" class="w-full flex items-center justify-center py-4 rounded-full border border-primary text-primary font-bold hover:bg-primary/5 transition-colors" wire:navigate>
                        Começar grátis
                    </a>
                </div>
                <div class="flex flex-col p-8 bg-white rounded-xl border-2 border-primary shadow-2xl relative transform scale-105 z-10 pricing-card">
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-primary text-[#111815] text-[10px] font-black uppercase tracking-widest px-4 py-1 rounded-full">
                        Mais Popular
                    </div>
                    <div class="mb-8">
                        <h3 class="text-xl font-bold mb-2">Pro</h3>
                        <div class="flex items-baseline gap-1">
                            <span class="text-4xl font-black">R$ 29</span>
                            <span class="text-gray-500 text-sm">/mês</span>
                        </div>
                        <p class="text-xs text-gray-400 mt-1">Ideal para foco e produtividade diária</p>
                    </div>
                    <ul class="flex flex-col gap-4 mb-8 flex-1">
                        <li class="flex items-center gap-3 text-sm font-bold">
                            <span class="material-symbols-outlined text-primary text-[20px]">check_circle</span>
                            Metas Ilimitadas
                        </li>
                        <li class="flex items-center gap-3 text-sm font-bold">
                            <span class="material-symbols-outlined text-primary text-[20px]">check_circle</span>
                            Insights por IA semanais
                        </li>
                        <li class="flex items-center gap-3 text-sm font-bold">
                            <span class="material-symbols-outlined text-primary text-[20px]">check_circle</span>
                            Ranking Global & Medalhas
                        </li>
                        <li class="flex items-center gap-3 text-sm font-bold">
                            <span class="material-symbols-outlined text-primary text-[20px]">check_circle</span>
                            Modo foco e sons relaxantes
                        </li>
                        <li class="flex items-center gap-3 text-sm font-bold">
                            <span class="material-symbols-outlined text-primary text-[20px]">check_circle</span>
                            Backup em nuvem automático
                        </li>
                    </ul>
                    <a href="{{ route('register') }}" class="w-full flex items-center justify-center py-4 rounded-full bg-primary text-[#111815] font-bold shadow-lg shadow-primary/30 hover:scale-105 transition-transform" wire:navigate>
                        Assinar Plano Pro
                    </a>
                </div>
                <div class="flex flex-col p-8 bg-white rounded-xl border border-[#dbe6e1] shadow-sm transition-all hover:border-primary/50 pricing-card">
                    <div class="mb-8">
                        <h3 class="text-xl font-bold mb-2">Elite</h3>
                        <div class="flex items-baseline gap-1">
                            <span class="text-4xl font-black">R$ 49</span>
                            <span class="text-gray-500 text-sm">/mês</span>
                        </div>
                        <p class="text-sm text-gray-500 mt-2">Para quem busca o próximo nível.</p>
                    </div>
                    <ul class="flex flex-col gap-4 mb-8 flex-1">
                        <li class="flex items-center gap-3 text-sm">
                            <span class="material-symbols-outlined text-primary text-[20px]">stars</span>
                            Tudo do plano Pro
                        </li>
                        <li class="flex items-center gap-3 text-sm">
                            <span class="material-symbols-outlined text-primary text-[20px]">groups</span>
                            Acesso à Comunidade VIP
                        </li>
                        <li class="flex items-center gap-3 text-sm font-bold">
                            <span class="material-symbols-outlined text-primary text-[20px]">psychology</span>
                            Mentoria Mensal em Grupo
                        </li>
                        <li class="flex items-center gap-3 text-sm">
                            <span class="material-symbols-outlined text-primary text-[20px]">workspace_premium</span>
                            Suporte Prioritário Via WhatsApp
                        </li>
                        <li class="flex items-center gap-3 text-sm">
                            <span class="material-symbols-outlined text-primary text-[20px]">auto_awesome</span>
                            Funcionalidades Beta Antecipadas
                        </li>
                    </ul>
                    <a href="{{ route('register') }}" class="w-full flex items-center justify-center py-4 rounded-full border border-gray-300 font-bold hover:bg-gray-50 transition-colors" wire:navigate>
                        Ser Elite
                    </a>
                </div>
            </div>
        </section>
        <section class="w-full bg-white py-24 faq-section" id="faq">
            <div class="max-w-[800px] mx-auto px-4 md:px-10">
                <h2 class="text-3xl font-black text-center mb-12">Dúvidas Frequentes</h2>
                <div class="space-y-6">
                    <div class="p-6 bg-gray-50 rounded-xl border border-[#dbe6e1] faq-item">
                        <h4 class="font-bold mb-2 text-lg">Posso mudar de plano quando quiser?</h4>
                        <p class="text-gray-600">Sim! Você pode fazer o upgrade para Pro ou Elite a qualquer momento. Se decidir voltar para o plano gratuito, suas metas extras ficarão arquivadas mas salvas.</p>
                    </div>
                    <div class="p-6 bg-gray-50 rounded-xl border border-[#dbe6e1] faq-item">
                        <h4 class="font-bold mb-2 text-lg">Como funciona a Mentoria Mensal do plano Elite?</h4>
                        <p class="text-gray-600">Todo primeiro sábado do mês realizamos uma call exclusiva para membros Elite sobre produtividade, hábitos e revisão de metas com especialistas.</p>
                    </div>
                    <div class="p-6 bg-gray-50 rounded-xl border border-[#dbe6e1] faq-item">
                        <h4 class="font-bold mb-2 text-lg">O Everest tem acesso Vitalício?</h4>
                        <p class="text-gray-600">Atualmente trabalhamos com assinaturas mensais para garantir a evolução contínua da plataforma, mas oferecemos descontos especiais para renovações anuais.</p>
                    </div>
                    <div class="p-6 bg-gray-50 rounded-xl border border-[#dbe6e1] faq-item">
                        <h4 class="font-bold mb-2 text-lg">Existe suporte para usuários individuais?</h4>
                        <p class="text-gray-600">Com certeza. Todos os usuários têm acesso à nossa central de ajuda. Assinantes Elite possuem canal direto e prioritário.</p>
                    </div>
                </div>
            </div>
        </section>
        <section class="w-full max-w-[1200px] px-4 py-24 pricing-cta-section">
            <div class="bg-gray-900 text-white rounded-xl p-8 md:p-16 flex flex-col items-center text-center gap-8 relative overflow-hidden pricing-cta-content">
                <div class="absolute top-0 right-0 w-64 h-64 bg-primary/20 rounded-full blur-[100px] -mr-32 -mt-32"></div>
                <div class="absolute bottom-0 left-0 w-64 h-64 bg-blue-500/10 rounded-full blur-[100px] -ml-32 -mb-32"></div>
                <h2 class="text-4xl md:text-5xl font-black max-w-[700px] relative z-10">O topo da montanha espera por você.</h2>
                <a href="{{ route('register') }}" class="flex min-w-[200px] cursor-pointer items-center justify-center rounded-full h-14 px-8 bg-primary text-[#111815] text-lg font-bold shadow-xl shadow-primary/30 hover:scale-105 transition-all relative z-10" wire:navigate>
                    Começar agora gratuitamente
                </a>
                <p class="text-sm text-gray-500 mt-4 relative z-10">Junte-se a 10.000+ pessoas que já estão evoluindo diariamente.</p>
            </div>
        </section>
    </main>


</div>
