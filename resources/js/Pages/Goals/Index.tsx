import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, router } from '@inertiajs/react';
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
    micro_tasks: { id: number; title: string; is_completed: boolean }[];
}

interface GoalsIndexProps extends PageProps {
    goals: Goal[];
}

export default function GoalsIndex({ auth, goals }: GoalsIndexProps) {
    const [search, setSearch] = useState('');
    const [expandedCards, setExpandedCards] = useState<Record<number, boolean>>({});

    const toggleMicroTask = (id: number) => {
        router.patch(route('micro-tasks.toggle', id), {}, {
            preserveScroll: true,
        });
    };

    const getCategoryStyles = (category: string) => {
        const styles: Record<string, { icon: string, color: string, bg: string, bar: string }> = {
            saude: { icon: 'fitness_center', color: 'text-green-500', bg: 'bg-green-100 dark:bg-green-900/30', bar: 'bg-green-500' },
            financeiro: { icon: 'payments', color: 'text-blue-500', bg: 'bg-blue-100 dark:bg-blue-900/30', bar: 'bg-blue-500' },
            carreira: { icon: 'rocket_launch', color: 'text-purple-500', bg: 'bg-purple-100 dark:bg-purple-900/30', bar: 'bg-purple-500' },
            pessoal: { icon: 'psychology', color: 'text-orange-500', bg: 'bg-orange-100 dark:bg-orange-900/30', bar: 'bg-orange-500' },
        };
        return styles[category] || { icon: 'flag', color: 'text-primary', bg: 'bg-primary/10', bar: 'bg-primary' };
    };

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
                    <div className="columns-1 md:columns-2 lg:columns-3 gap-6">
                        {filteredGoals.map((goal) => {
                            const styles = getCategoryStyles(goal.category);
                            const totalTasks = goal.micro_tasks?.length || 0;
                            const completedTasks = goal.micro_tasks?.filter(t => !!t.is_completed).length || 0;
                            const progress = totalTasks > 0 ? Math.round((completedTasks / totalTasks) * 100) : 0;

                            return (
                                <div key={goal.id} className="break-inside-avoid mb-6 bg-white dark:bg-gray-800 rounded-3xl p-6 border border-[#dbe6e1] dark:border-gray-700 shadow-sm hover:shadow-xl transition-all group">
                                    <div className="flex items-start justify-between mb-4">
                                        <div className="flex items-center gap-4">
                                            <div className={`size-12 rounded-xl flex items-center justify-center shadow-sm ${styles.bg} ${styles.color}`}>
                                                <span className="material-symbols-outlined">{styles.icon}</span>
                                            </div>
                                            <Link href={route('goals.show', goal.id)}>
                                                <h4 className="font-bold dark:text-white group-hover:text-primary transition-colors">{goal.title}</h4>
                                                <p className="text-[10px] text-gray-400 uppercase font-bold tracking-wider">{goal.category}</p>
                                            </Link>
                                        </div>
                                        <div className="text-right">
                                            {totalTasks > 0 && (
                                                <span className={`text-sm font-black ${styles.color}`}>{progress}%</span>
                                            )}
                                            {goal.is_streak_enabled && (
                                                <div className="flex items-center justify-end gap-1 text-orange-500 mt-1">
                                                    <span className="material-symbols-outlined text-sm" style={{ fontVariationSettings: "'FILL' 1" }}>local_fire_department</span>
                                                </div>
                                            )}
                                        </div>
                                    </div>
                                    
                                    {totalTasks > 0 && (
                                        <div className="w-full h-2.5 bg-gray-200/60 dark:bg-gray-700 rounded-full mb-6 relative overflow-hidden border border-gray-100 dark:border-gray-800">
                                            <div
                                                className={`h-full ${styles.bar} rounded-full transition-all duration-1000 ease-out shadow-[0_0_10px_rgba(0,0,0,0.1)]`}
                                                style={{ width: `${progress}%` }}
                                            ></div>
                                        </div>
                                    )}

                                    {goal.target_value && (
                                        <div className="mb-4">
                                            <p className="text-[10px] text-gray-400 font-bold mb-1 uppercase">Valor Alvo</p>
                                            <p className="text-sm font-black text-blue-600 dark:text-blue-400">R$ {Number(goal.target_value).toLocaleString('pt-BR', { minimumFractionDigits: 2 })}</p>
                                        </div>
                                    )}

                                    {goal.deadline && (
                                        <div className="mb-4">
                                            <p className="text-[10px] text-gray-400 font-bold mb-1 uppercase">Deadline</p>
                                            <div className="flex items-center gap-2">
                                                <span className="material-symbols-outlined text-xs text-gray-400">calendar_today</span>
                                                <span className="text-xs font-bold text-gray-600 dark:text-gray-300">
                                                    {new Date(goal.deadline).toLocaleDateString('pt-BR')}
                                                </span>
                                            </div>
                                        </div>
                                    )}

                                    {totalTasks > 0 && (
                                        <div className="space-y-3 border-t border-gray-50 dark:border-gray-700/50 pt-4 mb-6">
                                            {(expandedCards[goal.id] ? goal.micro_tasks : goal.micro_tasks.slice(0, 3)).map((task) => (
                                                <div 
                                                    key={task.id} 
                                                    className="flex items-center gap-3 p-2 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-lg cursor-pointer transition-colors"
                                                    onClick={() => toggleMicroTask(task.id)}
                                                >
                                                    <input
                                                        readOnly
                                                        checked={!!task.is_completed}
                                                        className={`rounded border-gray-300 ${styles.color.replace('text-', 'text-')} focus:ring-current pointer-events-none`}
                                                        type="checkbox"
                                                    />
                                                    <span className={`text-[11px] font-medium dark:text-gray-300 truncate ${task.is_completed ? 'line-through opacity-50' : ''}`}>
                                                        {task.title}
                                                    </span>
                                                </div>
                                            ))}
                                            
                                            {totalTasks > 3 && (
                                                <button 
                                                    onClick={(e) => {
                                                        e.preventDefault();
                                                        e.stopPropagation();
                                                        setExpandedCards(prev => ({ ...prev, [goal.id]: !prev[goal.id] }));
                                                    }}
                                                    className="w-full mt-2 py-2 px-3 flex items-center justify-between text-[11px] font-bold text-gray-400 hover:text-primary hover:bg-primary/5 rounded-lg transition-all border border-transparent hover:border-primary/10"
                                                >
                                                    <span>{expandedCards[goal.id] ? 'Ver menos' : `+ ${totalTasks - 3} outras tarefas`}</span>
                                                    <span className={`material-symbols-outlined text-sm transition-transform ${expandedCards[goal.id] ? 'rotate-180' : ''}`}>
                                                        keyboard_arrow_down
                                                    </span>
                                                </button>
                                            )}
                                        </div>
                                    )}

                                    <div className="mt-auto">
                                        <Link 
                                            href={route('goals.show', goal.id)}
                                            className="w-full h-10 border-2 border-gray-100 dark:border-gray-700 rounded-xl text-xs font-bold text-gray-500 hover:border-primary hover:text-primary transition-all flex items-center justify-center"
                                        >
                                            Ver Detalhes
                                        </Link>
                                    </div>
                                </div>
                            );
                        })}
                    </div>
                )}
            </main>
        </AuthenticatedLayout>
    );
}
