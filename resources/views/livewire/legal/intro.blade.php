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
                <h3 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-4 px-4">Documentação</h3>
                <a class="px-4 py-3 text-sm font-semibold rounded-lg text-gray-600  hover:bg-white  transition-all flex items-center gap-3" 
                   :class="{ 'active': current === 'introducao' || current === 'missao' || current === 'elegibilidade' || current === 'aceitacao' }"
                   href="#introducao">
                    <span class="material-symbols-outlined text-[18px]">info</span> Introdução
                </a>
                <a class="px-4 py-3 text-sm font-semibold rounded-lg text-gray-600  hover:bg-white  transition-all flex items-center gap-3" href="{{ route('legal.privacy') }}">
                    <span class="material-symbols-outlined text-[18px]">database</span> Coleta de Dados
                </a>
                <a class="px-4 py-3 text-sm font-semibold rounded-lg text-gray-600  hover:bg-white  transition-all flex items-center gap-3" href="{{ route('legal.terms.index') }}#cookies">
                    <span class="material-symbols-outlined text-[18px]">cookie</span> Uso de Cookies
                </a>
                <a class="px-4 py-3 text-sm font-semibold rounded-lg text-gray-600  hover:bg-white  transition-all flex items-center gap-3" href="{{ route('legal.terms.security') }}">
                    <span class="material-symbols-outlined text-[18px]">shield</span> Segurança
                </a>
                 <a class="px-4 py-3 text-sm font-semibold rounded-lg text-gray-600  hover:bg-white  transition-all flex items-center gap-3" href="{{ route('legal.terms.responsibilities') }}">
                    <span class="material-symbols-outlined text-[18px]">gavel</span> Responsabilidades
                </a>
            </nav>
        </aside>
        
        <section class="flex-1">
            <div class="mb-12">
                <nav class="flex items-center gap-2 text-sm text-gray-400 mb-6">
                    <a class="hover:text-primary transition-colors" href="{{ route('legal.terms.index') }}">Termos</a>
                    <span class="material-symbols-outlined text-base">chevron_right</span>
                    <span class="text-primary font-medium">Introdução</span>
                </nav>
                <h1 class="text-4xl md:text-5xl font-black text-[#111815]  tracking-tight leading-tight">Introdução Detalhada</h1>
                <p class="mt-4 text-lg text-gray-500  max-w-2xl">Entenda os pilares que sustentam a sua experiência no Everest e como firmamos nosso compromisso com o seu progresso.</p>
            </div>
            
            <div class="prose max-w-none">
                <div class="scroll-mt-32" id="introducao"> 
                     {{-- The HTML map 'introducao' to generic sections, but the content has 'Missão', 'Elegibilidade' etc. --}}
                </div> 
                
                <div class="scroll-mt-32" id="missao">
                    <h2 class="text-2xl font-bold mt-12 mb-6 text-[#111815]  flex items-center gap-2">Nossa Missão</h2>
                    <p class="text-gray-600  leading-relaxed mb-6 text-base">O Everest nasceu com um propósito claro: transformar a forma como as pessoas visualizam e conquistam suas metas pessoais. Acreditamos que o sucesso não é um destino isolado, mas uma jornada composta por pequenos passos consistentes.</p>
                    <p class="text-gray-600  leading-relaxed mb-6 text-base">Ao utilizar nossa plataforma, você se torna parte de um ecossistema focado em alta performance, clareza mental e disciplina, apoiado por tecnologia de ponta para gestão de tempo e prioridades.</p>
                </div>
                
                <div class="scroll-mt-32" id="elegibilidade">
                    <h2 class="text-2xl font-bold mt-12 mb-6 text-[#111815]  flex items-center gap-2">Quem pode usar (Elegibilidade)</h2>
                    <p class="text-gray-600  leading-relaxed mb-6 text-base">Para garantir a segurança e a integridade da nossa comunidade, estabelecemos critérios básicos de acesso:</p>
                    <ul class="list-disc list-inside mb-6 space-y-2 text-gray-600 ">
                        <li><strong>Idade Mínima:</strong> Você deve ter pelo menos 13 anos para criar uma conta individual. Menores de idade devem utilizar a plataforma sob supervisão de um responsável legal.</li>
                        <li><strong>Capacidade Jurídica:</strong> Você declara ter plena capacidade jurídica para concordar com estes termos.</li>
                        <li><strong>Uso Pessoal:</strong> As contas são pessoais e intransferíveis, focadas no desenvolvimento individual.</li>
                    </ul>
                </div>
                
                <div class="scroll-mt-32" id="aceitacao">
                    <h2 class="text-2xl font-bold mt-12 mb-6 text-[#111815]  flex items-center gap-2">Aceitação dos Termos</h2>
                    <p class="text-gray-600  leading-relaxed mb-6 text-base">O acesso e a utilização do Everest estão condicionados à sua aceitação e cumprimento destes Termos de Serviço. Estes termos aplicam-se a todos os visitantes, usuários e outras pessoas que acessem ou utilizem o Serviço.</p>
                    <p class="text-gray-600  leading-relaxed mb-6 text-base">Ao clicar em &quot;Criar Conta&quot; ou utilizar qualquer funcionalidade do App, você confirma que leu, entendeu e concorda em estar vinculado a estas regras. Se você não concordar com qualquer termo aqui descrito, solicitamos gentilmente que interrompa o uso imediatamente.</p>
                    <div class="bg-primary/5 p-6 rounded-2xl border-l-4 border-primary mt-8">
                        <h4 class="text-sm font-bold text-[#111815]  uppercase tracking-wider mb-2">Nota Importante</h4>
                        <p class="text-sm text-gray-600  mb-0 italic">Estes termos podem ser atualizados periodicamente para refletir melhorias no serviço ou mudanças regulatórias. Notificaremos você sobre alterações significativas através do e-mail cadastrado ou de avisos na própria plataforma.</p>
                    </div>
                </div>
                
                <div class="mt-16 flex items-center justify-between border-t border-[#dbe6e1]  pt-8">
                    <div class="flex flex-col">
                        <span class="text-xs text-gray-400 uppercase font-bold tracking-widest mb-1">Anterior</span>
                        <a class="text-gray-600  hover:text-primary font-bold flex items-center gap-1" href="{{ route('legal.terms.index') }}">
                            <span class="material-symbols-outlined text-sm">arrow_back</span> Visão Geral
                        </a>
                    </div>
                    <div class="flex flex-col items-end">
                        <span class="text-xs text-gray-400 uppercase font-bold tracking-widest mb-1">Próximo</span>
                        <a class="text-[#111815]  hover:text-primary font-bold flex items-center gap-1 text-right" href="{{ route('legal.privacy') }}">
                            Coleta de Dados <span class="material-symbols-outlined text-sm">arrow_forward</span>
                        </a>
                    </div>
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
