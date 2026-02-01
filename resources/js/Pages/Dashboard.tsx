import { Head, Link, useForm, router } from '@inertiajs/react';
import { PageProps } from '@/types';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import React, { useState } from 'react';

declare function route(name: string, params?: any): string;

interface Goal {
    id: number;
    title: string;
    category: string;
    is_streak_enabled: boolean;
    current_streak: number;
    target_value: number | null;
    deadline: string | null;
    status: string;
    micro_tasks: {
        id: number;
        title: string;
        is_completed: boolean;
    }[];
    can_complete_streak?: boolean;
}

interface DashboardProps extends PageProps {
    activeGoals: Goal[];
    todayCompletions: number;
    xp: {
        current: number;
        level: number;
        next_level_threshold: number;
        progress_percentage: number;
        current_level_xp: number;
        xp_needed_for_level: number;
    };
}

export default function Dashboard({ auth, activeGoals, todayCompletions, xp }: DashboardProps) {
    const user = auth.user;
    const [search, setSearch] = useState('');
    const [expandedCards, setExpandedCards] = useState<Record<number, boolean>>({});

    // XP Calculation variables for display
    const currentXp = xp?.current || 0;
    const level = xp?.level || 1;
    const progressPercentage = xp?.progress_percentage || 0;
    const currentLevelXp = xp?.current_level_xp || 0;
    const xpNeededForLevel = xp?.xp_needed_for_level || 1000;

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

    const filteredGoals = activeGoals.filter(goal => 
        goal.title.toLowerCase().includes(search.toLowerCase())
    );

    return (
        <AuthenticatedLayout>
            <Head title="Dashboard" />

            <main className="max-w-[1440px] mx-auto px-4 md:px-10 py-8">
                {/* XP Display Section */}
                <section className="mb-6">
                    <div className="bg-gray-50 dark:bg-gray-800/50 rounded-2xl p-4 flex flex-col md:flex-row items-center gap-4 border border-[#dbe6e1] dark:border-gray-700">
                        <div className="flex items-center gap-3 min-w-fit">
                            <div className="size-10 rounded-xl bg-gray-900 dark:bg-primary flex items-center justify-center text-white dark:text-gray-900 shadow-sm">
                                <span className="text-lg font-black">Lvl {level}</span>
                            </div>
                            <div className="hidden sm:block">
                                <p className="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none">Status</p>
                                <p className="text-xs font-bold dark:text-white">Iniciante</p>
                            </div>
                        </div>
                        <div className="flex-1 w-full space-y-1.5">
                            <div className="flex justify-between items-end">
                                <span className="text-[11px] font-extrabold text-gray-500 dark:text-gray-400">{currentLevelXp} / {xpNeededForLevel} XP</span>
                                <span className="text-[11px] font-black text-primary">Nível {level + 1} em {xpNeededForLevel - currentLevelXp} XP</span>
                            </div>
                            <div className="w-full h-2.5 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                <div className="h-full bg-gradient-to-r from-primary to-green-400 rounded-full shadow-[0_0_10px_rgba(19,236,146,0.3)] transition-all duration-1000" style={{ width: `${progressPercentage}%` }}></div>
                            </div>
                        </div>
                    </div>
                </section>

                {/* <section className="mb-8">
                    <div className="bg-gradient-to-r from-primary/20 to-blue-500/10 border border-primary/30 rounded-3xl p-6 flex items-center gap-6">
                        <div className="size-14 rounded-2xl bg-white dark:bg-gray-800 flex items-center justify-center shadow-lg border border-primary/20">
                            <span className="material-symbols-outlined text-4xl icon-gradient-primary text-primary">trending_up</span>
                        </div>
                        <div>
                            <h3 className="text-sm font-bold text-primary uppercase tracking-widest mb-1">Insight Semanal</h3>
                            <p className="text-xl font-extrabold dark:text-white">Você completou <span className="text-orange-500">{todayCompletions} ofensivas</span> hoje! Mantenha o ritmo para dobrar seu XP.</p>
                        </div>
                    </div>
                </section> */}

                <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {/* Active Goals Section */}
                    <div className="lg:col-span-2 order-2 lg:order-1 space-y-8">
                        <section className="space-y-6">
                            <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                <h2 className="text-2xl font-bold dark:text-white shrink-0">Metas Ativas</h2>
                                
                                <div className="flex flex-1 items-center gap-3">
                                    <div className="relative flex-1 group">
                                        <span className="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-primary transition-colors">search</span>
                                        <input 
                                            type="text"
                                            placeholder="Pesquisar metas..."
                                            value={search}
                                            onChange={e => setSearch(e.target.value)}
                                            className="w-full h-11 pl-12 pr-4 bg-white/50 dark:bg-gray-800/50 backdrop-blur-md border border-[#dbe6e1] dark:border-gray-700 rounded-2xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all dark:text-white"
                                        />
                                    </div>
                                    <Link href={route('goals.create')} className="bg-primary hover:bg-primary/90 text-gray-900 h-11 px-5 rounded-2xl font-black text-sm transition-all shadow-md shadow-primary/20 flex items-center gap-2 shrink-0">
                                        <span className="material-symbols-outlined text-lg">add_circle</span>
                                        Nova
                                    </Link>
                                </div>
                            </div>

                            <div className="columns-1 md:columns-2 gap-4">
                                {filteredGoals.length > 0 ? (
                                    filteredGoals.map((goal) => {
                                        const styles = getCategoryStyles(goal.category);
                                        const totalTasks = goal.micro_tasks?.length || 0;
                                        const completedTasks = goal.micro_tasks?.filter(t => !!t.is_completed).length || 0;
                                        const progress = totalTasks > 0 ? Math.round((completedTasks / totalTasks) * 100) : 0;

                                        return (
                                            <div key={goal.id} className={`break-inside-avoid mb-4 bg-white dark:bg-gray-800 rounded-3xl p-6 border transition-all hover:shadow-md ${
                                                goal.is_streak_enabled
                                                ? 'border-orange-200 dark:border-orange-900/40 bg-gradient-to-br from-white to-orange-50/30 dark:from-gray-800 dark:to-orange-900/5'
                                                : 'border-[#dbe6e1] dark:border-gray-700 shadow-sm'
                                            }`}>
                                                <div className="flex items-start justify-between mb-4">
                                                    <div className="flex items-center gap-4">
                                                        <div className={`size-12 rounded-xl ${styles.bg} flex items-center justify-center ${styles.color}`}>
                                                            <span className="material-symbols-outlined">{styles.icon}</span>
                                                        </div>
                                                        <Link href={route('goals.show', goal.id)} className="group">
                                                            <h4 className="font-bold dark:text-white capitalize group-hover:text-primary transition-colors">{goal.title}</h4>
                                                            <p className="text-xs text-gray-500 capitalize">{goal.category}</p>
                                                        </Link>
                                                    </div>
                                                    <div className="text-right">
                                                        {totalTasks > 0 && (
                                                            <span className={`text-sm font-black ${styles.color}`}>{progress}%</span>
                                                        )}
                                                        {goal.is_streak_enabled && (
                                                            <div className="flex items-center justify-end gap-1.5 text-lg font-black text-orange-500 mt-1">
                                                                <span className="material-symbols-outlined !text-2xl" style={{ fontVariationSettings: "'FILL' 1" }}>local_fire_department</span>
                                                                {goal.current_streak} dias
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

                                                <div className="space-y-3">
                                                    {(expandedCards[goal.id] ? goal.micro_tasks : goal.micro_tasks?.slice(0, 3)).map((task: any) => (
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
                                                            <span className={`text-sm text-gray-600 dark:text-gray-300 ${task.is_completed ? 'line-through opacity-60' : ''}`}>
                                                                 {task.title}
                                                            </span>
                                                        </div>
                                                    ))}
                                                    
                                                    {totalTasks > 3 && (
                                                        <button 
                                                            onClick={() => setExpandedCards(prev => ({ ...prev, [goal.id]: !prev[goal.id] }))}
                                                            className="w-full mt-2 py-2 px-3 flex items-center justify-between text-[11px] font-bold text-gray-400 hover:text-primary hover:bg-primary/5 rounded-lg transition-all border border-transparent hover:border-primary/10"
                                                        >
                                                            <span>{expandedCards[goal.id] ? 'Ver menos' : `+ ${totalTasks - 3} outras tarefas`}</span>
                                                            <span className={`material-symbols-outlined text-sm transition-transform ${expandedCards[goal.id] ? 'rotate-180' : ''}`}>
                                                                keyboard_arrow_down
                                                            </span>
                                                        </button>
                                                    )}
                                                </div>

                                                {goal.is_streak_enabled && (
                                                    <div className="mt-6 pt-4 border-t border-gray-50 dark:border-gray-700">
                                                        {goal.can_complete_streak ? (
                                                            <Link
                                                                href={route('goals.streak', goal.id)}
                                                                method="post"
                                                                as="button"
                                                                className="w-full h-10 bg-orange-500 hover:bg-orange-600 text-white rounded-xl text-xs font-bold flex items-center justify-center gap-2 transition-all shadow-lg shadow-orange-500/20"
                                                            >
                                                                <span className="material-symbols-outlined text-sm" style={{ fontVariationSettings: "'FILL' 1" }}>local_fire_department</span>
                                                                Confirmar Ofensiva Hoje +100 xp
                                                            </Link>
                                                        ) : (
                                                            <div className="w-full h-10 bg-green-50 dark:bg-green-900/10 text-green-600 dark:text-green-400 rounded-xl text-xs font-bold flex items-center justify-center gap-2 border border-green-100 dark:border-green-900/30">
                                                                <span className="material-symbols-outlined text-sm">verified</span>
                                                                Ofensiva Concluída!
                                                            </div>
                                                        )}
                                                    </div>
                                                )}
                                            </div>
                                        );
                                    })
                                ) : (
                                    <div className="bg-white dark:bg-gray-800 rounded-3xl p-10 border border-dashed border-[#dbe6e1] dark:border-gray-700 text-center space-y-4">
                                        <div className="size-16 rounded-full bg-gray-50 dark:bg-gray-700/50 flex items-center justify-center mx-auto text-gray-300">
                                            <span className="material-symbols-outlined !text-3xl">add_task</span>
                                        </div>
                                        <div>
                                            <h4 className="font-bold dark:text-white">
                                                {search ? 'Nenhuma meta encontrada' : 'Nenhuma meta ativa'}
                                            </h4>
                                            <p className="text-sm text-gray-500">
                                                {search ? `Não encontramos metas para "${search}"` : 'Que tal começar algo novo hoje?'}
                                            </p>
                                        </div>
                                        {!search && (
                                            <Link href={route('goals.create')} className="inline-flex h-10 px-6 bg-primary text-[#111815] rounded-full text-xs font-bold items-center hover:scale-105 transition-transform">
                                                Criar Minha Primeira Meta
                                            </Link>
                                        )}
                                    </div>
                                )}
                            </div>
                        </section>
                    </div>

                    <div className="space-y-6 order-1 lg:order-2">
                        <div className="bg-white dark:bg-gray-800 rounded-3xl border border-[#dbe6e1] dark:border-gray-700 shadow-sm overflow-hidden">
                            <div className="p-5 border-b border-[#dbe6e1] dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 flex items-center justify-between">
                                <h2 className="font-bold dark:text-white">Ranking XP</h2>
                                <span className="material-symbols-outlined icon-gradient-trophy text-2xl text-yellow-500">leaderboard</span>
                            </div>
                            <div className="p-4 space-y-3">
                                <div className="flex items-center gap-3 p-2 rounded-xl bg-primary/10 border border-primary/20">
                                    <div className="size-8 rounded-full overflow-hidden border border-primary">
                                        <img alt="User" className="w-full h-full object-cover" src={user.avatar_url || "https://ui-avatars.com/api/?name=" + user.name} />
                                    </div>
                                    <div className="flex-1 min-w-0">
                                        <p className="text-xs font-bold dark:text-white truncate">Você</p>
                                        <p className="text-[10px] text-primary font-bold">{currentXp} XP</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div className="bg-white dark:bg-gray-800 rounded-3xl border border-[#dbe6e1] dark:border-gray-700 shadow-sm overflow-hidden">
                            <div className="p-5 border-b border-[#dbe6e1] dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 flex items-center justify-between">
                                <h2 className="font-bold dark:text-white">Conquistas</h2>
                                <span className="material-symbols-outlined icon-gradient-trophy text-2xl text-yellow-500">workspace_premium</span>
                            </div>
                            <div className="p-5 grid grid-cols-2 gap-3">
                                <div className="flex flex-col items-center p-3 bg-yellow-50 dark:bg-yellow-900/10 rounded-2xl border border-yellow-100 dark:border-yellow-900/30">
                                    <span className="material-symbols-outlined text-yellow-500 mb-1 text-2xl">stars</span>
                                    <span className="text-[10px] font-bold dark:text-white text-center">Madrugador</span>
                                </div>
                                <div className="flex flex-col items-center p-3 bg-blue-50 dark:bg-blue-900/10 rounded-2xl border border-blue-100 dark:border-blue-900/30">
                                    <span className="material-symbols-outlined text-blue-500 mb-1 text-2xl">menu_book</span>
                                    <span className="text-[10px] font-bold dark:text-white text-center">Leitor</span>
                                </div>
                                <div className="flex flex-col items-center p-3 bg-green-50 dark:bg-green-900/10 rounded-2xl border border-green-100 dark:border-green-900/30">
                                    <span className="material-symbols-outlined text-green-500 mb-1 text-2xl">fitness_center</span>
                                    <span className="text-[10px] font-bold dark:text-white text-center">Atleta</span>
                                </div>
                                <div className="flex flex-col items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-2xl border border-dashed border-gray-300 dark:border-gray-600">
                                    <span className="material-symbols-outlined text-gray-400 mb-1 text-2xl">lock</span>
                                    <span className="text-[10px] font-bold text-gray-400 text-center">Bloqueado</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </AuthenticatedLayout>
    );
}
