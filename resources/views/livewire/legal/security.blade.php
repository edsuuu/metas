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
                    Segurança
                </h3>
                <a class="px-4 py-3 text-sm font-semibold rounded-lg text-gray-600  hover:bg-white  transition-all flex items-center gap-3" 
                   :class="{ 'active': current === 'infraestrutura' }"
                   href="#infraestrutura">
                    <span class="material-symbols-outlined text-[18px]">account_tree</span>
                    Infraestrutura
                </a>
                <a class="px-4 py-3 text-sm font-semibold rounded-lg text-gray-600  hover:bg-white  transition-all flex items-center gap-3" 
                   :class="{ 'active': current === 'criptografia' }"
                   href="#criptografia">
                    <span class="material-symbols-outlined text-[18px]">lock</span>
                    Criptografia
                </a>
                <a class="px-4 py-3 text-sm font-semibold rounded-lg text-gray-600  hover:bg-white  transition-all flex items-center gap-3" 
                   :class="{ 'active': current === 'backup' }"
                   href="#backup">
                    <span class="material-symbols-outlined text-[18px]">cloud_sync</span>
                    Backups e Redundância
                </a>
                <a class="px-4 py-3 text-sm font-semibold rounded-lg text-gray-600  hover:bg-white  transition-all flex items-center gap-3" 
                   :class="{ 'active': current === 'boas-praticas' }"
                   href="#boas-praticas">
                    <span class="material-symbols-outlined text-[18px]">verified_user</span>
                    Boas Práticas
                </a>

                <div class="mt-8 p-6 bg-primary/5 rounded-2xl border border-primary/20">
                    <p class="text-sm font-bold text-[#111815]  mb-2">
                        Segurança em Primeiro Lugar
                    </p>
                    <p class="text-xs text-gray-500 mb-4">
                        Seus dados e metas são protegidos por tecnologia de ponta.
                    </p>
                    <a class="inline-flex items-center gap-2 text-xs font-bold text-primary hover:underline" href="{{ route('support.index') }}">
                        Central de Ajuda <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>
            </nav>
        </aside>
        
        <section class="flex-1">

            <div class="mb-12">
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/10 text-primary text-xs font-bold mb-4">
                    <span class="material-symbols-outlined text-sm">verified</span>
                    Certificado de Segurança Atualizado
                </span>
                <h1 class="text-4xl md:text-5xl font-black text-[#111815]  tracking-tight leading-tight">
                    Segurança Detalhada
                </h1>
                <p class="mt-4 text-lg text-gray-500  max-w-2xl">
                    Entenda como construímos uma fortaleza digital para proteger suas ambições e sua privacidade.
                </p>
            </div>
            
            <div class="prose max-w-none">
                <div class="scroll-mt-32" id="infraestrutura">
                    <h2 class="text-2xl font-bold mt-12 mb-6 text-[#111815]  flex items-center gap-2">
                        1. Infraestrutura e Firewalls
                    </h2>
                    <p class="text-gray-600  leading-relaxed mb-6 text-base">
                        Nossa infraestrutura é hospedada em centros de dados de classe mundial que possuem certificações rigorosas de segurança (ISO 27001, SOC 2). Utilizamos múltiplas camadas de proteção perimetral para garantir que apenas tráfego legítimo chegue aos nossos servidores.
                    </p>
                    <div class="grid md:grid-cols-2 gap-4 mb-8">
                        <div class="p-6 bg-white  border border-[#dbe6e1]  rounded-2xl transition-all hover:shadow-md">
                            <span class="material-symbols-outlined text-primary mb-3">security</span>
                            <h4 class="font-bold mb-2 text-[#111815] ">Firewalls Inteligentes (WAF)</h4>
                            <p class="text-sm mb-0 text-gray-600 ">
                                Proteção ativa contra ataques de negação de serviço (DDoS) e injeções de código em tempo real.
                            </p>
                        </div>
                        <div class="p-6 bg-white  border border-[#dbe6e1]  rounded-2xl transition-all hover:shadow-md">
                            <span class="material-symbols-outlined text-primary mb-3">router</span>
                            <h4 class="font-bold mb-2 text-[#111815] ">Isolamento de Redes</h4>
                            <p class="text-sm mb-0 text-gray-600 ">
                                Seus dados sensíveis residem em redes isoladas, sem acesso direto pela internet pública.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="scroll-mt-32" id="criptografia">
                    <h2 class="text-2xl font-bold mt-12 mb-6 text-[#111815]  flex items-center gap-2">
                        2. Criptografia de Ponta a Ponta
                    </h2>
                    <p class="text-gray-600  leading-relaxed mb-6 text-base">
                        No Everest, a privacidade não é apenas uma configuração, é um pilar arquitetural. Aplicamos protocolos de criptografia robustos tanto para dados em repouso quanto em trânsito.
                    </p>
                    <ul class="list-none mb-6 space-y-3 text-gray-600 ">
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-primary text-lg">check_circle</span>
                            <span>
                                <strong>Trânsito:</strong> Toda comunicação entre seu dispositivo e nossos servidores é protegida via TLS 1.3 de 256 bits.
                            </span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-primary text-lg">check_circle</span>
                            <span>
                                <strong>Repouso:</strong> Dados de metas são criptografados em nossos bancos de dados usando AES-256.
                            </span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-primary text-lg">check_circle</span>
                            <span>
                                <strong>Hashing:</strong> Senhas nunca são armazenadas em texto simples; utilizamos algoritmos de hashing de última geração com salt individual.
                            </span>
                        </li>
                    </ul>
                </div>
                
                <div class="scroll-mt-32" id="backup">
                    <h2 class="text-2xl font-bold mt-12 mb-6 text-[#111815]  flex items-center gap-2">
                        3. Backups e Resiliência
                    </h2>
                    <p class="text-gray-600  leading-relaxed mb-6 text-base">
                        Sabemos que o progresso de uma vida está em suas metas. Por isso, garantimos que seus dados sobrevivam a qualquer imprevisto técnico através de uma política de backup rigorosa.
                    </p>
                    <div class="bg-white  p-8 rounded-3xl border border-[#dbe6e1]  my-8">
                        <h3 class="text-xl font-bold mt-0 mb-4 text-[#111815]  flex items-center gap-2">
                            Protocolo de Disponibilidade
                        </h3>
                        <p class="text-gray-600  leading-relaxed mb-6 text-base">
                            Realizamos backups incrementais a cada 15 minutos e backups completos diariamente. Todos os backups são replicados geograficamente em três regiões distintas para garantir a continuidade do serviço mesmo em desastres em larga escala.
                        </p>
                    </div>
                </div>
                
                <div class="scroll-mt-32" id="boas-praticas">
                    <h2 class="text-2xl font-bold mt-12 mb-6 text-primary flex items-center gap-2">
                        4. Como manter sua conta segura
                    </h2>
                    <p class="text-gray-600  leading-relaxed mb-6 text-base">
                        A segurança é uma responsabilidade compartilhada. Preparamos algumas dicas essenciais para você blindar seu acesso ao Everest:
                    </p>
                    <div class="space-y-4 mb-12">
                        <div class="flex gap-6 p-6 rounded-2xl bg-primary/5 border border-primary/20">
                            <div class="bg-primary text-white size-12 rounded-xl flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined">vibration</span>
                            </div>
                            <div>
                                <h4 class="font-bold text-[#111815]  mb-1">Ative o 2FA (Autenticação de Dois Fatores)</h4>
                                <p class="text-sm text-gray-500 mb-0">
                                    Adicione uma camada extra. Mesmo que alguém descubra sua senha, não conseguirá acessar sua conta sem o código do seu celular.
                                </p>
                            </div>
                        </div>
                        <div class="flex gap-6 p-6 rounded-2xl bg-white  border border-[#dbe6e1] ">
                            <div class="bg-gray-100  text-gray-500 size-12 rounded-xl flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined">password</span>
                            </div>
                            <div>
                                <h4 class="font-bold text-[#111815]  mb-1">Use Senhas Fortes e Únicas</h4>
                                <p class="text-sm text-gray-500 mb-0">
                                    Evite datas de aniversário ou sequências simples. Recomendamos o uso de um gerenciador de senhas para criar combinações complexas.
                                </p>
                            </div>
                        </div>
                        <div class="flex gap-6 p-6 rounded-2xl bg-white  border border-[#dbe6e1] ">
                            <div class="bg-gray-100  text-gray-500 size-12 rounded-xl flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined">devices</span>
                            </div>
                            <div>
                                <h4 class="font-bold text-[#111815]  mb-1">Monitore Sessões Ativas</h4>
                                <p class="text-sm text-gray-500 mb-0">
                                    Verifique regularmente na sua aba de configurações quais dispositivos estão conectados e encerre sessões desconhecidas.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="lg:hidden mt-16 p-8 bg-white  rounded-3xl border border-[#dbe6e1]  text-center">
                <span class="material-symbols-outlined text-primary text-4xl mb-4">support_agent</span>
                <p class="font-bold text-lg mb-2">Identificou algo suspeito?</p>
                <p class="text-gray-500 mb-6">Reporte vulnerabilidades ou atividades incomuns imediatamente para nosso time de segurança.</p>
                <a class="w-full inline-flex items-center justify-center gap-2 bg-primary text-[#111815] font-bold py-4 rounded-full shadow-lg shadow-primary/20" href="mailto:security@everest.app">
                    Reportar Problema
                </a>
            </div>
        </section>
    </main>
    

</div>
