import React, { useEffect } from 'react';
import { Head, Link } from '@inertiajs/react';
import LegalNavbar from '@/Components/LegalNavbar';

export default function DataCollection() {
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
                 // Skip the direct links (Voltar aos Termos)
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
            <Head title="Everest - Coleta de Dados Detalhada" />

            <LegalNavbar />

            <main className="max-w-[1200px] mx-auto px-4 md:px-12 py-12 lg:flex lg:gap-16">
                <aside className="hidden lg:block w-72 h-fit sticky top-28">
                    <nav className="flex flex-col gap-1">
                        <h3 className="text-xs font-bold uppercase tracking-widest text-gray-400 mb-4 px-4">Documentação</h3>
                        <Link className="nav-link px-4 py-3 text-sm font-semibold rounded-lg text-gray-600 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-800 transition-all flex items-center gap-3" href="/terms">
                            <span className="material-symbols-outlined text-[18px]">arrow_back</span> Voltar aos Termos
                        </Link>
                        <div className="h-px bg-gray-200 dark:my-2"></div>
                        <a className="nav-link active px-4 py-3 text-sm font-semibold rounded-lg text-gray-600 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-800 transition-all flex items-center gap-3" href="#tipos-dados">
                            <span className="material-symbols-outlined text-[18px]">database</span> Tipos de Dados
                        </a>
                        <a className="nav-link px-4 py-3 text-sm font-semibold rounded-lg text-gray-600 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-800 transition-all flex items-center gap-3" href="#finalidade">
                            <span className="material-symbols-outlined text-[18px]">target</span> Finalidade
                        </a>
                        <a className="nav-link px-4 py-3 text-sm font-semibold rounded-lg text-gray-600 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-800 transition-all flex items-center gap-3" href="#exclusao">
                            <span className="material-symbols-outlined text-[18px]">delete</span> Exclusão de Dados
                        </a>

                    </nav>
                </aside>

                <section className="flex-1">
                    <div className="mb-12">
                        <nav className="flex items-center gap-2 text-xs font-bold text-gray-400 mb-6 uppercase tracking-wider">
                            <Link className="hover:text-primary" href="/terms">Privacidade</Link>
                            <span className="material-symbols-outlined text-xs">chevron_right</span>
                            <span className="text-primary">Coleta de Dados Detalhada</span>
                        </nav>
                        <h1 className="text-4xl md:text-5xl font-black text-[#111815] dark:text-white tracking-tight leading-tight">Coleta de Dados</h1>
                        <p className="mt-4 text-lg text-gray-500 dark:text-gray-400 max-w-2xl">Entenda exatamente quais informações o Everest processa para ajudar você a atingir seus objetivos.</p>
                    </div>

                    <div className="prose max-w-none">
                        <div className="scroll-mt-32" id="tipos-dados">
                            <h2 className="text-2xl font-bold mt-12 mb-6 text-[#111815] dark:text-white flex items-center gap-2">1. Quais dados coletamos?</h2>
                            <p className="text-gray-600 dark:text-gray-400 leading-relaxed mb-6 text-base">Para o funcionamento pleno do Everest, dividimos a coleta em três categorias principais. A transparência sobre o que acessamos é fundamental para a confiança entre o usuário e a plataforma.</p>
                            <div className="overflow-hidden rounded-2xl border border-[#dbe6e1] dark:border-gray-800 bg-white dark:bg-gray-900/50 my-8">
                                <table className="w-full data-table">
                                    <thead>
                                        <tr>
                                            <th className="text-left px-4 py-3 bg-gray-50 dark:bg-gray-800/50 text-xs font-bold uppercase tracking-wider text-gray-500">Categoria</th>
                                            <th className="text-left px-4 py-3 bg-gray-50 dark:bg-gray-800/50 text-xs font-bold uppercase tracking-wider text-gray-500">Exemplos de Dados</th>
                                            <th className="text-left px-4 py-3 bg-gray-50 dark:bg-gray-800/50 text-xs font-bold uppercase tracking-wider text-gray-500">Necessário</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td className="px-4 py-4 border-t border-gray-100 dark:border-gray-800 text-sm text-gray-600 dark:text-gray-400 font-bold">Dados Pessoais</td>
                                            <td className="px-4 py-4 border-t border-gray-100 dark:border-gray-800 text-sm text-gray-600 dark:text-gray-400">Nome completo, endereço de e-mail, foto de perfil e fuso horário.</td>
                                            <td className="px-4 py-4 border-t border-gray-100 dark:border-gray-800 text-sm font-bold text-primary">Sim</td>
                                        </tr>
                                        <tr>
                                            <td className="px-4 py-4 border-t border-gray-100 dark:border-gray-800 text-sm text-gray-600 dark:text-gray-400 font-bold">Dados de Metas</td>
                                            <td className="px-4 py-4 border-t border-gray-100 dark:border-gray-800 text-sm text-gray-600 dark:text-gray-400">Títulos de objetivos, prazos, sub-tarefas, anotações de progresso e frequência.</td>
                                            <td className="px-4 py-4 border-t border-gray-100 dark:border-gray-800 text-sm font-bold text-primary">Sim</td>
                                        </tr>
                                        <tr>
                                            <td className="px-4 py-4 border-t border-gray-100 dark:border-gray-800 text-sm text-gray-600 dark:text-gray-400 font-bold">Dados de Uso</td>
                                            <td className="px-4 py-4 border-t border-gray-100 dark:border-gray-800 text-sm text-gray-600 dark:text-gray-400">Endereço IP, tipo de navegador, logs de erro e tempo de permanência em telas.</td>
                                            <td className="px-4 py-4 border-t border-gray-100 dark:border-gray-800 text-sm text-gray-400">Opcional*</td>
                                        </tr>
                                        <tr>
                                            <td className="px-4 py-4 border-t border-gray-100 dark:border-gray-800 text-sm text-gray-600 dark:text-gray-400 font-bold">Dados Sociais</td>
                                            <td className="px-4 py-4 border-t border-gray-100 dark:border-gray-800 text-sm text-gray-600 dark:text-gray-400">Interações em metas compartilhadas (comentários e curtidas).</td>
                                            <td className="px-4 py-4 border-t border-gray-100 dark:border-gray-800 text-sm text-gray-400">Opcional</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <p className="text-sm italic text-gray-500">* Dados de uso essenciais para segurança não podem ser desabilitados.</p>
                        </div>
                        <div className="scroll-mt-32" id="finalidade">
                            <h2 className="text-2xl font-bold mt-12 mb-6 text-[#111815] dark:text-white flex items-center gap-2">2. Finalidade da Coleta</h2>
                            <p className="text-gray-600 dark:text-gray-400 leading-relaxed mb-6 text-base">O Everest não comercializa seus dados. Cada bit de informação coletado tem um propósito específico voltado para sua produtividade:</p>
                            <ul className="list-disc list-inside mb-6 space-y-2 text-gray-600 dark:text-gray-400">
                                <li><strong>Personalização:</strong> Ajustamos o algoritmo de lembretes baseados no seu fuso horário e histórico de conclusão.</li>
                                <li><strong>Insights:</strong> Geramos gráficos de desempenho baseados exclusivamente nos seus dados de metas.</li>
                                <li><strong>Segurança:</strong> Utilizamos dados de acesso para identificar tentativas de login suspeitas e proteger sua conta.</li>
                                <li><strong>Suporte:</strong> Dados técnicos nos ajudam a resolver bugs específicos que você possa encontrar na plataforma.</li>
                            </ul>
                        </div>
                        <div className="scroll-mt-32" id="exclusao">
                            <h2 className="text-2xl font-bold mt-12 mb-6 text-[#111815] dark:text-white flex items-center gap-2">3. Exclusão e Portabilidade</h2>
                            <p className="text-gray-600 dark:text-gray-400 leading-relaxed mb-6 text-base">Acreditamos que você é o único dono dos seus dados. Por isso, oferecemos autonomia total sobre suas informações:</p>
                            <div className="grid md:grid-cols-2 gap-6 my-8">
                                <div className="p-6 rounded-2xl bg-white dark:bg-gray-800 border border-[#dbe6e1] dark:border-gray-700">
                                    <span className="material-symbols-outlined text-primary mb-4 text-3xl">download</span>
                                    <h4 className="font-bold text-[#111815] dark:text-white mb-2">Exportar Meus Dados</h4>
                                    <p className="text-sm text-gray-500 mb-0">Você pode solicitar um arquivo .JSON com todas as suas metas e histórico a qualquer momento nas configurações do perfil.</p>
                                </div>
                                <div className="p-6 rounded-2xl bg-white dark:bg-gray-800 border border-[#dbe6e1] dark:border-gray-700">
                                    <span className="material-symbols-outlined text-red-500 mb-4 text-3xl">delete_forever</span>
                                    <h4 className="font-bold text-[#111815] dark:text-white mb-2">Excluir Conta</h4>
                                    <p className="text-sm text-gray-500 mb-0">Ao excluir sua conta, todos os dados são removidos de nossos servidores em até 30 dias, sem possibilidade de recuperação.</p>
                                </div>
                            </div>
                            <p className="text-gray-600 dark:text-gray-400 leading-relaxed mb-6 text-base">Para solicitar a exclusão imediata de dados específicos ou tirar dúvidas sobre o processo, você pode entrar em contato diretamente com nosso DPO (Data Protection Officer) através do e-mail <a className="text-primary font-bold hover:underline" href="mailto:privacy@everest.app">privacy@everest.app</a>.</p>
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
                        <Link className="hover:text-primary transition-colors" href="/terms">Termos de Uso</Link>
                        <Link className="hover:text-primary transition-colors" href="/terms">Privacidade</Link>
                        <Link className="hover:text-primary transition-colors" href="/terms">Cookies</Link>
                        <Link className="hover:text-primary transition-colors" href="/terms/security">Segurança</Link>
                    </div>
                    <p className="text-xs text-gray-400">© 2024 Everest Technologies Inc. Todos os direitos reservados.</p>
                </div>
            </footer>
        </div>
    );
}
