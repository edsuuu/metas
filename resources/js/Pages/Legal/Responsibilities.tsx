import React, { useEffect } from 'react';
import { Head, Link } from '@inertiajs/react';
import LegalNavbar from '@/Components/LegalNavbar';

declare function route(name: string, params?: any, absolute?: boolean): string;

export default function Responsibilities() {
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
                if(!link.getAttribute('href')?.startsWith('#')) return;

                 link.classList.remove('active');
                 if (link.getAttribute('href')?.includes(current) && current !== '') {
                     link.classList.add('active');
                 }
             });
        };
        window.addEventListener('scroll', handleScroll);
        return () => window.removeEventListener('scroll', handleScroll);
    }, []);

    return (
        <div className="bg-background-light dark:bg-background-dark text-[#111815] transition-colors duration-300 min-h-screen font-display">
            <Head title="Everest - Responsabilidades Detalhadas" />

            <LegalNavbar />

            <main className="max-w-[1200px] mx-auto px-4 md:px-12 py-12 lg:flex lg:gap-16">
                <aside className="hidden lg:block w-72 h-fit sticky top-28">
                    <nav className="flex flex-col gap-1">
                         <h3 className="text-xs font-bold uppercase tracking-widest text-gray-400 mb-4 px-4">Documentação</h3>
                        <Link className="nav-link px-4 py-3 text-sm font-semibold rounded-lg text-gray-600 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-800 transition-all flex items-center gap-3" href={route('terms')}>
                            <span className="material-symbols-outlined text-[18px]">description</span> Termos Gerais
                        </Link>
                        <a className="nav-link active px-4 py-3 text-sm font-semibold rounded-lg text-gray-600 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-800 transition-all flex items-center gap-3" href="#responsabilidades">
                            <span className="material-symbols-outlined text-[18px]">gavel</span> Responsabilidades
                        </a>
                        <a className="nav-link px-4 py-3 text-sm font-semibold rounded-lg text-gray-600 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-800 transition-all flex items-center gap-3" href="#disponibilidade">
                            <span className="material-symbols-outlined text-[18px]">update</span> Disponibilidade (Uptime)
                        </a>
                        <a className="nav-link px-4 py-3 text-sm font-semibold rounded-lg text-gray-600 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-800 transition-all flex items-center gap-3" href="#obrigacoes">
                            <span className="material-symbols-outlined text-[18px]">person</span> Obrigações do Usuário
                        </a>

                    </nav>
                </aside>

                <section className="flex-1">
                    <div className="mb-12">
                         <nav className="flex items-center gap-2 text-xs font-medium text-gray-400 mb-6">
                            <Link className="hover:text-primary transition-colors" href="/terms">Legal</Link>
                            <span className="material-symbols-outlined text-xs">chevron_right</span>
                            <span className="text-gray-600 dark:text-gray-300">Responsabilidades</span>
                        </nav>
                        <span className="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/10 text-primary text-xs font-bold mb-4">
                            <span className="relative flex h-2 w-2">
                                <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                                <span className="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
                            </span>
                            Última atualização: 24 de Maio de 2024
                        </span>
                        <h1 className="text-4xl md:text-5xl font-black text-[#111815] dark:text-white tracking-tight leading-tight">Responsabilidades Detalhadas</h1>
                        <p className="mt-4 text-lg text-gray-500 dark:text-gray-400 max-w-2xl">Esta seção detalha os limites da nossa atuação, os compromissos de serviço e as responsabilidades fundamentais do usuário ao utilizar o Everest.</p>
                    </div>

                    <div className="prose max-w-none">
                        <div className="scroll-mt-32" id="responsabilidades">
                            <h2 className="text-2xl font-bold mt-12 mb-6 text-[#111815] dark:text-white flex items-center gap-2">1. Limites de Responsabilidade da Plataforma</h2>
                            <p className="text-gray-600 dark:text-gray-400 leading-relaxed mb-6 text-base">O Everest é fornecido &quot;como está&quot;. Embora busquemos a excelência técnica, nossa responsabilidade é limitada aos seguintes termos:</p>
                            <ul className="list-disc list-inside mb-6 space-y-2 text-gray-600 dark:text-gray-400">
                                <li><strong>Ferramenta de Apoio:</strong> O Everest é uma plataforma de produtividade e organização. Não garantimos que o uso da ferramenta resultará no atingimento das metas, uma vez que o sucesso depende exclusivamente da execução por parte do usuário.</li>
                                <li><strong>Danos Indiretos:</strong> Em nenhuma circunstância o Everest será responsável por danos indiretos, incidentais, especiais ou consequentes decorrentes do uso ou da incapacidade de usar o serviço.</li>
                                <li><strong>Integrações de Terceiros:</strong> Não nos responsabilizamos por falhas em serviços de terceiros integrados (como provedores de e-mail ou serviços de nuvem externos).</li>
                            </ul>
                        </div>
                        <div className="scroll-mt-32" id="obrigacoes">
                            <h2 className="text-2xl font-bold mt-12 mb-6 text-[#111815] dark:text-white flex items-center gap-2">2. Obrigações e Conduta do Usuário</h2>
                            <p className="text-gray-600 dark:text-gray-400 leading-relaxed mb-6 text-base">Para manter a integridade do ecossistema Everest, o usuário compromete-se a:</p>
                            <h3 className="text-lg font-bold mt-8 mb-4 text-[#111815] dark:text-white">2.1 Segurança da Conta</h3>
                            <p className="text-gray-600 dark:text-gray-400 leading-relaxed mb-6 text-base">O usuário é o único responsável por manter a confidencialidade de suas credenciais de acesso. <strong>É terminantemente proibido o compartilhamento de contas entre múltiplos usuários.</strong> Cada licença é pessoal e intransferível.</p>
                            <h3 className="text-lg font-bold mt-8 mb-4 text-[#111815] dark:text-white">2.2 Veracidade das Informações</h3>
                            <p className="text-gray-600 dark:text-gray-400 leading-relaxed mb-6 text-base">O usuário garante que todas as informações fornecidas no cadastro e durante o uso da plataforma são verdadeiras e atualizadas. O uso de identidades falsas ou a inserção de dados maliciosos pode resultar na suspensão imediata da conta.</p>
                            <h3 className="text-lg font-bold mt-8 mb-4 text-[#111815] dark:text-white">2.3 Uso Lícito</h3>
                            <p className="text-gray-600 dark:text-gray-400 leading-relaxed mb-6 text-base">A plataforma deve ser utilizada apenas para fins lícitos. É proibido o uso do Everest para armazenar conteúdo que viole direitos autorais, promova atividades ilegais ou contenha malware.</p>
                        </div>
                        <div className="scroll-mt-32" id="disponibilidade">
                            <h2 className="text-2xl font-bold mt-12 mb-6 text-[#111815] dark:text-white flex items-center gap-2">3. Disponibilidade do Serviço (Uptime)</h2>
                            <p className="text-gray-600 dark:text-gray-400 leading-relaxed mb-6 text-base">O Everest empenha-se comercialmente para manter uma taxa de disponibilidade (uptime) de <strong>99,9%</strong> em base mensal.</p>
                            <div className="bg-white dark:bg-gray-800/50 p-6 rounded-2xl border border-[#dbe6e1] dark:border-gray-700 my-8">
                                <div className="flex items-start gap-4">
                                    <span className="material-symbols-outlined text-primary">info</span>
                                    <div>
                                        <p className="text-sm font-bold text-[#111815] dark:text-white mb-2">Janelas de Manutenção</p>
                                        <p className="text-sm text-gray-500 mb-0">Manutenções programadas que possam afetar a disponibilidade serão comunicadas com pelo menos 24 horas de antecedência através do painel do sistema ou e-mail cadastrado.</p>
                                    </div>
                                </div>
                            </div>
                            <p className="text-gray-600 dark:text-gray-400 leading-relaxed mb-6 text-base">A garantia de uptime não se aplica a falhas decorrentes de:</p>
                            <ul className="list-disc list-inside mb-6 space-y-2 text-gray-600 dark:text-gray-400">
                                <li>Interrupções globais da infraestrutura de internet.</li>
                                <li>Problemas técnicos nos dispositivos ou conexão do próprio usuário.</li>
                                <li>Eventos de força maior ou casos fortuitos que fujam ao controle técnico da plataforma.</li>
                            </ul>
                        </div>
                         <div className="scroll-mt-32" id="suporte">
                            <h2 className="text-2xl font-bold mt-12 mb-6 text-[#111815] dark:text-white flex items-center gap-2">4. Notificações e Suporte</h2>
                            <p className="text-gray-600 dark:text-gray-400 leading-relaxed mb-6 text-base">Caso o usuário identifique qualquer falha de segurança ou uso indevido de sua conta, deve notificar o Everest imediatamente através dos nossos canais oficiais de suporte.</p>
                        </div>
                    </div>
                     <div className="lg:hidden mt-16 p-8 bg-white dark:bg-gray-800 rounded-3xl border border-[#dbe6e1] dark:border-gray-700">
                        <p className="font-bold text-lg mb-2">Precisa de ajuda jurídica?</p>
                        <p className="text-gray-500 dark:text-gray-400 mb-6">Nossa equipe está pronta para esclarecer qualquer ponto sobre as responsabilidades de uso.</p>
                        <a className="w-full inline-flex items-center justify-center gap-2 bg-primary text-[#111815] font-bold py-4 rounded-full shadow-lg shadow-primary/20" href="mailto:legal@everest.app">
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
                        <Link className="hover:text-primary transition-colors" href={`${route('terms')}#cookies`}>Cookies</Link>
                        <Link className="hover:text-primary transition-colors" href={route('terms.security')}>Segurança</Link>
                    </div>
                    <p className="text-xs text-gray-400">© 2024 Everest Technologies Inc. Todos os direitos reservados.</p>
                </div>
            </footer>
        </div>
    );
}
