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
                <h3 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-4 px-4">Tópicos</h3>
                <a class="px-4 py-3 text-sm font-semibold rounded-lg text-gray-600  hover:bg-white  transition-all flex items-center gap-3" 
                   :class="{ 'active': current === 'introducao' }"
                   href="#introducao">
                    <span class="material-symbols-outlined text-[18px]">info</span> Introdução
                </a>
                <a class="px-4 py-3 text-sm font-semibold rounded-lg text-gray-600  hover:bg-white  transition-all flex items-center gap-3" 
                   :class="{ 'active': current === 'coleta' }"
                   href="#coleta">
                    <span class="material-symbols-outlined text-[18px]">database</span> Coleta de Dados
                </a>
                <a class="px-4 py-3 text-sm font-semibold rounded-lg text-gray-600  hover:bg-white  transition-all flex items-center gap-3" 
                   :class="{ 'active': current === 'cookies' }"
                   href="#cookies">
                    <span class="material-symbols-outlined text-[18px]">cookie</span> Uso de Cookies
                </a>
                <a class="px-4 py-3 text-sm font-semibold rounded-lg text-gray-600  hover:bg-white  transition-all flex items-center gap-3" 
                   :class="{ 'active': current === 'seguranca' }"
                   href="#seguranca">
                    <span class="material-symbols-outlined text-[18px]">shield</span> Segurança
                </a>
                <a class="px-4 py-3 text-sm font-semibold rounded-lg text-gray-600  hover:bg-white  transition-all flex items-center gap-3" 
                   :class="{ 'active': current === 'responsabilidades' }"
                   href="#responsabilidades">
                    <span class="material-symbols-outlined text-[18px]">gavel</span> Responsabilidades
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
                <h1 class="text-4xl md:text-5xl font-black text-[#111815]  tracking-tight leading-tight">Termos de Serviço e Política de Privacidade</h1>
                <p class="mt-4 text-lg text-gray-500  max-w-2xl">Transparência é a base da nossa jornada. Saiba como protegemos seus dados e quais são nossos compromissos mútuos.</p>
            </div>
            
            <div class="prose max-w-none">
                <div class="scroll-mt-32" id="introducao">
                    <h2 class="text-2xl font-bold mt-12 mb-6 text-[#111815]  flex items-center gap-2">1. Introdução</h2>
                    <p class="text-gray-600  leading-relaxed mb-6 text-base">Bem-vindo ao Everest. Ao utilizar nossa plataforma de gestão de metas pessoais, você concorda em cumprir estes termos. O Everest foi projetado para ajudá-lo a alcançar seu potencial máximo, respeitando sua privacidade e garantindo a segurança de sua jornada.</p>
                    <p class="text-gray-600  leading-relaxed mb-6 text-base">Estes termos regem o uso do nosso site, aplicativos móveis e serviços relacionados. Se você não concordar com qualquer parte destes termos, recomendamos não utilizar os nossos serviços.</p>
                    <a href="{{ route('legal.terms.intro') }}" class="flex items-center gap-2 text-primary font-bold hover:underline">
                        Ver Introdução Detalhada <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>
                
                <div class="scroll-mt-32" id="coleta">
                    <h2 class="text-2xl font-bold mt-12 mb-6 text-[#111815]  flex items-center gap-2">2. Coleta de Dados</h2>
                    <p class="text-gray-600  leading-relaxed mb-6 text-base">Para fornecer a melhor experiência de acompanhamento de metas, coletamos informações essenciais que você nos fornece:</p>
                    <ul class="list-disc list-inside mb-6 space-y-2 text-gray-600 ">
                        <li>Informações de conta (nome, e-mail, nickname e senha criptografada).</li>
                        <li>Conteúdo de metas, prazos e métricas de progresso inseridos por você.</li>
                        <li>Dados técnicos de acesso, como endereço IP e tipo de dispositivo, para otimização de performance.</li>
                    </ul>
                    <p class="text-gray-600  leading-relaxed mb-6 text-base">Seus dados de progresso pessoal nunca são vendidos a terceiros. Eles existem apenas para alimentar o seu dashboard e fornecer insights personalizados.</p>
                    <a href="{{ route('legal.privacy') }}" class="flex items-center gap-2 text-primary font-bold hover:underline">
                        Ver Detalhes da Coleta <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>
                
                <div class="scroll-mt-32" id="cookies">
                    <h2 class="text-2xl font-bold mt-12 mb-6 text-[#111815]  flex items-center gap-2">3. Uso de Cookies</h2>
                    <p class="text-gray-600  leading-relaxed mb-6 text-base">Utilizamos cookies para melhorar a navegação e entender como você interage com o Everest. Os cookies nos ajudam a manter sua sessão ativa e a lembrar suas preferências de visualização (como o Modo Escuro).</p>
                    <p class="text-gray-600  leading-relaxed mb-6 text-base">Você pode gerenciar ou desabilitar cookies através das configurações do seu navegador, mas esteja ciente de que certas funcionalidades da plataforma podem ser limitadas.</p>
                </div>
                
                <div class="scroll-mt-32" id="seguranca">
                    <h2 class="text-2xl font-bold mt-12 mb-6 text-[#111815]  flex items-center gap-2">4. Segurança da Informação</h2>
                    <p class="text-gray-600  leading-relaxed mb-6 text-base">Segurança não é opcional no Everest. Implementamos camadas de proteção de nível bancário para garantir que sua jornada ao topo esteja segura:</p>
                    <p class="text-gray-600  leading-relaxed mb-6 text-base">Utilizamos criptografia SSL/TLS para todos os dados em trânsito e armazenamento seguro para informações sensíveis. Além disso, incentivamos o uso de senhas fortes e autenticação de dois fatores.</p>
                    <a href="{{ route('legal.terms.security') }}" class="flex items-center gap-2 text-primary font-bold hover:underline">
                        Ver Política de Segurança <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>
                
                <div class="scroll-mt-32" id="responsabilidades">
                    <h2 class="text-2xl font-bold mt-12 mb-6 text-[#111815]  flex items-center gap-2">5. Responsabilidades</h2>
                    <p class="text-gray-600  leading-relaxed mb-6 text-base">O Everest é uma ferramenta de auxílio. Embora façamos o máximo para manter o serviço disponível 24/7, não nos responsabilizamos por perdas de dados decorrentes de mau uso da conta ou falhas técnicas externas.</p>
                    <p class="text-gray-600  leading-relaxed mb-6 text-base">Você é responsável pela veracidade das informações inseridas e pelo sigilo de suas credenciais de acesso.</p>
                    <a href="{{ route('legal.terms.responsibilities') }}" class="flex items-center gap-2 text-primary font-bold hover:underline">
                        Ver Responsabilidades <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
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
