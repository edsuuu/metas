import React, { useEffect } from 'react';
import { Head, Link } from '@inertiajs/react';
import LegalNavbar from '@/Components/LegalNavbar';

declare function route(name: string, params?: any, absolute?: boolean): string;

export default function TermsIntro() {
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
                     // Only add active class if it's the current section
                     // Note: The HTML had 'active' class hardcoded on the first link for this page.
                     // We can replicate that logic or let the scroll spy handle it.
                     // The scroll spy is better.
                     link.classList.add('active');
                }
            });
        };

        window.addEventListener('scroll', handleScroll);
        return () => window.removeEventListener('scroll', handleScroll);
    }, []);

    return (
        <div className="bg-background-light dark:bg-background-dark text-[#111815] transition-colors duration-300 min-h-screen font-display">
            <Head title="Everest - Termos: Introdução Detalhada" />

            <LegalNavbar />

            <main className="max-w-[1200px] mx-auto px-4 md:px-12 py-12 lg:flex lg:gap-16">
                <aside className="hidden lg:block w-72 h-fit sticky top-28">
                    <nav className="flex flex-col gap-1">
                        <h3 className="text-xs font-bold uppercase tracking-widest text-gray-400 mb-4 px-4">Documentação</h3>
                        <a className="nav-link active px-4 py-3 text-sm font-semibold rounded-lg text-gray-600 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-800 transition-all flex items-center gap-3" href="#introducao">
                            <span className="material-symbols-outlined text-[18px]">info</span> Introdução
                        </a>
                        <Link className="nav-link px-4 py-3 text-sm font-semibold rounded-lg text-gray-600 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-800 transition-all flex items-center gap-3" href={route('terms.data-collection')}>
                            <span className="material-symbols-outlined text-[18px]">database</span> Coleta de Dados
                        </Link>
                        <Link className="nav-link px-4 py-3 text-sm font-semibold rounded-lg text-gray-600 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-800 transition-all flex items-center gap-3" href={`${route('terms')}#cookies`}>
                            <span className="material-symbols-outlined text-[18px]">cookie</span> Uso de Cookies
                        </Link>
                        <Link className="nav-link px-4 py-3 text-sm font-semibold rounded-lg text-gray-600 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-800 transition-all flex items-center gap-3" href={route('terms.security')}>
                            <span className="material-symbols-outlined text-[18px]">shield</span> Segurança
                        </Link>
                         <Link className="nav-link px-4 py-3 text-sm font-semibold rounded-lg text-gray-600 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-800 transition-all flex items-center gap-3" href={route('terms.responsibilities')}>
                            <span className="material-symbols-outlined text-[18px]">gavel</span> Responsabilidades
                        </Link>

                    </nav>
                </aside>
                <section className="flex-1">
                    <div className="mb-12">
                        <nav className="flex items-center gap-2 text-sm text-gray-400 mb-6">
                            <Link className="hover:text-primary transition-colors" href={route('terms')}>Termos</Link>
                            <span className="material-symbols-outlined text-base">chevron_right</span>
                            <span className="text-primary font-medium">Introdução</span>
                        </nav>
                        <h1 className="text-4xl md:text-5xl font-black text-[#111815] dark:text-white tracking-tight leading-tight">Introdução Detalhada</h1>
                        <p className="mt-4 text-lg text-gray-500 dark:text-gray-400 max-w-2xl">Entenda os pilares que sustentam a sua experiência no Everest e como firmamos nosso compromisso com o seu progresso.</p>
                    </div>
                    <div className="prose max-w-none">
                        <div className="scroll-mt-32" id="introducao"> 
                             {/* The HTML map 'introducao' to generic sections, but the content has 'Missão', 'Elegibilidade' etc.
                                 I'll add IDs for scrollspy matching the HTML content structure */}
                        </div> 
                        <div className="scroll-mt-32" id="missao">
                            <h2 className="text-2xl font-bold mt-12 mb-6 text-[#111815] dark:text-white flex items-center gap-2">Nossa Missão</h2>
                            <p className="text-gray-600 dark:text-gray-400 leading-relaxed mb-6 text-base">O Everest nasceu com um propósito claro: transformar a forma como as pessoas visualizam e conquistam suas metas pessoais. Acreditamos que o sucesso não é um destino isolado, mas uma jornada composta por pequenos passos consistentes.</p>
                            <p className="text-gray-600 dark:text-gray-400 leading-relaxed mb-6 text-base">Ao utilizar nossa plataforma, você se torna parte de um ecossistema focado em alta performance, clareza mental e disciplina, apoiado por tecnologia de ponta para gestão de tempo e prioridades.</p>
                        </div>
                        <div className="scroll-mt-32" id="elegibilidade">
                            <h2 className="text-2xl font-bold mt-12 mb-6 text-[#111815] dark:text-white flex items-center gap-2">Quem pode usar (Elegibilidade)</h2>
                            <p className="text-gray-600 dark:text-gray-400 leading-relaxed mb-6 text-base">Para garantir a segurança e a integridade da nossa comunidade, estabelecemos critérios básicos de acesso:</p>
                            <ul className="list-disc list-inside mb-6 space-y-2 text-gray-600 dark:text-gray-400">
                                <li><strong>Idade Mínima:</strong> Você deve ter pelo menos 13 anos para criar uma conta individual. Menores de idade devem utilizar a plataforma sob supervisão de um responsável legal.</li>
                                <li><strong>Capacidade Jurídica:</strong> Você declara ter plena capacidade jurídica para concordar com estes termos.</li>
                                <li><strong>Uso Pessoal:</strong> As contas são pessoais e intransferíveis, focadas no desenvolvimento individual.</li>
                            </ul>
                        </div>
                        <div className="scroll-mt-32" id="aceitacao">
                            <h2 className="text-2xl font-bold mt-12 mb-6 text-[#111815] dark:text-white flex items-center gap-2">Aceitação dos Termos</h2>
                            <p className="text-gray-600 dark:text-gray-400 leading-relaxed mb-6 text-base">O acesso e a utilização do Everest estão condicionados à sua aceitação e cumprimento destes Termos de Serviço. Estes termos aplicam-se a todos os visitantes, usuários e outras pessoas que acessem ou utilizem o Serviço.</p>
                            <p className="text-gray-600 dark:text-gray-400 leading-relaxed mb-6 text-base">Ao clicar em &quot;Criar Conta&quot; ou utilizar qualquer funcionalidade do App, você confirma que leu, entendeu e concorda em estar vinculado a estas regras. Se você não concordar com qualquer termo aqui descrito, solicitamos gentilmente que interrompa o uso imediatamente.</p>
                            <div className="bg-primary/5 p-6 rounded-2xl border-l-4 border-primary mt-8">
                                <h4 className="text-sm font-bold text-[#111815] dark:text-white uppercase tracking-wider mb-2">Nota Importante</h4>
                                <p className="text-sm text-gray-600 dark:text-gray-400 mb-0 italic">Estes termos podem ser atualizados periodicamente para refletir melhorias no serviço ou mudanças regulatórias. Notificaremos você sobre alterações significativas através do e-mail cadastrado ou de avisos na própria plataforma.</p>
                            </div>
                        </div>
                        <div className="mt-16 flex items-center justify-between border-t border-[#dbe6e1] dark:border-gray-800 pt-8">
                            <div className="flex flex-col">
                                <span className="text-xs text-gray-400 uppercase font-bold tracking-widest mb-1">Anterior</span>
                                <Link className="text-gray-600 dark:text-gray-400 hover:text-primary font-bold flex items-center gap-1" href={route('terms')}>
                                    <span className="material-symbols-outlined text-sm">arrow_back</span> Visão Geral
                                </Link>
                            </div>
                            <div className="flex flex-col items-end">
                                <span className="text-xs text-gray-400 uppercase font-bold tracking-widest mb-1">Próximo</span>
                                <Link className="text-[#111815] dark:text-white hover:text-primary font-bold flex items-center gap-1 text-right" href={route('terms.data-collection')}>
                                    Coleta de Dados <span className="material-symbols-outlined text-sm">arrow_forward</span>
                                </Link>
                            </div>
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
            <footer className="w-full py-12 px-4 border-t border-[#dbe6e1] dark:border-gray-800 mt-20 bg-white/50 dark:bg-black/20">
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
