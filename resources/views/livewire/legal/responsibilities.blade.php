<div class="bg-background-light  text-[#111815] transition-colors duration-300 min-h-screen font-display"
     x-data="{
        current: '',
        handleScroll() {
            const sections = document.querySelectorAll('div[id]');
            let currentSection = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                if (window.pageYOffset >= sectionTop - 150) {
                    currentSection = section.getAttribute('id') || '';
                }
            });
            this.current = currentSection;
        }
     }"
     @scroll.window="handleScroll()"
     x-init="handleScroll()">
    
    <x-legal-navbar />

    <main class="max-w-[1200px] mx-auto px-4 md:px-12 py-12 lg:flex lg:gap-16">
        <aside class="hidden lg:block w-72 h-fit sticky top-28">
            <div class="mb-8 px-4">
                <span class="text-xs font-bold uppercase tracking-widest text-gray-400 block mb-2">Documentação</span>
                <a href="{{ route('legal.terms.index') }}" class="inline-flex items-center gap-2 text-[#111815]  font-bold hover:text-primary transition-colors">
                    <span class="material-symbols-outlined text-lg">arrow_back</span>
                    Voltar aos Termos
                </a>
            </div>
            <nav class="flex flex-col gap-1">
                <h3 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-4 px-4">
                    Documentação
                </h3>
                <a class="px-4 py-3 text-sm font-semibold rounded-lg text-gray-600  hover:bg-white  transition-all flex items-center gap-3" href="{{ route('legal.terms.index') }}">
                    <span class="material-symbols-outlined text-[18px]">description</span>
                    Termos Gerais
                </a>
                <a class="px-4 py-3 text-sm font-semibold rounded-lg text-gray-600  hover:bg-white  transition-all flex items-center gap-3"
                   :class="{ 'active': current === 'responsabilidades' }"
                   href="#responsabilidades">
                    <span class="material-symbols-outlined text-[18px]">gavel</span>
                    Responsabilidades
                </a>
                <a class="px-4 py-3 text-sm font-semibold rounded-lg text-gray-600  hover:bg-white  transition-all flex items-center gap-3"
                   :class="{ 'active': current === 'disponibilidade' }"
                   href="#disponibilidade">
                    <span class="material-symbols-outlined text-[18px]">update</span>
                    Disponibilidade (Uptime)
                </a>
                <a class="px-4 py-3 text-sm font-semibold rounded-lg text-gray-600  hover:bg-white  transition-all flex items-center gap-3"
                   :class="{ 'active': current === 'obrigacoes' }"
                   href="#obrigacoes">
                    <span class="material-symbols-outlined text-[18px]">person</span>
                    Obrigações do Usuário
                </a>
            </nav>
        </aside>
        
        <section class="flex-1">
            <div class="mb-12">

                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/10 text-primary text-xs font-bold mb-4">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
                    </span>
                    Última atualização: 24 de Maio de 2024
                </span>
                <h1 class="text-4xl md:text-5xl font-black text-[#111815]  tracking-tight leading-tight">
                    Responsabilidades Detalhadas
                </h1>
                <p class="mt-4 text-lg text-gray-500  max-w-2xl">
                    Esta seção detalha os limites da nossa atuação, os compromissos de serviço e as responsabilidades fundamentais do usuário ao utilizar o Everest.
                </p>
            </div>
            
            <div class="prose max-w-none">
                <div class="scroll-mt-32" id="responsabilidades">
                    <h2 class="text-2xl font-bold mt-12 mb-6 text-[#111815]  flex items-center gap-2">
                        1. Limites de Responsabilidade da Plataforma
                    </h2>
                    <p class="text-gray-600  leading-relaxed mb-6 text-base">
                        O Everest é fornecido "como está". Embora busquemos a excelência técnica, nossa responsabilidade é limitada aos seguintes termos:
                    </p>
                    <ul class="list-disc list-inside mb-6 space-y-2 text-gray-600 ">
                        <li>
                            <strong>Ferramenta de Apoio:</strong> O Everest é uma plataforma de produtividade e organização. Não garantimos que o uso da ferramenta resultará no atingimento das metas, uma vez que o sucesso depende exclusivamente da execução por parte do usuário.
                        </li>
                        <li>
                            <strong>Danos Indiretos:</strong> Em nenhuma circunstância o Everest será responsável por danos indiretos, incidentais, especiais ou consequentes decorrentes do uso ou da incapacidade de usar o serviço.
                        </li>
                        <li>
                            <strong>Integrações de Terceiros:</strong> Não nos responsabilizamos por falhas em serviços de terceiros integrados (como provedores de e-mail ou serviços de nuvem externos).
                        </li>
                    </ul>
                </div>
                
                <div class="scroll-mt-32" id="obrigacoes">
                    <h2 class="text-2xl font-bold mt-12 mb-6 text-[#111815]  flex items-center gap-2">
                        2. Obrigações e Conduta do Usuário
                    </h2>
                    <p class="text-gray-600  leading-relaxed mb-6 text-base">
                        Para manter a integridade do ecossistema Everest, o usuário compromete-se a:
                    </p>
                    <h3 class="text-lg font-bold mt-8 mb-4 text-[#111815] ">2.1 Segurança da Conta</h3>
                    <p class="text-gray-600  leading-relaxed mb-6 text-base">
                        O usuário é o único responsável por manter a confidencialidade de suas credenciais de acesso. <strong>É terminantemente proibido o compartilhamento de contas entre múltiplos usuários.</strong> Cada conta é pessoal e intransferível.
                    </p>
                    <h3 class="text-lg font-bold mt-8 mb-4 text-[#111815] ">2.2 Veracidade das Informações</h3>
                    <p class="text-gray-600  leading-relaxed mb-6 text-base">
                        O usuário garante que todas as informações fornecidas no cadastro e durante o uso da plataforma são verdadeiras e atualizadas. O uso de identidades falsas ou a inserção de dados maliciosos pode resultar na suspensão imediata da conta.
                    </p>
                    <h3 class="text-lg font-bold mt-8 mb-4 text-[#111815] ">2.3 Uso Lícito</h3>
                    <p class="text-gray-600  leading-relaxed mb-6 text-base">
                        A plataforma deve ser utilizada apenas para fins lícitos. É proibido o uso do Everest para armazenar conteúdo que viole direitos autorais, promova atividades ilegais ou contenha malware.
                    </p>
                </div>
                
                <div class="scroll-mt-32" id="disponibilidade">
                    <h2 class="text-2xl font-bold mt-12 mb-6 text-[#111815]  flex items-center gap-2">
                        3. Disponibilidade do Serviço (Uptime)
                    </h2>
                    <p class="text-gray-600  leading-relaxed mb-6 text-base">
                        O Everest empenha-se comercialmente para manter uma taxa de disponibilidade (uptime) de <strong>99,9%</strong> em base mensal.
                    </p>
                    <div class="bg-white  p-6 rounded-2xl border border-[#dbe6e1]  my-8">
                        <div class="flex items-start gap-4">
                            <span class="material-symbols-outlined text-primary">info</span>
                            <div>
                                <p class="text-sm font-bold text-[#111815]  mb-2">Janelas de Manutenção</p>
                                <p class="text-sm text-gray-500 mb-0">
                                    Manutenções programadas que possam afetar a disponibilidade serão comunicadas com pelo menos 24 horas de antecedência através do painel do sistema ou e-mail cadastrado.
                                </p>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-600  leading-relaxed mb-6 text-base">
                        A garantia de uptime não se aplica a falhas decorrentes de:
                    </p>
                    <ul class="list-disc list-inside mb-6 space-y-2 text-gray-600 ">
                        <li>Interrupções globais da infraestrutura de internet.</li>
                        <li>Problemas técnicos nos dispositivos ou conexão do próprio usuário.</li>
                        <li>Eventos de força maior ou casos fortuitos que fujam ao controle técnico da plataforma.</li>
                    </ul>
                </div>
                
                <div class="scroll-mt-32" id="suporte">
                    <h2 class="text-2xl font-bold mt-12 mb-6 text-[#111815]  flex items-center gap-2">
                        4. Notificações e Suporte
                    </h2>
                    <p class="text-gray-600  leading-relaxed mb-6 text-base">
                        Caso o usuário identifique qualquer falha de segurança ou uso indevido de sua conta, deve notificar o Everest imediatamente através dos nossos canais oficiais de suporte.
                    </p>
                </div>
            </div>
            
            <div class="lg:hidden mt-16 p-8 bg-white  rounded-3xl border border-[#dbe6e1] ">
                <p class="font-bold text-lg mb-2">Precisa de ajuda jurídica?</p>
                <p class="text-gray-500  mb-6">Nossa equipe está pronta para esclarecer qualquer ponto sobre as responsabilidades de uso.</p>
                <a class="w-full inline-flex items-center justify-center gap-2 bg-primary text-[#111815] font-bold py-4 rounded-full shadow-lg shadow-primary/20" href="mailto:legal@everest.app">
                    Contactar Suporte Jurídico
                </a>
            </div>
        </section>
    </main>
    

</div>
