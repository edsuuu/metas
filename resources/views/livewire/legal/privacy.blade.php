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
            <nav class="flex flex-col gap-1">
                <h3 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-4 px-4">
                    Documentação
                </h3>
                <a class="px-4 py-3 text-sm font-semibold rounded-lg text-gray-600  hover:bg-white  transition-all flex items-center gap-3" href="{{ route('legal.terms.index') }}">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                    Voltar aos Termos
                </a>
                <div class="h-px bg-gray-200  my-2"></div>
                <a class="px-4 py-3 text-sm font-semibold rounded-lg text-gray-600  hover:bg-white  transition-all flex items-center gap-3"
                   :class="{ 'active': current === 'tipos-dados' }"
                   href="#tipos-dados">
                    <span class="material-symbols-outlined text-[18px]">database</span>
                    Tipos de Dados
                </a>
                <a class="px-4 py-3 text-sm font-semibold rounded-lg text-gray-600  hover:bg-white  transition-all flex items-center gap-3"
                   :class="{ 'active': current === 'finalidade' }"
                   href="#finalidade">
                    <span class="material-symbols-outlined text-[18px]">target</span>
                    Finalidade
                </a>
                <a class="px-4 py-3 text-sm font-semibold rounded-lg text-gray-600  hover:bg-white  transition-all flex items-center gap-3"
                   :class="{ 'active': current === 'exclusao' }"
                   href="#exclusao">
                    <span class="material-symbols-outlined text-[18px]">delete</span>
                    Exclusão de Dados
                </a>
            </nav>
        </aside>
        
        <section class="flex-1">
            <div class="mb-12">
                <nav class="flex items-center gap-2 text-xs font-bold text-gray-400 mb-6 uppercase tracking-wider">
                    <a class="hover:text-primary" href="{{ route('legal.terms.index') }}">
                        Privacidade
                    </a>
                    <span class="material-symbols-outlined text-xs">chevron_right</span>
                    <span class="text-primary">
                        Coleta de Dados Detalhada
                    </span>
                </nav>
                <h1 class="text-4xl md:text-5xl font-black text-[#111815]  tracking-tight leading-tight">
                    Coleta de Dados
                </h1>
                <p class="mt-4 text-lg text-gray-500  max-w-2xl">
                    Entenda exatamente quais informações o Everest processa para ajudar você a atingir seus objetivos.
                </p>
            </div>
            
            <div class="prose max-w-none">
                <div class="scroll-mt-32" id="tipos-dados">
                    <h2 class="text-2xl font-bold mt-12 mb-6 text-[#111815]  flex items-center gap-2">
                        1. Quais dados coletamos?
                    </h2>
                    <p class="text-gray-600  leading-relaxed mb-6 text-base">
                        Para o funcionamento pleno do Everest, dividimos a coleta em três categorias principais. A transparência sobre o que acessamos é fundamental para a confiança entre o usuário e a plataforma.
                    </p>
                    <div class="overflow-hidden rounded-2xl border border-[#dbe6e1]  bg-white  my-8">
                        <table class="w-full data-table">
                            <thead>
                                <tr>
                                    <th class="text-left px-4 py-3 bg-gray-50  text-xs font-bold uppercase tracking-wider text-gray-500">
                                        Categoria
                                    </th>
                                    <th class="text-left px-4 py-3 bg-gray-50  text-xs font-bold uppercase tracking-wider text-gray-500">
                                        Exemplos de Dados
                                    </th>
                                    <th class="text-left px-4 py-3 bg-gray-50  text-xs font-bold uppercase tracking-wider text-gray-500">
                                        Necessário
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="px-4 py-4 border-t border-gray-100  text-sm text-gray-600  font-bold">
                                        Dados Pessoais
                                    </td>
                                    <td class="px-4 py-4 border-t border-gray-100  text-sm text-gray-600 ">
                                        Nome completo, endereço de e-mail, foto de perfil e fuso horário.
                                    </td>
                                    <td class="px-4 py-4 border-t border-gray-100  text-sm font-bold text-primary">
                                        Sim
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-4 border-t border-gray-100  text-sm text-gray-600  font-bold">
                                        Dados de Metas
                                    </td>
                                    <td class="px-4 py-4 border-t border-gray-100  text-sm text-gray-600 ">
                                        Títulos de objetivos, prazos, sub-tarefas, anotações de progresso e frequência.
                                    </td>
                                    <td class="px-4 py-4 border-t border-gray-100  text-sm font-bold text-primary">
                                        Sim
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-4 border-t border-gray-100  text-sm text-gray-600  font-bold">
                                        Dados de Uso
                                    </td>
                                    <td class="px-4 py-4 border-t border-gray-100  text-sm text-gray-600 ">
                                        Endereço IP, tipo de navegador e logs de erro.
                                    </td>
                                    <td class="px-4 py-4 border-t border-gray-100  text-sm text-gray-400">
                                        Opcional*
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-4 border-t border-gray-100  text-sm text-gray-600  font-bold">
                                        Dados Sociais
                                    </td>
                                    <td class="px-4 py-4 border-t border-gray-100  text-sm text-gray-600 ">
                                        Interações em metas compartilhadas (comentários e curtidas).
                                    </td>
                                    <td class="px-4 py-4 border-t border-gray-100  text-sm text-gray-400">
                                        Opcional
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <p class="text-sm italic text-gray-500">
                        * Dados de uso essenciais para segurança não podem ser desabilitados.
                    </p>
                </div>
                
                <div class="scroll-mt-32" id="finalidade">
                    <h2 class="text-2xl font-bold mt-12 mb-6 text-[#111815]  flex items-center gap-2">
                        2. Finalidade da Coleta
                    </h2>
                    <p class="text-gray-600  leading-relaxed mb-6 text-base">
                        O Everest não comercializa seus dados. Cada bit de informação coletado tem um propósito específico voltado para sua produtividade:
                    </p>
                    <ul class="list-disc list-inside mb-6 space-y-2 text-gray-600 ">
                        <li>
                            <strong>Personalização:</strong> Ajustamos o algoritmo de lembretes baseados no seu fuso horário e histórico de conclusão.
                        </li>
                        <li>
                            <strong>Insights:</strong> Geramos gráficos de desempenho baseados exclusivamente nos seus dados de metas.
                        </li>
                        <li>
                            <strong>Segurança:</strong> Utilizamos dados de acesso para identificar tentativas de login suspeitas e proteger sua conta.
                        </li>
                        <li>
                            <strong>Suporte:</strong> Dados técnicos nos ajudam a resolver bugs específicos que você possa encontrar na plataforma.
                        </li>
                    </ul>
                </div>
                
                <div class="scroll-mt-32" id="exclusao">
                    <h2 class="text-2xl font-bold mt-12 mb-6 text-[#111815]  flex items-center gap-2">
                        3. Exclusão e Portabilidade
                    </h2>
                    <p class="text-gray-600  leading-relaxed mb-6 text-base">
                        Acreditamos que você é o único dono dos seus dados. Por isso, oferecemos autonomia total sobre suas informações:
                    </p>
                    <div class="grid md:grid-cols-2 gap-6 my-8">
                        <div class="p-6 rounded-2xl bg-white  border border-[#dbe6e1] ">
                            <span class="material-symbols-outlined text-primary mb-4 text-3xl">download</span>
                            <h4 class="font-bold text-[#111815]  mb-2">Exportar Meus Dados</h4>
                            <p class="text-sm text-gray-500 mb-0">
                                Você pode solicitar um arquivo .JSON com todas as suas metas e histórico entrando em contato com nosso suporte via e-mail.
                            </p>
                        </div>
                        <div class="p-6 rounded-2xl bg-white  border border-[#dbe6e1] ">
                            <span class="material-symbols-outlined text-red-500 mb-4 text-3xl">delete_forever</span>
                            <h4 class="font-bold text-[#111815]  mb-2">Excluir Conta</h4>
                            <p class="text-sm text-gray-500 mb-0">
                                Ao excluir sua conta, todos os dados são removidos de nossos servidores em até 30 dias, sem possibilidade de recuperação.
                            </p>
                        </div>
                    </div>
                    <p class="text-gray-600  leading-relaxed mb-6 text-base">
                        Para tirar dúvidas sobre o processo, você pode entrar em contato através do e-mail <a class="text-primary font-bold hover:underline" href="mailto:privacy@everest.app">privacy@everest.app</a>.
                    </p>
                </div>
            </div>
            
            <div class="lg:hidden mt-16 p-8 bg-white  rounded-3xl border border-[#dbe6e1] ">
                <p class="font-bold text-lg mb-2">Dúvidas sobre sua privacidade?</p>
                <p class="text-gray-500  mb-6">Nossa equipe está pronta para ajudar você a entender melhor como cuidamos dos seus dados.</p>
                <a class="w-full inline-flex items-center justify-center gap-2 bg-primary text-[#111815] font-bold py-4 rounded-full shadow-lg shadow-primary/20" href="#">
                    Contactar Suporte Jurídico
                </a>
            </div>
        </section>
    </main>
    

</div>
