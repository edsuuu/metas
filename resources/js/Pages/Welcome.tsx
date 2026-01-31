import { Link, Head } from '@inertiajs/react';
import { PageProps } from '@/types';

declare function route(name: string, params?: any, absolute?: boolean): string;

export default function Welcome({ auth }: PageProps<{ laravelVersion: string, phpVersion: string }>) {
    return (
        <>
            <Head title="Conquiste seus Objetivos" />
            <div className="bg-background-light dark:bg-background-dark text-[#111815] transition-colors duration-300">
                <header className="sticky top-0 z-50 w-full border-b border-solid border-[#dbe6e1] bg-white/80 dark:bg-background-dark/80 backdrop-blur-md px-4 md:px-20 lg:px-40 py-3">
                    <div className="max-w-[1200px] mx-auto flex items-center justify-between">
                        <Link href={route('home')} className="flex items-center gap-3 hover:opacity-80 transition-opacity">
                            <div className="size-8 text-primary">
                                <svg fill="currentColor" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                                    <path clipRule="evenodd" d="M24 4H42V17.3333V30.6667H24V44H6V30.6667V17.3333H24V4Z" fillRule="evenodd"></path>
                                </svg>
                            </div>
                            <h2 className="text-[#111815] dark:text-white text-xl font-bold leading-tight tracking-tight">Everest</h2>
                        </Link>
                        <nav className="hidden md:flex items-center gap-9">
                            <a className="text-[#111815] dark:text-gray-300 text-sm font-medium hover:text-primary transition-colors" href="#features">Funcionalidades</a>
                            <Link className="text-[#111815] dark:text-gray-300 text-sm font-medium hover:text-primary transition-colors" href={route('pricing')}>Planos</Link>
                        </nav>
                        <div className="flex gap-3">
                            {auth.user ? (
                                <Link
                                    href={route('dashboard')}
                                    className="flex min-w-[100px] cursor-pointer items-center justify-center rounded-full h-10 px-5 bg-primary text-[#111815] text-sm font-bold shadow-lg shadow-primary/20 hover:scale-105 transition-transform"
                                >
                                    Dashboard
                                </Link>
                            ) : (
                                <>
                                    <Link
                                        href={route('login')}
                                        className="hidden sm:flex min-w-[84px] cursor-pointer items-center justify-center rounded-full h-10 px-5 bg-background-light dark:bg-gray-800 text-[#111815] dark:text-white text-sm font-bold border border-[#dbe6e1] dark:border-gray-700"
                                    >
                                        Login
                                    </Link>
                                    <Link
                                        href={route('register')}
                                        className="flex min-w-[100px] cursor-pointer items-center justify-center rounded-full h-10 px-5 bg-primary text-[#111815] text-sm font-bold shadow-lg shadow-primary/20 hover:scale-105 transition-transform"
                                    >
                                        Começar teste grátis
                                    </Link>
                                </>
                            )}
                        </div>
                    </div>
                </header>

                <main className="flex flex-col items-center">
                    <section className="w-full max-w-[1200px] px-4 md:px-10 py-16 md:py-24">
                        <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                            <div className="flex flex-col gap-8 text-left">
                                <div className="flex flex-col gap-4">
                                    <span className="inline-block px-4 py-1 rounded-full bg-primary/10 text-primary text-sm font-bold w-fit">
                                        🚀 Gestão de Metas Reimaginada
                                    </span>
                                    <h1 className="text-[#111815] dark:text-white text-5xl md:text-6xl font-black leading-[1.1] tracking-tight">
                                        Conquiste seus maiores objetivos, <span className="text-primary italic">um passo de cada vez</span>
                                    </h1>
                                    <p className="text-lg text-gray-600 dark:text-gray-400 max-w-[500px]">
                                        O Everest ajuda você a transformar sonhos complexos em micro-tarefas acionáveis. Comece sua escalada hoje e alcance o topo da sua produtividade.
                                    </p>
                                </div>
                                <div className="flex flex-col sm:flex-row gap-4">
                                    <Link
                                        href={route('register')}
                                        className="flex min-w-[200px] cursor-pointer items-center justify-center rounded-full h-14 px-8 bg-primary text-[#111815] text-lg font-bold shadow-xl shadow-primary/30 hover:scale-105 transition-all"
                                    >
                                        Começar teste grátis de 14 dias
                                    </Link>
                                    <button className="flex min-w-[200px] cursor-pointer items-center justify-center rounded-full h-14 px-8 bg-white dark:bg-gray-800 border border-[#dbe6e1] dark:border-gray-700 text-[#111815] dark:text-white text-lg font-bold">
                                        Ver Demo
                                    </button>
                                </div>
                                <div className="flex items-center gap-4 mt-2">
                                    <div className="flex -space-x-2">
                                        {[1, 2, 3].map((i) => (
                                            <div key={i} className="w-8 h-8 rounded-full border-2 border-white bg-gray-200 overflow-hidden">
                                                <img alt="Avatar" src={`https://i.pravatar.cc/100?img=${i + 10}`} />
                                            </div>
                                        ))}
                                    </div>
                                    <p className="text-sm font-medium text-gray-500">Junte-se a mais de 10.000 usuários</p>
                                </div>
                            </div>
                            <div className="relative">
                                <div className="absolute -top-10 -left-10 w-40 h-40 bg-primary/20 rounded-full blur-3xl"></div>
                                <div className="absolute -bottom-10 -right-10 w-40 h-40 bg-blue-400/10 rounded-full blur-3xl"></div>
                                <div className="relative w-full aspect-[4/3] bg-white dark:bg-gray-800 rounded-xl shadow-2xl border border-[#dbe6e1] dark:border-gray-700 overflow-hidden p-4">
                                    <div className="flex flex-col h-full bg-background-light dark:bg-background-dark rounded-lg p-4 gap-4">
                                        <div className="flex justify-between items-center border-b pb-3 border-gray-200 dark:border-gray-700">
                                            <div className="h-4 w-32 bg-gray-200 dark:bg-gray-700 rounded-full"></div>
                                            <div className="flex gap-2">
                                                <div className="size-4 rounded-full bg-red-400"></div>
                                                <div className="size-4 rounded-full bg-yellow-400"></div>
                                                <div className="size-4 rounded-full bg-green-400"></div>
                                            </div>
                                        </div>
                                        <div className="grid grid-cols-3 gap-3">
                                            <div className="col-span-1 h-32 bg-white dark:bg-gray-800 rounded-lg p-3 flex flex-col items-center justify-center gap-2 border border-gray-100 dark:border-gray-700 shadow-sm">
                                                <div className="size-16 rounded-full border-4 border-primary border-t-transparent flex items-center justify-center">
                                                    <span className="text-xs font-bold">78%</span>
                                                </div>
                                                <div className="h-2 w-12 bg-gray-200 dark:bg-gray-700 rounded-full"></div>
                                            </div>
                                            <div className="col-span-2 h-32 bg-white dark:bg-gray-800 rounded-lg p-3 flex flex-col gap-2 border border-gray-100 dark:border-gray-700 shadow-sm">
                                                <div className="h-4 w-2/3 bg-primary/20 rounded-full"></div>
                                                <div className="flex flex-col gap-2 pt-2">
                                                    <div className="h-2 w-full bg-gray-100 dark:bg-gray-700 rounded-full"></div>
                                                    <div className="h-2 w-5/6 bg-gray-100 dark:bg-gray-700 rounded-full"></div>
                                                    <div className="h-2 w-4/6 bg-gray-100 dark:bg-gray-700 rounded-full"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div className="flex-1 bg-white dark:bg-gray-800 rounded-lg border border-gray-100 dark:border-gray-700 shadow-sm p-4">
                                            <div className="flex items-center gap-3 mb-4">
                                                <span className="material-symbols-outlined text-orange-500 font-bold">local_fire_department</span>
                                                <div className="h-4 w-32 bg-gray-200 dark:bg-gray-700 rounded-full"></div>
                                            </div>
                                            <div className="space-y-3">
                                                <div className="flex items-center gap-3">
                                                    <div className="size-5 rounded-md border-2 border-primary"></div>
                                                    <div className="h-3 w-40 bg-gray-100 dark:bg-gray-700 rounded-full"></div>
                                                </div>
                                                <div className="flex items-center gap-3">
                                                    <div className="size-5 rounded-md bg-primary flex items-center justify-center">
                                                        <span className="material-symbols-outlined text-white text-[14px]">check</span>
                                                    </div>
                                                    <div className="h-3 w-48 bg-gray-100 dark:bg-gray-700 rounded-full"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section className="w-full bg-white dark:bg-background-dark py-12" id="stats">
                        <div className="max-w-[1200px] mx-auto px-4 md:px-10">
                            <div className="flex flex-wrap gap-6 justify-center">
                                <div className="flex min-w-[200px] flex-1 flex-col items-center gap-2 rounded-xl p-8 border border-[#dbe6e1] dark:border-gray-800 bg-background-light dark:bg-gray-800/50">
                                    <p className="text-gray-500 dark:text-gray-400 text-sm font-medium">Usuários Ativos</p>
                                    <p className="text-[#111815] dark:text-white tracking-light text-4xl font-black leading-tight">10.000+</p>
                                </div>
                                <div className="flex min-w-[200px] flex-1 flex-col items-center gap-2 rounded-xl p-8 border border-[#dbe6e1] dark:border-gray-800 bg-background-light dark:bg-gray-800/50">
                                    <p className="text-gray-500 dark:text-gray-400 text-sm font-medium">Metas Concluídas</p>
                                    <p className="text-[#111815] dark:text-white tracking-light text-4xl font-black leading-tight">1.2M</p>
                                </div>
                                <div className="flex min-w-[200px] flex-1 flex-col items-center gap-2 rounded-xl p-8 border border-[#dbe6e1] dark:border-gray-800 bg-background-light dark:bg-gray-800/50">
                                    <p className="text-gray-500 dark:text-gray-400 text-sm font-medium">Taxa de Sucesso</p>
                                    <p className="text-[#111815] dark:text-white tracking-light text-4xl font-black leading-tight">94%</p>
                                </div>
                            </div>
                        </div>
                    </section>
                    
                    <section className="w-full max-w-[1200px] px-4 md:px-10 py-20" id="features">
                        <div className="flex flex-col gap-16 items-center">
                            <div className="flex flex-col gap-4 text-center max-w-[800px]">
                                <h2 className="text-primary font-bold tracking-widest uppercase text-sm">Motor de Alta Performance</h2>
                                <h1 className="text-[#111815] dark:text-white tracking-tight text-4xl md:text-5xl font-black leading-tight">
                                    Quebre seus limites, não seu foco
                                </h1>
                                <p className="text-gray-600 dark:text-gray-400 text-lg">
                                    Nosso sistema é projetado para manter você avançando com ferramentas baseadas em psicologia que eliminam a procrastinação.
                                </p>
                            </div>
                            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 w-full">
                                {[
                                    { icon: 'bar_chart', title: 'Insights Semanais', desc: 'Reflexão baseada em dados sobre seu progresso para otimizar seu desempenho toda segunda-feira de manhã.' },
                                    { icon: 'check_circle', title: 'Micro-tarefas', desc: 'Produtividade baseada em psicologia para evitar o burnout e manter o ritmo ao dividir grandes objetivos.' },
                                    { icon: 'sync', title: 'Metas Recorrentes', desc: 'Construa hábitos que duram para sempre com cronogramas recorrentes automatizados e lembretes.' },
                                    { icon: 'local_fire_department', title: 'Gamificação', desc: 'Mantenha a motivação com recompensas, marcos visuais e contadores de chamas que rastreiam sua constância.' }
                                ].map((feature, i) => (
                                    <div key={i} className="flex flex-col gap-6 rounded-xl border border-[#dbe6e1] dark:border-gray-800 bg-white dark:bg-gray-800/30 p-8 hover:border-primary/50 transition-all group">
                                        <div className="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
                                            <span className="material-symbols-outlined">{feature.icon}</span>
                                        </div>
                                        <div className="flex flex-col gap-2">
                                            <h2 className="text-[#111815] dark:text-white text-xl font-bold leading-tight">{feature.title}</h2>
                                            <p className="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">{feature.desc}</p>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>
                    </section>

                    <section className="w-full bg-background-light dark:bg-background-dark/50 py-24 overflow-hidden">
                        <div className="max-w-[1200px] mx-auto px-4 md:px-10 flex flex-col gap-12 lg:flex-row items-center">
                            <div className="flex flex-col gap-6 lg:w-1/2">
                                <h2 className="text-[#111815] dark:text-white text-4xl font-black leading-tight">Experimente o auge da produtividade pessoal</h2>
                                <p className="text-gray-600 dark:text-gray-400 text-lg">
                                    O Everest não é apenas uma lista de tarefas. É um motor de conquista de metas que prioriza sua energia mental e foca seu tempo no que realmente importa.
                                </p>
                                <ul className="flex flex-col gap-4">
                                    {[
                                        'Matriz de Priorização',
                                        'Lembretes de Pausas Conscientes',
                                        'Sincronização entre todos os dispositivos'
                                    ].map((item, i) => (
                                        <li key={i} className="flex items-center gap-3">
                                            <span className="material-symbols-outlined text-primary">verified</span>
                                            <span className="font-bold">{item}</span>
                                        </li>
                                    ))}
                                </ul>
                            </div>
                            <div className="lg:w-1/2 flex justify-center">
                                <div className="relative w-full max-w-[500px]">
                                    <div className="bg-gradient-to-tr from-primary to-blue-400 p-8 rounded-lg shadow-2xl rotate-3 scale-95 opacity-50 absolute inset-0"></div>
                                    <div className="relative bg-white dark:bg-gray-800 rounded-lg shadow-2xl p-6 border border-[#dbe6e1] dark:border-gray-700">
                                        <div className="flex justify-between items-center mb-6">
                                            <h3 className="font-black text-xl">Escalada de Hoje</h3>
                                            <div className="flex items-center gap-1 bg-primary/20 px-3 py-1 rounded-full">
                                                <span className="material-symbols-outlined text-orange-500 text-sm font-bold">local_fire_department</span>
                                                <span className="text-xs font-bold">12 Dias</span>
                                            </div>
                                        </div>
                                        <div className="space-y-4">
                                            <div className="p-4 bg-background-light dark:bg-background-dark rounded-xl flex items-center gap-4">
                                                <div className="size-6 rounded-full border-2 border-primary"></div>
                                                <div className="h-4 w-40 bg-gray-200 dark:bg-gray-700 rounded-full"></div>
                                            </div>
                                            <div className="p-4 bg-background-light dark:bg-background-dark rounded-xl flex items-center gap-4">
                                                <div className="size-6 rounded-full bg-primary flex items-center justify-center text-white">
                                                    <span className="material-symbols-outlined text-xs">check</span>
                                                </div>
                                                <div className="h-4 w-32 bg-gray-200 dark:bg-gray-700 rounded-full opacity-50"></div>
                                            </div>
                                            <div className="p-4 bg-background-light dark:bg-background-dark rounded-xl flex items-center gap-4">
                                                <div className="size-6 rounded-full border-2 border-primary"></div>
                                                <div className="h-4 w-48 bg-gray-200 dark:bg-gray-700 rounded-full"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section className="w-full max-w-[1200px] px-4 py-24">
                        <div className="bg-background-dark text-white rounded-xl p-8 md:p-16 flex flex-col items-center text-center gap-8 relative overflow-hidden">
                            <div className="absolute top-0 right-0 w-64 h-64 bg-primary/20 rounded-full blur-[100px] -mr-32 -mt-32"></div>
                            <div className="absolute bottom-0 left-0 w-64 h-64 bg-blue-500/10 rounded-full blur-[100px] -ml-32 -mb-32"></div>
                            <h2 className="text-4xl md:text-5xl font-black max-w-[700px] relative z-10">Pronto para começar sua escalada?</h2>
                            <p className="text-gray-400 text-lg max-w-[600px] relative z-10">
                                Junte-se a milhares de pessoas de alta performance que estão alcançando suas metas com o Everest. Sem necessidade de cartão de crédito para começar seu teste gratuito.
                            </p>
                            <div className="flex flex-col sm:flex-row gap-4 relative z-10 w-full sm:w-auto">
                                <Link
                                    href={route('register')}
                                    className="flex min-w-[200px] cursor-pointer items-center justify-center rounded-full h-14 px-8 bg-primary text-[#111815] text-lg font-bold shadow-xl shadow-primary/30 hover:scale-105 transition-all"
                                >
                                    Começar teste grátis de 14 dias
                                </Link>
                                <button className="flex min-w-[200px] cursor-pointer items-center justify-center rounded-full h-14 px-8 bg-white/10 backdrop-blur-md border border-white/20 text-white text-lg font-bold hover:bg-white/20 transition-all">
                                    Falar com vendas
                                </button>
                            </div>
                            <p className="text-sm text-gray-500 mt-4 relative z-10">Junte-se a 10.000+ usuários ativos. Cancele a qualquer momento.</p>
                        </div>
                    </section>
                </main>

                <footer className="w-full bg-white dark:bg-background-dark border-t border-[#dbe6e1] dark:border-gray-800 py-12 px-4 md:px-20 lg:px-40">
                    <div className="max-w-[1200px] mx-auto flex flex-col md:flex-row justify-between items-center gap-8">
                        <div className="flex flex-col gap-4">
                            <div className="flex items-center gap-3">
                                <div className="size-6 text-primary">
                                    <svg fill="currentColor" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                                        <path clipRule="evenodd" d="M24 4H42V17.3333V30.6667H24V44H6V30.6667V17.3333H24V4Z" fillRule="evenodd"></path>
                                    </svg>
                                </div>
                                <h2 className="text-[#111815] dark:text-white text-lg font-bold">Everest</h2>
                            </div>
                            <p className="text-gray-500 text-sm">Capacitando pessoas a alcançarem seu auge desde 2024.</p>
                        </div>
                        <div className="flex gap-8 text-sm font-medium text-gray-600 dark:text-gray-400">
                            <Link className="hover:text-primary" href={route('terms')}>Privacidade</Link>
                            <Link className="hover:text-primary" href={route('terms')}>Termos</Link>
                            <Link className="hover:text-primary" href={route('support')}>Ajuda</Link>
                            <Link className="hover:text-primary" href={route('support')}>Contato</Link>
                        </div>
                        <div className="flex gap-4">
                            <div className="size-10 rounded-full border border-gray-200 dark:border-gray-700 flex items-center justify-center text-gray-500 hover:text-primary cursor-pointer">
                                <span className="material-symbols-outlined">share</span>
                            </div>
                            <div className="size-10 rounded-full border border-gray-200 dark:border-gray-700 flex items-center justify-center text-gray-500 hover:text-primary cursor-pointer">
                                <span className="material-symbols-outlined">mail</span>
                            </div>
                        </div>
                    </div>
                    <div className="max-w-[1200px] mx-auto mt-12 pt-8 border-t border-gray-100 dark:border-gray-800 text-center">
                        <p className="text-xs text-gray-400">© 2024 Everest Technologies Inc. Todos os direitos reservados.</p>
                    </div>
                </footer>
            </div>
        </>
    );
}
