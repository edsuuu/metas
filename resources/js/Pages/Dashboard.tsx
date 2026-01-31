import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';
import { PageProps } from '@/types';

export default function Dashboard({ auth }: PageProps) {
    const user = auth.user;
    
    return (
        <AuthenticatedLayout>
            <Head title="Dashboard" />
            
            <main className="max-w-[1200px] mx-auto px-4 md:px-10 py-8">
                <section className="mb-8">
                    <div className="w-full bg-gradient-to-r from-[#13ec92] to-[#11c479] rounded-3xl p-8 md:p-10 relative overflow-hidden shadow-xl shadow-[#13ec92]/20 group cursor-pointer hover:scale-[1.01] transition-transform">
                        <div className="relative z-10 max-w-xl">
                            <div className="flex items-center gap-3 mb-4">
                                <span className="px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-bold text-[#10221a] uppercase tracking-wider">Insight Semanal</span>
                                <span className="flex items-center gap-1 text-xs font-bold text-[#10221a]/70">
                                    <span className="material-symbols-outlined text-sm">trending_up</span>
                                    +12% vs semana passada
                                </span>
                            </div>
                            <h2 className="text-3xl md:text-4xl font-black text-[#10221a] leading-tight mb-4">
                                Você está a 3 dias de atingir seu recorde!
                            </h2>
                            <p className="text-[#10221a]/80 font-medium mb-8 leading-relaxed">
                                Mantenha o foco. Sua constância nos treinos e estudos aumentou 40% este mês. Que tal dobrar a meta de leitura hoje?
                            </p>
                            <button className="h-12 px-8 bg-[#10221a] text-white rounded-full text-sm font-bold hover:bg-[#10221a]/80 transition-colors shadow-lg shadow-[#10221a]/10 flex items-center gap-2">
                                Ver detalhamento
                                <span className="material-symbols-outlined text-lg">arrow_forward</span>
                            </button>
                        </div>
                        
                        {/* Abstract Shapes Decoration */}
                        <div className="absolute right-0 top-0 w-[400px] h-[400px] bg-white/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3 group-hover:bg-white/15 transition-colors duration-500"></div>
                        <div className="absolute right-[100px] bottom-0 w-[300px] h-[300px] bg-[#10221a]/5 rounded-full blur-2xl translate-y-1/3 transform rotate-45"></div>
                    </div>
                </section>

                <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div className="lg:col-span-2 space-y-8">
                        <section className="space-y-6">
                            <div className="flex items-center justify-between">
                                <h2 className="text-2xl font-bold dark:text-white">Metas Ativas</h2>
                                <Link href={route('goals.create')} className="text-primary font-bold text-sm hover:underline">+ Nova Meta</Link>
                            </div>
                            <div className="space-y-4">
                                <div className="bg-white dark:bg-gray-800 rounded-3xl p-6 border border-[#dbe6e1] dark:border-gray-700 shadow-sm goal-card transition-all">
                                    <div className="flex items-start justify-between mb-4">
                                        <div className="flex items-center gap-4">
                                            <div className="size-12 rounded-xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-green-500">
                                                <span className="material-symbols-outlined">fitness_center</span>
                                            </div>
                                            <div>
                                                <h4 className="font-bold dark:text-white">Maratona 10km</h4>
                                                <p className="text-xs text-gray-500">Saúde & Bem-estar</p>
                                            </div>
                                        </div>
                                        <span className="text-sm font-bold text-primary">65%</span>
                                    </div>
                                    <div className="w-full h-2 bg-gray-100 dark:bg-gray-700 rounded-full mb-6">
                                        <div className="h-full bg-primary w-[65%] rounded-full"></div>
                                    </div>
                                    <div className="space-y-3">
                                        <label className="flex items-center gap-3 p-2 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-lg cursor-pointer transition-colors">
                                            <input defaultChecked className="rounded border-gray-300 text-primary focus:ring-primary" type="checkbox" />
                                            <span className="text-sm text-gray-600 dark:text-gray-300 line-through opacity-60">Correr 5km sem parar</span>
                                        </label>
                                        <label className="flex items-center gap-3 p-2 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-lg cursor-pointer transition-colors">
                                            <input className="rounded border-gray-300 text-primary focus:ring-primary" type="checkbox" />
                                            <span className="text-sm text-gray-600 dark:text-gray-300">Treino de tiro 400m (8x)</span>
                                        </label>
                                        <label className="flex items-center gap-3 p-2 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-lg cursor-pointer transition-colors">
                                            <input className="rounded border-gray-300 text-primary focus:ring-primary" type="checkbox" />
                                            <span className="text-sm text-gray-600 dark:text-gray-300">Comprar novo tênis de corrida</span>
                                        </label>
                                    </div>
                                </div>
                                <div className="bg-white dark:bg-gray-800 rounded-3xl p-6 border border-[#dbe6e1] dark:border-gray-700 shadow-sm goal-card transition-all">
                                    <div className="flex items-start justify-between mb-4">
                                        <div className="flex items-center gap-4">
                                            <div className="size-12 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-500">
                                                <span className="material-symbols-outlined">payments</span>
                                            </div>
                                            <div>
                                                <h4 className="font-bold dark:text-white">Reserva de Emergência</h4>
                                                <p className="text-xs text-gray-500">Financeiro</p>
                                            </div>
                                        </div>
                                        <span className="text-sm font-bold text-blue-500">30%</span>
                                    </div>
                                    <div className="w-full h-2 bg-gray-100 dark:bg-gray-700 rounded-full mb-6">
                                        <div className="h-full bg-blue-500 w-[30%] rounded-full"></div>
                                    </div>
                                    <div className="space-y-3">
                                        <label className="flex items-center gap-3 p-2 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-lg cursor-pointer transition-colors">
                                            <input defaultChecked className="rounded border-gray-300 text-blue-500 focus:ring-blue-500" type="checkbox" />
                                            <span className="text-sm text-gray-600 dark:text-gray-300 line-through opacity-60">Abrir conta investimento</span>
                                        </label>
                                        <label className="flex items-center gap-3 p-2 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-lg cursor-pointer transition-colors">
                                            <input className="rounded border-gray-300 text-blue-500 focus:ring-blue-500" type="checkbox" />
                                            <span className="text-sm text-gray-600 dark:text-gray-300">Aportar R$ 500 este mês</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section className="bg-white dark:bg-gray-800 rounded-3xl p-8 border border-[#dbe6e1] dark:border-gray-700 shadow-sm">
                            <div className="flex items-center justify-between mb-8">
                                <div>
                                    <h2 className="text-2xl font-bold dark:text-white">Evolução Financeira</h2>
                                    <p className="text-gray-500 text-sm">Patrimônio guardado nos últimos 6 meses</p>
                                </div>
                                <div className="text-right">
                                    <p className="text-2xl font-black text-blue-600 dark:text-blue-400">R$ 12.450,00</p>
                                    <p className="text-xs font-bold text-primary">+12% vs mês anterior</p>
                                </div>
                            </div>
                            <div className="w-full h-48 relative pt-4">
                                <svg className="w-full h-full" preserveAspectRatio="none" viewBox="0 0 1000 200">
                                    <defs>
                                        <linearGradient id="gradient" x1="0%" x2="0%" y1="0%" y2="100%">
                                            <stop offset="0%" stopColor="#3b82f6" stopOpacity="0.2"></stop>
                                            <stop offset="100%" stopColor="#3b82f6" stopOpacity="0"></stop>
                                        </linearGradient>
                                    </defs>
                                    <path d="M0,180 Q100,160 200,170 T400,140 T600,100 T800,60 T1000,40 V200 H0 Z" fill="url(#gradient)"></path>
                                    <path className="finance-chart-line" d="M0,180 Q100,160 200,170 T400,140 T600,100 T800,60 T1000,40" fill="none" stroke="#3b82f6" strokeLinecap="round" strokeWidth="4"></path>
                                    <circle cx="200" cy="170" fill="#3b82f6" r="6" stroke="white" strokeWidth="2"></circle>
                                    <circle cx="400" cy="140" fill="#3b82f6" r="6" stroke="white" strokeWidth="2"></circle>
                                    <circle cx="600" cy="100" fill="#3b82f6" r="6" stroke="white" strokeWidth="2"></circle>
                                    <circle cx="800" cy="60" fill="#3b82f6" r="6" stroke="white" strokeWidth="2"></circle>
                                    <circle cx="1000" cy="40" fill="#3b82f6" r="6" stroke="white" strokeWidth="2"></circle>
                                </svg>
                                <div className="flex justify-between mt-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                    <span>Jan</span>
                                    <span>Fev</span>
                                    <span>Mar</span>
                                    <span>Abr</span>
                                    <span>Mai</span>
                                    <span>Jun</span>
                                </div>
                            </div>
                        </section>
                    </div>

                    <div className="space-y-6">
                        <div className="bg-white dark:bg-gray-800 rounded-3xl border border-[#dbe6e1] dark:border-gray-700 shadow-sm overflow-hidden">
                            <div className="p-5 border-b border-[#dbe6e1] dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 flex items-center justify-between">
                                <h2 className="font-bold dark:text-white">Ranking XP</h2>
                                <span className="material-symbols-outlined text-primary text-xl">leaderboard</span>
                            </div>
                            <div className="p-4 space-y-3">
                                {[1, 2, 3].map((i) => (
                                    <div key={i} className={`flex items-center gap-3 p-2 rounded-xl ${i === 1 ? 'bg-primary/10 border border-primary/20' : 'opacity-70'}`}>
                                        <span className={`text-sm font-bold w-4 ${i === 1 ? 'text-primary' : 'text-gray-400'}`}>{i}</span>
                                        <div className={`size-8 rounded-full overflow-hidden ${i === 1 ? 'border border-primary' : 'bg-gray-200'}`}>
                                            <img alt="User" className="w-full h-full object-cover" src={`https://i.pravatar.cc/100?img=${i + 20}`} />
                                        </div>
                                        <div className="flex-1 min-w-0">
                                            <p className="text-xs font-bold dark:text-white truncate">{i === 1 ? 'Você' : `User ${i}`}</p>
                                            <p className={`text-[10px] font-bold ${i === 1 ? 'text-primary' : 'text-gray-500'}`}>{2000 - i * 100} XP</p>
                                        </div>
                                    </div>
                                ))}
                            </div>
                            <div className="p-4 pt-0">
                                <button className="w-full py-2 bg-gray-100 dark:bg-gray-700 text-xs font-bold rounded-lg dark:text-white">Ver Ranking Completo</button>
                            </div>
                        </div>

                        <div className="bg-white dark:bg-gray-800 rounded-3xl border border-[#dbe6e1] dark:border-gray-700 shadow-sm overflow-hidden">
                            <div className="p-5 border-b border-[#dbe6e1] dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 flex items-center justify-between">
                                <h2 className="font-bold dark:text-white">Conquistas</h2>
                                <span className="material-symbols-outlined text-accent text-xl">military_tech</span>
                            </div>
                            <div className="p-5 grid grid-cols-2 gap-3">
                                <div className="flex flex-col items-center p-3 bg-yellow-50 dark:bg-yellow-900/10 rounded-2xl border border-yellow-100 dark:border-yellow-900/30">
                                    <span className="material-symbols-outlined text-yellow-500 mb-1">workspace_premium</span>
                                    <span className="text-[10px] font-bold dark:text-white text-center">Madrugador</span>
                                </div>
                                <div className="flex flex-col items-center p-3 bg-blue-50 dark:bg-blue-900/10 rounded-2xl border border-blue-100 dark:border-blue-900/30">
                                    <span className="material-symbols-outlined text-blue-500 mb-1">menu_book</span>
                                    <span className="text-[10px] font-bold dark:text-white text-center">Leitor</span>
                                </div>
                                <div className="flex flex-col items-center p-3 bg-green-50 dark:bg-green-900/10 rounded-2xl border border-green-100 dark:border-green-900/30">
                                    <span className="material-symbols-outlined text-green-500 mb-1">fitness_center</span>
                                    <span className="text-[10px] font-bold dark:text-white text-center">Atleta</span>
                                </div>
                                <div className="flex flex-col items-center p-3 bg-gray-100 dark:bg-gray-700/50 rounded-2xl border border-dashed border-gray-300 dark:border-gray-600">
                                    <span className="material-symbols-outlined text-gray-400 mb-1">lock</span>
                                    <span className="text-[10px] font-bold text-gray-400 text-center">Bloqueado</span>
                                </div>
                            </div>
                        </div>

                        <div className="bg-gradient-to-br from-gray-900 to-black dark:from-primary dark:to-green-600 rounded-3xl p-6 text-white shadow-xl">
                            <div className="flex justify-between items-start mb-4">
                                <div>
                                    <p className="text-[10px] font-bold opacity-70 uppercase tracking-widest">Nível Atual</p>
                                    <h4 className="text-2xl font-black">Nível 15</h4>
                                </div>
                                <div className="size-10 rounded-full bg-white/20 flex items-center justify-center">
                                    <span className="material-symbols-outlined">trending_up</span>
                                </div>
                            </div>
                            <div className="space-y-2">
                                <div className="flex justify-between text-[10px] font-bold">
                                    <span>XP: 250 / 500</span>
                                    <span>50%</span>
                                </div>
                                <div className="w-full h-1.5 bg-white/20 rounded-full overflow-hidden">
                                    <div className="h-full bg-white w-1/2"></div>
                                </div>
                                <p className="text-[10px] text-white/70 italic mt-2">Faltam 250 XP para "Mestre das Montanhas"</p>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

        </AuthenticatedLayout>
    );
}
