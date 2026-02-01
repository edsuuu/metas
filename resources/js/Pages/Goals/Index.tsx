import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';
import { PageProps } from '@/types';
import { useState } from 'react';

declare function route(name: string, params?: any): string;

interface Goal {
    id: number;
    title: string;
    category: string;
    deadline: string | null;
    target_value: number | null;
    is_streak_enabled: boolean;
    status: string;
    micro_tasks?: { id: number; title: string; is_completed: boolean }[];
}

export default function GoalsIndex({ auth, goals }: PageProps & { goals: Goal[] }) {
    const [search, setSearch] = useState('');

    const filteredGoals = goals.filter(goal => 
        goal.title.toLowerCase().includes(search.toLowerCase()) ||
        goal.category.toLowerCase().includes(search.toLowerCase())
    );

    return (
        <AuthenticatedLayout>
            <Head title="Minhas Metas" />
            
            <main className="max-w-[1200px] mx-auto px-4 md:px-10 py-12">
                <div className="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
                    <div>
                        <h1 className="text-3xl font-black text-[#111815] dark:text-white">Minhas Metas</h1>
                        <p className="text-gray-500 dark:text-gray-400 mt-1">Acompanhe seu progresso e conquiste o topo.</p>
                    </div>
                    <div className="flex flex-col sm:flex-row items-center gap-4 w-full md:w-auto">
                        <div className="relative w-full sm:w-64 group">
                            <span className="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-primary transition-colors">search</span>
                            <input 
                                type="text"
                                placeholder="Buscar metas..."
                                value={search}
                                onChange={e => setSearch(e.target.value)}
                                className="w-full h-11 pl-12 pr-4 bg-white dark:bg-gray-800 border border-[#dbe6e1] dark:border-gray-700 rounded-2xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all dark:text-white"
                            />
                        </div>
                        <Link 
                            href={route('goals.create')}
                            className="bg-primary text-background-dark h-11 px-6 rounded-xl text-sm font-bold shadow-lg shadow-primary/20 flex items-center justify-center gap-2 hover:scale-105 transition-all w-full sm:w-auto shrink-0"
                        >
                            <span className="material-symbols-outlined text-[18px]">add</span>
                            Criar Nova Meta
                        </Link>
                    </div>
                </div>

                {goals.length === 0 ? (
                    <div className="bg-white dark:bg-gray-800 rounded-3xl p-12 text-center border-2 border-dashed border-gray-200 dark:border-gray-700">
                        <div className="size-20 rounded-full bg-gray-50 dark:bg-gray-700 mx-auto flex items-center justify-center mb-6 text-gray-300">
                            <span className="material-symbols-outlined !text-4xl">flag</span>
                        </div>
                        <h3 className="text-xl font-bold dark:text-white mb-2">Nenhuma meta ativa</h3>
                        <p className="text-gray-500 dark:text-gray-400 mb-8 max-w-xs mx-auto">Sua jornada começa com o primeiro passo. Defina um objetivo hoje!</p>
                        <Link 
                            href={route('goals.create')}
                            className="inline-flex items-center gap-2 text-primary font-bold hover:underline"
                        >
                            Começar agora
                            <span className="material-symbols-outlined text-sm">arrow_forward</span>
                        </Link>
                    </div>
                ) : filteredGoals.length === 0 ? (
                    <div className="bg-white dark:bg-gray-800 rounded-3xl p-12 text-center border-2 border-dashed border-gray-200 dark:border-gray-700">
                        <div className="size-20 rounded-full bg-gray-50 dark:bg-gray-700 mx-auto flex items-center justify-center mb-6 text-gray-300">
                            <span className="material-symbols-outlined !text-4xl">search_off</span>
                        </div>
                        <h3 className="text-xl font-bold dark:text-white mb-2">Nenhum resultado</h3>
                        <p className="text-gray-500 dark:text-gray-400 max-w-xs mx-auto">Não encontramos metas que correspondam à sua busca "{search}".</p>
                    </div>
                ) : (
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        {filteredGoals.map((goal) => (
                            <div key={goal.id} className="bg-white dark:bg-gray-800 rounded-3xl p-6 border border-[#dbe6e1] dark:border-gray-700 shadow-sm hover:shadow-xl transition-all group">
                                <div className="flex items-start justify-between mb-4">
                                    <div className="flex items-center gap-4">
                                        <div className={`size-12 rounded-xl flex items-center justify-center shadow-sm ${
                                            goal.category === 'saude' ? 'bg-green-100 dark:bg-green-900/30 text-green-500' :
                                            goal.category === 'financeiro' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-500' :
                                            goal.category === 'carreira' ? 'bg-purple-100 dark:bg-purple-900/30 text-purple-500' :
                                            'bg-gray-100 dark:bg-gray-900/30 text-gray-500'
                                        }`}>
                                            <span className="material-symbols-outlined">
                                                {goal.category === 'saude' ? 'fitness_center' :
                                                 goal.category === 'financeiro' ? 'payments' :
                                                 goal.category === 'carreira' ? 'rocket_launch' : 'psychology'}
                                            </span>
                                        </div>
                                        <div>
                                            <h4 className="font-bold dark:text-white group-hover:text-primary transition-colors">{goal.title}</h4>
                                            <p className="text-[10px] text-gray-400 uppercase font-bold tracking-wider">{goal.category}</p>
                                        </div>
                                    </div>
                                    {goal.is_streak_enabled && (
                                        <div className="flex items-center gap-1 text-orange-500">
                                            <span className="material-symbols-outlined text-sm" style={{ fontVariationSettings: "'FILL' 1" }}>local_fire_department</span>
                                        </div>
                                    )}
                                </div>
                                
                                {goal.target_value && (
                                    <div className="mb-4">
                                        <p className="text-[10px] text-gray-400 font-bold mb-1 uppercase">Valor Alvo</p>
                                        <p className="text-sm font-black text-blue-600 dark:text-blue-400">R$ {Number(goal.target_value).toLocaleString('pt-BR', { minimumFractionDigits: 2 })}</p>
                                    </div>
                                )}

                                {goal.deadline && (
                                    <div className="mb-6">
                                        <p className="text-[10px] text-gray-400 font-bold mb-1 uppercase">Deadline</p>
                                        <div className="flex items-center gap-2">
                                            <span className="material-symbols-outlined text-xs text-gray-400">calendar_today</span>
                                            <span className="text-xs font-bold text-gray-600 dark:text-gray-300">
                                                {new Date(goal.deadline).toLocaleDateString('pt-BR')}
                                            </span>
                                        </div>
                                    </div>
                                )}

                                {goal.micro_tasks && goal.micro_tasks.length > 0 && (
                                    <div className="space-y-2 border-t border-gray-50 dark:border-gray-700/50 pt-4">
                                        {goal.micro_tasks.slice(0, 3).map((task, idx) => (
                                            <div key={idx} className="flex items-center gap-2 opacity-70">
                                                <div className={`size-4 rounded border ${task.is_completed ? 'bg-primary border-primary flex items-center justify-center' : 'border-gray-200 dark:border-gray-600'}`}>
                                                    {task.is_completed && <span className="material-symbols-outlined text-[10px] text-background-dark font-black">check</span>}
                                                </div>
                                                <span className={`text-[11px] font-medium dark:text-gray-300 truncate ${task.is_completed ? 'line-through opacity-50' : ''}`}>{task.title}</span>
                                            </div>
                                        ))}
                                        {goal.micro_tasks.length > 3 && (
                                            <p className="text-[10px] text-primary font-bold pt-1">+ {goal.micro_tasks.length - 3} outras tarefas</p>
                                        )}
                                    </div>
                                )}

                                <div className="mt-6">
                                    <Link 
                                        href={route('goals.show', goal.id)}
                                        className="w-full h-10 border-2 border-gray-100 dark:border-gray-700 rounded-xl text-xs font-bold text-gray-500 hover:border-primary hover:text-primary transition-all flex items-center justify-center"
                                    >
                                        Ver Detalhes
                                    </Link>
                                </div>
                            </div>
                        ))}
                    </div>
                )}
            </main>
        </AuthenticatedLayout>
    );
}
