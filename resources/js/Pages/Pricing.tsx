import React from 'react';
import { Head, usePage, Link } from '@inertiajs/react';
import PublicNavbar from '@/Components/PublicNavbar';
import Footer from '@/Components/Footer';
import { PageProps } from '@/types';

declare function route(name: string, params?: any, absolute?: boolean): string;

export default function Pricing() {
    const { auth } = usePage<PageProps>().props;

    return (
        <div className="bg-background-light dark:bg-background-dark text-[#111815] transition-colors duration-300 font-display min-h-screen flex flex-col">
            <Head title="Planos e Preços Individuais - Everest" />

            <PublicNavbar auth={auth} />

            <main className="flex-1 flex flex-col items-center">
                <section className="w-full max-w-[1200px] px-4 md:px-10 py-16 md:py-24" id="pricing">
                    <div className="flex flex-col gap-4 text-center mb-16">
                        <h1 className="text-[#111815] dark:text-white text-4xl md:text-5xl font-black tracking-tight">Planos e Preços <span className="text-primary">Individuais</span></h1>
                        <p className="text-gray-600 dark:text-gray-400 text-lg max-w-[700px] mx-auto">
                            Foco total na sua evolução pessoal. Escolha o nível de suporte que você precisa para chegar ao topo.
                        </p>
                    </div>
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch">
                        <div className="flex flex-col p-8 bg-white dark:bg-gray-800 rounded-xl border border-[#dbe6e1] dark:border-gray-700 shadow-sm transition-all hover:border-primary/50">
                            <div className="mb-8">
                                <h3 className="text-xl font-bold mb-2">Iniciante</h3>
                                <div className="flex items-baseline gap-1">
                                    <span className="text-4xl font-black">R$ 0</span>
                                    <span className="text-gray-500 text-sm">/sempre</span>
                                </div>
                                <p className="text-sm text-gray-500 mt-2">Para quem está começando a jornada.</p>
                            </div>
                            <ul className="flex flex-col gap-4 mb-8 flex-1">
                                <li className="flex items-center gap-3 text-sm">
                                    <span className="material-symbols-outlined text-primary text-[20px]">check_circle</span>
                                    Até 5 metas ativas
                                </li>
                                <li className="flex items-center gap-3 text-sm">
                                    <span className="material-symbols-outlined text-primary text-[20px]">check_circle</span>
                                    Ofensivas (Streaks) básicas
                                </li>
                                <li className="flex items-center gap-3 text-sm text-gray-400 line-through">
                                    <span className="material-symbols-outlined text-gray-300 text-[20px]">block</span>
                                    Insights por IA
                                </li>
                                <li className="flex items-center gap-3 text-sm text-gray-400 line-through">
                                    <span className="material-symbols-outlined text-gray-300 text-[20px]">block</span>
                                    Ranking Global
                                </li>
                            </ul>
                            <Link href={route('register')} className="w-full flex items-center justify-center py-4 rounded-full border border-primary text-primary font-bold hover:bg-primary/5 transition-colors">
                                Começar grátis
                            </Link>
                        </div>
                        <div className="flex flex-col p-8 bg-white dark:bg-gray-800 rounded-xl border-2 border-primary shadow-2xl relative transform scale-105 z-10">
                            <div className="absolute -top-4 left-1/2 -translate-x-1/2 bg-primary text-[#111815] text-[10px] font-black uppercase tracking-widest px-4 py-1 rounded-full">
                                Mais Popular
                            </div>
                            <div className="mb-8">
                                <h3 className="text-xl font-bold mb-2">Pro</h3>
                                <div className="flex items-baseline gap-1">
                                    <span className="text-4xl font-black">R$ 29</span>
                                    <span className="text-gray-500 text-sm">/mês</span>
                                </div>
                                <p className="text-xs text-gray-400 mt-1">Ideal para foco e produtividade diária</p>
                            </div>
                            <ul className="flex flex-col gap-4 mb-8 flex-1">
                                <li className="flex items-center gap-3 text-sm font-bold">
                                    <span className="material-symbols-outlined text-primary text-[20px]">check_circle</span>
                                    Metas Ilimitadas
                                </li>
                                <li className="flex items-center gap-3 text-sm font-bold">
                                    <span className="material-symbols-outlined text-primary text-[20px]">check_circle</span>
                                    Insights por IA semanais
                                </li>
                                <li className="flex items-center gap-3 text-sm font-bold">
                                    <span className="material-symbols-outlined text-primary text-[20px]">check_circle</span>
                                    Ranking Global & Medalhas
                                </li>
                                <li className="flex items-center gap-3 text-sm font-bold">
                                    <span className="material-symbols-outlined text-primary text-[20px]">check_circle</span>
                                    Modo foco e sons relaxantes
                                </li>
                                <li className="flex items-center gap-3 text-sm font-bold">
                                    <span className="material-symbols-outlined text-primary text-[20px]">check_circle</span>
                                    Backup em nuvem automático
                                </li>
                            </ul>
                            <Link href={route('register')} className="w-full flex items-center justify-center py-4 rounded-full bg-primary text-[#111815] font-bold shadow-lg shadow-primary/30 hover:scale-105 transition-transform">
                                Assinar Plano Pro
                            </Link>
                        </div>
                        <div className="flex flex-col p-8 bg-white dark:bg-gray-800 rounded-xl border border-[#dbe6e1] dark:border-gray-700 shadow-sm transition-all hover:border-primary/50">
                            <div className="mb-8">
                                <h3 className="text-xl font-bold mb-2">Elite</h3>
                                <div className="flex items-baseline gap-1">
                                    <span className="text-4xl font-black">R$ 49</span>
                                    <span className="text-gray-500 text-sm">/mês</span>
                                </div>
                                <p className="text-sm text-gray-500 mt-2">Para quem busca o próximo nível.</p>
                            </div>
                            <ul className="flex flex-col gap-4 mb-8 flex-1">
                                <li className="flex items-center gap-3 text-sm">
                                    <span className="material-symbols-outlined text-primary text-[20px]">stars</span>
                                    Tudo do plano Pro
                                </li>
                                <li className="flex items-center gap-3 text-sm">
                                    <span className="material-symbols-outlined text-primary text-[20px]">groups</span>
                                    Acesso à Comunidade VIP
                                </li>
                                <li className="flex items-center gap-3 text-sm font-bold">
                                    <span className="material-symbols-outlined text-primary text-[20px]">psychology</span>
                                    Mentoria Mensal em Grupo
                                </li>
                                <li className="flex items-center gap-3 text-sm">
                                    <span className="material-symbols-outlined text-primary text-[20px]">workspace_premium</span>
                                    Suporte Prioritário Via WhatsApp
                                </li>
                                <li className="flex items-center gap-3 text-sm">
                                    <span className="material-symbols-outlined text-primary text-[20px]">auto_awesome</span>
                                    Funcionalidades Beta Antecipadas
                                </li>
                            </ul>
                            <Link href={route('register')} className="w-full flex items-center justify-center py-4 rounded-full border border-gray-300 dark:border-gray-600 font-bold hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                Ser Elite
                            </Link>
                        </div>
                    </div>
                </section>
                <section className="w-full bg-white dark:bg-background-dark py-24" id="faq">
                    <div className="max-w-[800px] mx-auto px-4 md:px-10">
                        <h2 className="text-3xl font-black text-center mb-12">Dúvidas Frequentes</h2>
                        <div className="space-y-6">
                            <div className="p-6 bg-background-light dark:bg-gray-800/50 rounded-xl border border-[#dbe6e1] dark:border-gray-700">
                                <h4 className="font-bold mb-2 text-lg">Posso mudar de plano quando quiser?</h4>
                                <p className="text-gray-600 dark:text-gray-400">Sim! Você pode fazer o upgrade para Pro ou Elite a qualquer momento. Se decidir voltar para o plano gratuito, suas metas extras ficarão arquivadas mas salvas.</p>
                            </div>
                            <div className="p-6 bg-background-light dark:bg-gray-800/50 rounded-xl border border-[#dbe6e1] dark:border-gray-700">
                                <h4 className="font-bold mb-2 text-lg">Como funciona a Mentoria Mensal do plano Elite?</h4>
                                <p className="text-gray-600 dark:text-gray-400">Todo primeiro sábado do mês realizamos uma call exclusiva para membros Elite sobre produtividade, hábitos e revisão de metas com especialistas.</p>
                            </div>
                            <div className="p-6 bg-background-light dark:bg-gray-800/50 rounded-xl border border-[#dbe6e1] dark:border-gray-700">
                                <h4 className="font-bold mb-2 text-lg">O Everest tem acesso Vitalício?</h4>
                                <p className="text-gray-600 dark:text-gray-400">Atualmente trabalhamos com assinaturas mensais para garantir a evolução contínua da plataforma, mas oferecemos descontos especiais para renovações anuais.</p>
                            </div>
                            <div className="p-6 bg-background-light dark:bg-gray-800/50 rounded-xl border border-[#dbe6e1] dark:border-gray-700">
                                <h4 className="font-bold mb-2 text-lg">Existe suporte para usuários individuais?</h4>
                                <p className="text-gray-600 dark:text-gray-400">Com certeza. Todos os usuários têm acesso à nossa central de ajuda. Assinantes Elite possuem canal direto e prioritário.</p>
                            </div>
                        </div>
                    </div>
                </section>
                <section className="w-full max-w-[1200px] px-4 py-24">
                    <div className="bg-background-dark text-white rounded-xl p-8 md:p-16 flex flex-col items-center text-center gap-8 relative overflow-hidden">
                        <div className="absolute top-0 right-0 w-64 h-64 bg-primary/20 rounded-full blur-[100px] -mr-32 -mt-32"></div>
                        <div className="absolute bottom-0 left-0 w-64 h-64 bg-blue-500/10 rounded-full blur-[100px] -ml-32 -mb-32"></div>
                        <h2 className="text-4xl md:text-5xl font-black max-w-[700px] relative z-10">O topo da montanha espera por você.</h2>
                        <Link href={route('register')} className="flex min-w-[200px] cursor-pointer items-center justify-center rounded-full h-14 px-8 bg-primary text-[#111815] text-lg font-bold shadow-xl shadow-primary/30 hover:scale-105 transition-all relative z-10">
                            Começar agora gratuitamente
                        </Link>
                        <p className="text-sm text-gray-500 mt-4 relative z-10">Junte-se a 10.000+ pessoas que já estão evoluindo diariamente.</p>
                    </div>
                </section>
            </main>

            <Footer />
        </div>
    );
}
