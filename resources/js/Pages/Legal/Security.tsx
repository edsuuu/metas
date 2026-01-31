import React, { useEffect } from 'react';
import { Head, Link } from '@inertiajs/react';
import LegalNavbar from '@/Components/LegalNavbar';

export default function Security() {
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
            <Head title="Everest - Segurança Detalhada" />

            <LegalNavbar />

            <main className="max-w-[1200px] mx-auto px-4 md:px-12 py-12 lg:flex lg:gap-16">
                <aside className="hidden lg:block w-72 h-fit sticky top-28">
                    <nav className="flex flex-col gap-1">
                        <h3 className="text-xs font-bold uppercase tracking-widest text-gray-400 mb-4 px-4">Segurança</h3>
                        <a className="nav-link active px-4 py-3 text-sm font-semibold rounded-lg text-gray-600 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-800 transition-all flex items-center gap-3" href="#infraestrutura">
                            <span className="material-symbols-outlined text-[18px]">account_tree</span> Infraestrutura
                        </a>
                        <a className="nav-link px-4 py-3 text-sm font-semibold rounded-lg text-gray-600 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-800 transition-all flex items-center gap-3" href="#criptografia">
                            <span className="material-symbols-outlined text-[18px]">lock</span> Criptografia
                        </a>
                        <a className="nav-link px-4 py-3 text-sm font-semibold rounded-lg text-gray-600 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-800 transition-all flex items-center gap-3" href="#backup">
                            <span className="material-symbols-outlined text-[18px]">cloud_sync</span> Backups e Redundância
                        </a>
                         <a className="nav-link px-4 py-3 text-sm font-semibold rounded-lg text-gray-600 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-800 transition-all flex items-center gap-3" href="#boas-praticas">
                            <span className="material-symbols-outlined text-[18px]">verified_user</span> Boas Práticas
                        </a>
                        <div className="mt-8 p-6 bg-primary/5 rounded-2xl border border-primary/20">
                            <p className="text-sm font-bold text-[#111815] dark:text-white mb-2">Segurança em Primeiro Lugar</p>
                            <p className="text-xs text-gray-500 mb-4">Seus dados financeiros e metas são protegidos por tecnologia de ponta.</p>
                            <Link className="inline-flex items-center gap-2 text-xs font-bold text-primary hover:underline" href="/support">
                                Central de Ajuda <span className="material-symbols-outlined text-sm">arrow_forward</span>
                            </Link>
                        </div>
                    </nav>
                </aside>

                <section className="flex-1">
                     <div className="mb-12">
                        <span className="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/10 text-primary text-xs font-bold mb-4">
                            <span className="material-symbols-outlined text-sm">verified</span>
                            Certificado de Segurança Atualizado
                        </span>
                        <h1 className="text-4xl md:text-5xl font-black text-[#111815] dark:text-white tracking-tight leading-tight">Segurança Detalhada</h1>
                        <p className="mt-4 text-lg text-gray-500 dark:text-gray-400 max-w-2xl">Entenda como construímos uma fortaleza digital para proteger suas ambições e sua privacidade financeira.</p>
                    </div>

                    <div className="prose max-w-none">
                        <div className="scroll-mt-32" id="infraestrutura">
                            <h2 className="text-2xl font-bold mt-12 mb-6 text-[#111815] dark:text-white flex items-center gap-2">1. Infraestrutura e Firewalls</h2>
                            <p className="text-gray-600 dark:text-gray-400 leading-relaxed mb-6 text-base">Nossa infraestrutura é hospedada em centros de dados de classe mundial que possuem certificações rigorosas de segurança (ISO 27001, SOC 2). Utilizamos múltiplas camadas de proteção perimetral para garantir que apenas tráfego legítimo chegue aos nossos servidores.</p>
                            <div className="grid md:grid-cols-2 gap-4 mb-8">
                                <div className="p-6 bg-white dark:bg-gray-800/50 border border-[#dbe6e1] dark:border-gray-700 rounded-2xl transition-all hover:shadow-md">
                                    <span className="material-symbols-outlined text-primary mb-3">security</span>
                                    <h4 className="font-bold mb-2 text-[#111815] dark:text-white">Firewalls Inteligentes (WAF)</h4>
                                    <p className="text-sm mb-0 text-gray-600 dark:text-gray-400">Proteção ativa contra ataques de negação de serviço (DDoS) e injeções de código em tempo real.</p>
                                </div>
                                <div className="p-6 bg-white dark:bg-gray-800/50 border border-[#dbe6e1] dark:border-gray-700 rounded-2xl transition-all hover:shadow-md">
                                    <span className="material-symbols-outlined text-primary mb-3">router</span>
                                    <h4 className="font-bold mb-2 text-[#111815] dark:text-white">Isolamento de Redes</h4>
                                    <p className="text-sm mb-0 text-gray-600 dark:text-gray-400">Seus dados sensíveis residem em redes isoladas, sem acesso direto pela internet pública.</p>
                                </div>
                            </div>
                        </div>
                        <div className="scroll-mt-32" id="criptografia">
                            <h2 className="text-2xl font-bold mt-12 mb-6 text-[#111815] dark:text-white flex items-center gap-2">2. Criptografia de Ponta a Ponta</h2>
                            <p className="text-gray-600 dark:text-gray-400 leading-relaxed mb-6 text-base">No Everest, a privacidade não é apenas uma configuração, é um pilar arquitetural. Aplicamos protocolos de criptografia robustos tanto para dados em repouso quanto em trânsito.</p>
                            <ul className="list-none mb-6 space-y-3 text-gray-600 dark:text-gray-400">
                                <li className="flex items-start gap-3">
                                    <span className="material-symbols-outlined text-primary text-lg">check_circle</span>
                                    <span><strong>Trânsito:</strong> Toda comunicação entre seu dispositivo e nossos servidores é protegida via TLS 1.3 de 256 bits.</span>
                                </li>
                                <li className="flex items-start gap-3">
                                    <span className="material-symbols-outlined text-primary text-lg">check_circle</span>
                                    <span><strong>Repouso:</strong> Dados de metas e registros financeiros são criptografados em nossos bancos de dados usando AES-256.</span>
                                </li>
                                <li className="flex items-start gap-3">
                                    <span className="material-symbols-outlined text-primary text-lg">check_circle</span>
                                    <span><strong>Hashing:</strong> Senhas nunca são armazenadas em texto simples; utilizamos algoritmos de hashing de última geração com salt individual.</span>
                                </li>
                            </ul>
                        </div>
                        <div className="scroll-mt-32" id="backup">
                            <h2 className="text-2xl font-bold mt-12 mb-6 text-[#111815] dark:text-white flex items-center gap-2">3. Backups e Resiliência</h2>
                            <p className="text-gray-600 dark:text-gray-400 leading-relaxed mb-6 text-base">Sabemos que o progresso de uma vida está em suas metas. Por isso, garantimos que seus dados sobrevivam a qualquer imprevisto técnico através de uma política de backup rigorosa.</p>
                            <div className="bg-white dark:bg-gray-800/30 p-8 rounded-3xl border border-[#dbe6e1] dark:border-gray-700 my-8">
                                <h3 className="text-xl font-bold mt-0 mb-4 text-[#111815] dark:text-white flex items-center gap-2">Protocolo de Disponibilidade</h3>
                                <p className="text-gray-600 dark:text-gray-400 leading-relaxed mb-6 text-base">Realizamos backups incrementais a cada 15 minutos e backups completos diariamente. Todos os backups são replicados geograficamente em três regiões distintas para garantir a continuidade do serviço mesmo em desastres em larga escala.</p>
                            </div>
                        </div>
                        <div className="scroll-mt-32" id="boas-praticas">
                            <h2 className="text-2xl font-bold mt-12 mb-6 text-primary flex items-center gap-2">4. Como manter sua conta segura</h2>
                            <p className="text-gray-600 dark:text-gray-400 leading-relaxed mb-6 text-base">A segurança é uma responsabilidade compartilhada. Preparamos algumas dicas essenciais para você blindar seu acesso ao Everest:</p>
                            <div className="space-y-4 mb-12">
                                <div className="flex gap-6 p-6 rounded-2xl bg-primary/5 border border-primary/20">
                                    <div className="bg-primary text-white size-12 rounded-xl flex items-center justify-center shrink-0">
                                        <span className="material-symbols-outlined">vibration</span>
                                    </div>
                                    <div>
                                        <h4 className="font-bold text-[#111815] dark:text-white mb-1">Ative o 2FA (Autenticação de Dois Fatores)</h4>
                                        <p className="text-sm text-gray-500 mb-0">Adicione uma camada extra. Mesmo que alguém descubra sua senha, não conseguirá acessar sua conta sem o código do seu celular.</p>
                                    </div>
                                </div>
                                <div className="flex gap-6 p-6 rounded-2xl bg-white dark:bg-gray-800 border border-[#dbe6e1] dark:border-gray-700">
                                    <div className="bg-gray-100 dark:bg-gray-700 text-gray-500 size-12 rounded-xl flex items-center justify-center shrink-0">
                                        <span className="material-symbols-outlined">password</span>
                                    </div>
                                    <div>
                                        <h4 className="font-bold text-[#111815] dark:text-white mb-1">Use Senhas Fortes e Únicas</h4>
                                        <p className="text-sm text-gray-500 mb-0">Evite datas de aniversário ou sequências simples. Recomendamos o uso de um gerenciador de senhas para criar combinações complexas.</p>
                                    </div>
                                </div>
                                <div className="flex gap-6 p-6 rounded-2xl bg-white dark:bg-gray-800 border border-[#dbe6e1] dark:border-gray-700">
                                    <div className="bg-gray-100 dark:bg-gray-700 text-gray-500 size-12 rounded-xl flex items-center justify-center shrink-0">
                                        <span className="material-symbols-outlined">devices</span>
                                    </div>
                                    <div>
                                        <h4 className="font-bold text-[#111815] dark:text-white mb-1">Monitore Sessões Ativas</h4>
                                        <p className="text-sm text-gray-500 mb-0">Verifique regularmente na sua aba de configurações quais dispositivos estão conectados e encerre sessões desconhecidas.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                     <div className="lg:hidden mt-16 p-8 bg-white dark:bg-gray-800 rounded-3xl border border-[#dbe6e1] dark:border-gray-700 text-center">
                        <span className="material-symbols-outlined text-primary text-4xl mb-4">support_agent</span>
                        <p className="font-bold text-lg mb-2">Identificou algo suspeito?</p>
                        <p className="text-gray-500 mb-6">Reporte vulnerabilidades ou atividades incomuns imediatamente para nosso time de segurança.</p>
                        <a className="w-full inline-flex items-center justify-center gap-2 bg-primary text-[#111815] font-bold py-4 rounded-full shadow-lg shadow-primary/20" href="mailto:security@everest.app">
                            Reportar Problema
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
                        <Link className="hover:text-primary transition-colors" href="/terms/security">Segurança</Link>
                        <Link className="hover:text-primary transition-colors" href="#">Status</Link>
                    </div>
                    <p className="text-xs text-gray-400">© 2024 Everest Technologies Inc. Todos os direitos reservados.</p>
                </div>
            </footer>
        </div>
    );
}
