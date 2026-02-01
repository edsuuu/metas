import React, { useEffect } from 'react';
import { Head, Link } from '@inertiajs/react';
import LegalNavbar from '@/Components/LegalNavbar';

export default function Terms() {
    useEffect(() => {
        const handleScroll = () => {
            const sections = document.querySelectorAll('div[id]');
            const navLinks = document.querySelectorAll('.nav-link');
            let current = '';

            sections.forEach(section => {
                const sectionTop = (section as HTMLElement).offsetTop;
                if (window.pageYOffset >= sectionTop - 150) {
                    current = section.getAttribute('id') || '';
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href')?.includes(current)) {
                    link.classList.add('active');
                }
            });
        };

        window.addEventListener('scroll', handleScroll);
        return () => window.removeEventListener('scroll', handleScroll);
    }, []);

    return (
        <div className="bg-background-light dark:bg-background-dark text-[#111815] transition-colors duration-300 min-h-screen font-display">
            <Head title="Termos e Privacidade" />

            <LegalNavbar />

            <main className="max-w-[1200px] mx-auto px-4 md:px-12 py-12 lg:flex lg:gap-16">
                <aside className="hidden lg:block w-72 h-fit sticky top-28">
                    <nav className="flex flex-col gap-1">
                        <h3 className="text-xs font-bold uppercase tracking-widest text-gray-400 mb-4 px-4">Tópicos</h3>
                        <a className="nav-link px-4 py-3 text-sm font-semibold rounded-lg text-gray-600 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-800 transition-all flex items-center gap-3" href="#introducao">
                            <span className="material-symbols-outlined text-[18px]">info</span> Introdução
                        </a>
                        <a className="nav-link px-4 py-3 text-sm font-semibold rounded-lg text-gray-600 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-800 transition-all flex items-center gap-3" href="#coleta">
                            <span className="material-symbols-outlined text-[18px]">database</span> Coleta de Dados
                        </a>
                        <a className="nav-link px-4 py-3 text-sm font-semibold rounded-lg text-gray-600 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-800 transition-all flex items-center gap-3" href="#cookies">
                            <span className="material-symbols-outlined text-[18px]">cookie</span> Uso de Cookies
                        </a>
                        <a className="nav-link px-4 py-3 text-sm font-semibold rounded-lg text-gray-600 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-800 transition-all flex items-center gap-3" href="#seguranca">
                            <span className="material-symbols-outlined text-[18px]">shield</span> Segurança
                        </a>
                        <a className="nav-link px-4 py-3 text-sm font-semibold rounded-lg text-gray-600 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-800 transition-all flex items-center gap-3" href="#responsabilidades">
                            <span className="material-symbols-outlined text-[18px]">gavel</span> Responsabilidades
                        </a>

                    </nav>
                </aside>
                <section className="flex-1">
                    <div className="mb-12">
                        <span className="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/10 text-primary text-xs font-bold mb-4">
                            <span className="relative flex h-2 w-2">
                                <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                                <span className="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
                            </span>
                            Última atualização: 24 de Maio de 2024
                        </span>
                        <h1 className="text-4xl md:text-5xl font-black text-[#111815] dark:text-white tracking-tight leading-tight">Termos de Serviço e Política de Privacidade</h1>
                        <p className="mt-4 text-lg text-gray-500 dark:text-gray-400 max-w-2xl">Transparência é a base da nossa jornada. Saiba como protegemos seus dados e quais são nossos compromissos mútuos.</p>
                    </div>
                    <div className="prose max-w-none">
                        <div className="scroll-mt-32" id="introducao">
                            <h2 className="text-2xl font-bold mt-12 mb-6 text-[#111815] dark:text-white flex items-center gap-2">1. Introdução</h2>
                            <p className="text-gray-600 dark:text-gray-400 leading-relaxed mb-6 text-base">Bem-vindo ao Everest. Ao utilizar nossa plataforma de gestão de metas pessoais, você concorda em cumprir estes termos. O Everest foi projetado para ajudá-lo a alcançar seu potencial máximo, respeitando sua privacidade e garantindo a segurança de sua jornada.</p>
                            <p className="text-gray-600 dark:text-gray-400 leading-relaxed mb-6 text-base">Estes termos regem o uso do nosso site, aplicativos móveis e serviços relacionados. Se você não concordar com qualquer parte destes termos, recomendamos não utilizar os nossos serviços.</p>
                            <Link href={route('terms.intro')} className="flex items-center gap-2 text-primary font-bold hover:underline">
                                Ver Introdução Detalhada <span className="material-symbols-outlined text-sm">arrow_forward</span>
                            </Link>
                        </div>
                        <div className="scroll-mt-32" id="coleta">
                            <h2 className="text-2xl font-bold mt-12 mb-6 text-[#111815] dark:text-white flex items-center gap-2">2. Coleta de Dados</h2>
                            <p className="text-gray-600 dark:text-gray-400 leading-relaxed mb-6 text-base">Para fornecer a melhor experiência de acompanhamento de metas, coletamos informações essenciais que você nos fornece:</p>
                            <ul className="list-disc list-inside mb-6 space-y-2 text-gray-600 dark:text-gray-400">
                                <li>Informações de conta (nome, e-mail, nickname e senha criptografada).</li>
                                <li>Conteúdo de metas, prazos e métricas de progresso inseridos por você.</li>
                                <li>Dados técnicos de acesso, como endereço IP e tipo de dispositivo, para otimização de performance.</li>
                            </ul>
                            <p className="text-gray-600 dark:text-gray-400 leading-relaxed mb-6 text-base">Seus dados de progresso pessoal nunca são vendidos a terceiros. Eles existem apenas para alimentar o seu dashboard e fornecer insights personalizados.</p>
                            <Link href={route('terms.data-collection')} className="flex items-center gap-2 text-primary font-bold hover:underline">
                                Ver Detalhes da Coleta <span className="material-symbols-outlined text-sm">arrow_forward</span>
                            </Link>
                        </div>
                        <div className="scroll-mt-32" id="cookies">
                            <h2 className="text-2xl font-bold mt-12 mb-6 text-[#111815] dark:text-white flex items-center gap-2">3. Uso de Cookies</h2>
                            <p className="text-gray-600 dark:text-gray-400 leading-relaxed mb-6 text-base">Utilizamos cookies para melhorar a navegação e entender como você interage com o Everest. Os cookies nos ajudam a manter sua sessão ativa e a lembrar suas preferências de visualização (como o Modo Escuro).</p>
                            <p className="text-gray-600 dark:text-gray-400 leading-relaxed mb-6 text-base">Você pode gerenciar ou desabilitar cookies através das configurações do seu navegador, mas esteja ciente de que certas funcionalidades da plataforma podem ser limitadas.</p>
                        </div>
                        <div className="scroll-mt-32" id="seguranca">
                            <h2 className="text-2xl font-bold mt-12 mb-6 text-[#111815] dark:text-white flex items-center gap-2">4. Segurança da Informação</h2>
                            <p className="text-gray-600 dark:text-gray-400 leading-relaxed mb-6 text-base">Segurança não é opcional no Everest. Implementamos camadas de proteção de nível bancário para garantir que sua jornada ao topo esteja segura:</p>
                            <p className="text-gray-600 dark:text-gray-400 leading-relaxed mb-6 text-base">Utilizamos criptografia SSL/TLS para todos os dados em trânsito e armazenamento seguro para informações sensíveis. Além disso, incentivamos o uso de senhas fortes e autenticação de dois fatores.</p>
                            <Link href={route('terms.security')} className="flex items-center gap-2 text-primary font-bold hover:underline">
                                Ver Política de Segurança <span className="material-symbols-outlined text-sm">arrow_forward</span>
                            </Link>
                        </div>
                        <div className="scroll-mt-32" id="responsabilidades">
                            <h2 className="text-2xl font-bold mt-12 mb-6 text-[#111815] dark:text-white flex items-center gap-2">5. Responsabilidades</h2>
                            <p className="text-gray-600 dark:text-gray-400 leading-relaxed mb-6 text-base">O Everest é uma ferramenta de auxílio. Embora façamos o máximo para manter o serviço disponível 24/7, não nos responsabilizamos por perdas de dados decorrentes de mau uso da conta ou falhas técnicas externas.</p>
                            <p className="text-gray-600 dark:text-gray-400 leading-relaxed mb-6 text-base">Você é responsável pela veracidade das informações inseridas e pelo sigilo de suas credenciais de acesso.</p>
                            <Link href={route('terms.responsibilities')} className="flex items-center gap-2 text-primary font-bold hover:underline">
                                Ver Responsabilidades <span className="material-symbols-outlined text-sm">arrow_forward</span>
                            </Link>
                        </div>
                    </div>
                    <div className="lg:hidden mt-16 p-8 bg-white dark:bg-gray-800 rounded-3xl border border-[#dbe6e1] dark:border-gray-700">
                        <p className="font-bold text-lg mb-2">Dúvidas sobre sua privacidade?</p>
                        <p className="text-gray-500 dark:text-gray-400 mb-6">Nossa equipe está pronta para ajudar você a entender melhor como cuidamos dos seus dados.</p>
                        <a className="w-full inline-flex items-center justify-center gap-2 bg-primary text-[#111815] font-bold py-4 rounded-full shadow-lg shadow-primary/20" href="#">
                            Contactar Suporte Jurídico
                        </a>
                    </div>
                </section>
            </main>
            <footer className="w-full py-12 px-4 border-t border-[#dbe6e1] dark:border-gray-800 mt-20">
                <div className="max-w-[1200px] mx-auto flex flex-col md:flex-row justify-between items-center gap-8">
                    <div className="flex items-center gap-3">
                        <div className="size-6 text-primary">
                            <svg fill="currentColor" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                                <path clipRule="evenodd" d="M24 4H42V17.3333V30.6667H24V44H6V30.6667V17.3333H24V44Z" fillRule="evenodd"></path>
                            </svg>
                        </div>
                        <span className="text-[#111815] dark:text-white font-bold">Everest</span>
                    </div>
                    <div className="flex gap-8 text-xs font-medium text-gray-500">
                        <Link className="hover:text-primary transition-colors" href={route('terms')}>Termos de Uso</Link>
                        <Link className="hover:text-primary transition-colors" href={route('privacy')}>Privacidade</Link>
                        <Link className="hover:text-primary transition-colors" href={route('terms')}>Cookies</Link>
                        <Link className="hover:text-primary transition-colors" href={route('terms.security')}>Segurança</Link>
                    </div>
                    <p className="text-xs text-gray-400">© 2024 Everest Technologies Inc. Todos os direitos reservados.</p>
                </div>
            </footer>
        </div>
    );
}
