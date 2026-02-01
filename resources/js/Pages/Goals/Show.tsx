import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, useForm, router } from '@inertiajs/react';
import { PageProps } from '@/types';
import { useState } from 'react';
import Modal from '@/Components/Modal';

declare function route(name: string, params?: any): string;

interface Goal {
    id: number;
    title: string;
    category: string;
    deadline: string | null;
    completed_at: string | null;
    target_value: number | null;
    is_streak_enabled: boolean;
    current_streak: number;
    last_completed_at: string | null;
    status: string;
    created_at: string;
    updated_at: string;
    micro_tasks: { id: number; title: string; is_completed: boolean }[];
}

interface Props extends PageProps {
    goal: Goal;
    can_complete_streak: boolean;
}

export default function GoalShow({ auth, goal, can_complete_streak }: Props) {
    const { post, patch, delete: destroy, processing } = useForm();

    const [showDeleteModal, setShowDeleteModal] = useState(false);
    const [showDeactivateModal, setShowDeactivateModal] = useState(false);

    const handleStreakConfirm = () => {
        post(route('goals.streak', goal.id));
    };

    const confirmDelete = () => {
        destroy(route('goals.destroy', goal.id), {
            onSuccess: () => setShowDeleteModal(false),
        });
    };

    const confirmDeactivate = () => {
        patch(route('goals.deactivate', goal.id), {
            onSuccess: () => setShowDeactivateModal(false),
        });
    };

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

    const styles = getCategoryStyles(goal.category);
    const totalTasks = goal.micro_tasks?.length || 0;
    const completedTasks = goal.micro_tasks?.filter(t => !!t.is_completed).length || 0;
    const progress = totalTasks > 0 ? Math.round((completedTasks / totalTasks) * 100) : 0;

    return (
        <AuthenticatedLayout>
            <Head title={`Detalhes: ${goal.title}`} />
            
            <main className="max-w-[1200px] mx-auto px-4 md:px-10 py-12">
                <div className="mb-8">
                    <Link href={route('goals.index')} className="text-gray-500 hover:text-primary transition-colors flex items-center gap-2 text-sm font-bold">
                        <span className="material-symbols-outlined text-lg">arrow_back</span>
                        Voltar para minhas metas
                    </Link>
                </div>

                <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {/* Main Content */}
                    <div className="lg:col-span-2 space-y-8">
                        <div className="bg-white dark:bg-gray-800 rounded-3xl p-8 border border-[#dbe6e1] dark:border-gray-700 shadow-sm relative overflow-hidden">
                            <div className="flex items-center gap-6 mb-8 relative z-10">
                                <div className={`size-16 rounded-2xl ${styles.bg} flex items-center justify-center ${styles.color} shadow-lg shadow-black/5`}>
                                    <span className="material-symbols-outlined !text-[32px]">{styles.icon}</span>
                                </div>
                                <div>
                                    <h1 className="text-3xl font-black text-[#111815] dark:text-white capitalize leading-tight">{goal.title}</h1>
                                    <div className="flex items-center gap-3 mt-1">
                                        <span className={`text-xs font-bold uppercase tracking-widest ${styles.color}`}>{goal.category}</span>
                                        <span className="size-1 bg-gray-300 rounded-full"></span>
                                        <span className="text-xs font-bold text-gray-400 uppercase tracking-widest">{goal.status === 'active' ? 'Ativa' : goal.status}</span>
                                    </div>
                                </div>
                            </div>

                            <div className="grid grid-cols-1 md:grid-cols-3 gap-6 relative z-10">
                                {goal.deadline && (
                                    <div className="p-4 bg-gray-50 dark:bg-gray-900/50 rounded-2xl border border-gray-100 dark:border-gray-700">
                                        <p className="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">Prazo Final</p>
                                        <div className="flex items-center gap-2">
                                            <span className="material-symbols-outlined text-primary">calendar_today</span>
                                            <span className="text-sm font-bold dark:text-gray-200">{new Date(goal.deadline).toLocaleDateString('pt-BR')}</span>
                                        </div>
                                    </div>
                                )}
                                {goal.target_value && (
                                    <div className="p-4 bg-blue-50/50 dark:bg-blue-900/10 rounded-2xl border border-blue-100 dark:border-blue-900/30">
                                        <p className="text-[10px] text-blue-400 font-bold uppercase tracking-wider mb-1">Valor Alvo</p>
                                        <div className="flex items-center gap-2 text-blue-600 dark:text-blue-400">
                                            <span className="material-symbols-outlined">payments</span>
                                            <span className="text-sm font-black">R$ {Number(goal.target_value).toLocaleString('pt-BR', { minimumFractionDigits: 2 })}</span>
                                        </div>
                                    </div>
                                )}
                                {goal.is_streak_enabled && (
                                    <div className="p-4 bg-orange-50/50 dark:bg-orange-900/10 rounded-2xl border border-orange-100 dark:border-orange-900/30">
                                        <p className="text-[10px] text-orange-400 font-bold uppercase tracking-wider mb-1">Ofensiva Atual</p>
                                        <div className="flex items-center gap-2 text-orange-500 text-lg">
                                            <span className="material-symbols-outlined !text-2xl" style={{ fontVariationSettings: "'FILL' 1" }}>local_fire_department</span>
                                            <span className="font-black">{goal.current_streak} dias</span>
                                        </div>
                                    </div>
                                )}
                            </div>

                            {/* Abstract Background Decoration */}
                            <div className={`absolute top-0 right-0 w-64 h-64 ${styles.bg} rounded-full blur-3xl opacity-20 translate-x-1/2 -translate-y-1/2`}></div>
                        </div>

                        {/* Checklist Section */}
                        <div className="bg-white dark:bg-gray-800 rounded-3xl p-8 border border-[#dbe6e1] dark:border-gray-700 shadow-sm">
                            <div className="flex items-center justify-between mb-8">
                                <div>
                                    <h3 className="text-xl font-bold dark:text-white">Checklist de Passos</h3>
                                    <p className="text-sm text-gray-500">Defina os marcos para atingir seu objetivo</p>
                                </div>
                                {totalTasks > 0 && (
                                    <div className="text-right">
                                        <p className="text-2xl font-black dark:text-white">{progress}%</p>
                                        <p className="text-xs text-gray-400 font-bold uppercase">Concluído</p>
                                    </div>
                                )}
                            </div>

                            {totalTasks > 0 && (
                                <div className="w-full h-3 bg-gray-100 dark:bg-gray-700 rounded-full mb-8 overflow-hidden relative">
                                    <div 
                                        className={`h-full ${styles.bar} rounded-full transition-all duration-1000 ease-out shadow-lg`} 
                                        style={{ width: `${progress}%` }}
                                    ></div>
                                </div>
                            )}

                            <div className="space-y-4">
                                {goal.micro_tasks.length > 0 ? (
                                    goal.micro_tasks.map((task) => (
                                        <div 
                                            key={task.id} 
                                            className="flex items-center gap-4 p-5 bg-gray-50 dark:bg-gray-900/30 rounded-2xl border border-transparent hover:border-primary/30 transition-all cursor-pointer group"
                                            onClick={() => toggleMicroTask(task.id)}
                                        >
                                            <input 
                                                type="checkbox" 
                                                checked={task.is_completed} 
                                                readOnly
                                                className={`size-6 rounded-lg ${styles.color.replace('text-', 'text-')} border-gray-300 focus:ring-primary transition-all group-hover:scale-110 pointer-events-none`} 
                                            />
                                            <div className="flex-1">
                                                <p className={`font-bold transition-all ${task.is_completed ? 'text-gray-400 line-through' : 'text-[#111815] dark:text-gray-200'}`}>
                                                    {task.title}
                                                </p>
                                            </div>
                                            <span className="material-symbols-outlined text-gray-300 group-hover:text-primary transition-colors">chevron_right</span>
                                        </div>
                                    ))
                                ) : (
                                    <div className="text-center py-10 text-gray-400">
                                        <p className="text-sm">Nenhuma tarefa cadastrada para esta meta.</p>
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>

                    {/* Sidebar / Streak Progress */}
                    <div className="space-y-8">
                        {goal.is_streak_enabled && (
                            <div className="bg-[#111815] dark:bg-black rounded-3xl p-8 text-white relative overflow-hidden shadow-2xl shadow-orange-500/10">
                                <div className="relative z-10 text-center">
                                    <div className="size-24 rounded-full bg-gradient-to-tr from-orange-600 to-yellow-500 mx-auto flex items-center justify-center mb-6 shadow-xl shadow-orange-500/40 animate-pulse">
                                        <span className="material-symbols-outlined !text-[48px]" style={{ fontVariationSettings: "'FILL' 1" }}>local_fire_department</span>
                                    </div>
                                    <h3 className="text-2xl font-black mb-2">Ofensiva: {goal.current_streak} Dias</h3>
                                    <p className="text-orange-200/60 text-sm font-medium mb-8">Mantenha o foco diário para não quebrar sua sequência!</p>
                                    
                                    {can_complete_streak ? (
                                        <button 
                                            onClick={handleStreakConfirm}
                                            disabled={processing}
                                            className="w-full h-14 bg-white text-[#111815] rounded-2xl font-black text-lg hover:bg-orange-50 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-3 shadow-xl"
                                        >
                                            <span className="material-symbols-outlined">check_circle</span>
                                            Confirmar Hoje!
                                        </button>
                                    ) : (
                                        <div className="w-full h-14 bg-white/10 rounded-2xl flex items-center justify-center gap-3 text-white/50 border border-white/5">
                                            <span className="material-symbols-outlined text-green-500">verified</span>
                                            <span className="font-bold">Concluído Hoje</span>
                                        </div>
                                    )}
                                </div>
                                <div className="absolute top-0 right-0 w-40 h-40 bg-orange-500/20 rounded-full blur-[80px]"></div>
                            </div>
                        )}

                        {goal.deadline && (
                            <div className="bg-[#111815] dark:bg-black rounded-3xl p-8 text-white relative overflow-hidden shadow-2xl shadow-blue-500/10">
                                <div className="relative z-10 text-center">
                                    <div className="size-24 rounded-full bg-gradient-to-tr from-blue-600 to-cyan-500 mx-auto flex items-center justify-center mb-6 shadow-xl shadow-blue-500/40">
                                        <span className="material-symbols-outlined !text-[48px]">flag</span>
                                    </div>
                                    <h3 className="text-2xl font-black mb-2">{goal.status === 'completed' ? 'Meta Concluída!' : 'Meta com Prazo'}</h3>
                                    <p className="text-blue-200/60 text-sm font-medium mb-8">
                                        {goal.status === 'completed' 
                                            ? 'Parabéns! Você alcançou seu objetivo.' 
                                            : `Prazo final: ${new Date(goal.deadline).toLocaleDateString('pt-BR')}`
                                        }
                                    </p>
                                    
                                    {goal.status !== 'completed' ? (
                                        <button 
                                            onClick={() => router.post(route('goals.complete', goal.id))}
                                            disabled={processing}
                                            className="w-full h-14 bg-white text-[#111815] rounded-2xl font-black text-lg hover:bg-blue-50 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-3 shadow-xl"
                                        >
                                            <span className="material-symbols-outlined">check_circle</span>
                                            Concluir Meta
                                        </button>
                                    ) : (
                                        <div className="w-full h-14 bg-white/10 rounded-2xl flex items-center justify-center gap-3 text-white/50 border border-white/5">
                                            <span className="material-symbols-outlined text-green-500">verified</span>
                                            <span className="font-bold">Concluída em {goal.completed_at && new Date(goal.completed_at).toLocaleDateString('pt-BR')}</span>
                                        </div>
                                    )}
                                </div>
                                <div className="absolute top-0 right-0 w-40 h-40 bg-blue-500/20 rounded-full blur-[80px]"></div>
                            </div>
                        )}

                        <div className="bg-white dark:bg-gray-800 rounded-3xl p-8 border border-[#dbe6e1] dark:border-gray-700 shadow-sm">
                            <h4 className="font-bold dark:text-white mb-6">Informações</h4>
                            <div className="space-y-4">
                                <div className="flex items-center justify-between text-sm">
                                    <span className="text-gray-400 font-medium">Criada em</span>
                                    <span className="font-bold dark:text-gray-200">{new Date(goal.created_at).toLocaleDateString('pt-BR')}</span>
                                </div>
                                <div className="flex items-center justify-between text-sm">
                                    <span className="text-gray-400 font-medium">Última atualização</span>
                                    <span className="font-bold dark:text-gray-200">{new Date(goal.updated_at).toLocaleDateString('pt-BR')}</span>
                                </div>
                                <div className="flex items-center justify-between text-sm">
                                    <span className="text-gray-400 font-medium">Categoria</span>
                                    <span className={`px-3 py-1 ${styles.bg} ${styles.color} rounded-full font-bold text-[10px] uppercase tracking-wider`}>
                                        {goal.category}
                                    </span>
                                </div>
                            </div>
                            
                            <div className="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700 space-y-3">
                                    <Link 
                                        href={route('goals.edit', goal.id)}
                                        className="w-full h-12 rounded-xl text-sm font-bold text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors flex items-center justify-center gap-2"
                                    >
                                        <span className="material-symbols-outlined text-lg">edit</span>
                                        Editar Meta
                                    </Link>
                                    
                                    {!(goal.deadline && goal.status === 'completed') && (
                                        <>
                                            <button 
                                                onClick={() => setShowDeactivateModal(true)}
                                                disabled={processing}
                                                className="w-full h-12 rounded-xl text-sm font-bold text-orange-600 hover:bg-orange-50 dark:hover:bg-orange-900/10 transition-colors flex items-center justify-center gap-2"
                                            >
                                                <span className="material-symbols-outlined text-lg">archive</span>
                                                Desativar Meta
                                            </button>
                                            <button 
                                                onClick={() => setShowDeleteModal(true)}
                                                disabled={processing}
                                                className="w-full h-12 rounded-xl text-sm font-bold text-red-500 hover:bg-red-50 dark:hover:bg-red-900/10 transition-colors flex items-center justify-center gap-2"
                                            >
                                                <span className="material-symbols-outlined text-lg">delete</span>
                                                Excluir Meta
                                            </button>
                                        </>
                                    )}

                                    {(goal.deadline && goal.status === 'completed') && (
                                        <div className="p-4 bg-gray-50 dark:bg-gray-900/50 rounded-xl text-center">
                                             <p className="text-xs text-gray-400">Esta meta foi concluída e arquivada no seu histórico de conquistas.</p>
                                        </div>
                                    )}
                                </div>
                        </div>
                    </div>
                </div>
            </main>

            {/* Deactivate Modal */}
            <Modal show={showDeactivateModal} onClose={() => setShowDeactivateModal(false)}>
                <div className="p-6">
                    <h2 className="text-xl font-bold dark:text-white mb-4">Desativar Meta</h2>
                    <p className="text-gray-500 dark:text-gray-300 mb-6">
                        Tem certeza que deseja desativar esta meta? Sua ofensiva (streak) atual será resetada.
                    </p>
                    <div className="flex justify-end gap-3">
                        <button
                            onClick={() => setShowDeactivateModal(false)}
                            className="px-4 py-2 text-sm font-bold text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
                        >
                            Cancelar
                        </button>
                        <button
                            onClick={confirmDeactivate}
                            className="px-4 py-2 text-sm font-bold text-white bg-orange-500 hover:bg-orange-600 rounded-lg transition-colors"
                            disabled={processing}
                        >
                            Confirmar Desativação
                        </button>
                    </div>
                </div>
            </Modal>

            {/* Delete Modal */}
            <Modal show={showDeleteModal} onClose={() => setShowDeleteModal(false)}>
                <div className="p-6">
                    <h2 className="text-xl font-bold dark:text-white mb-4 text-red-600">Excluir Meta</h2>
                    <p className="text-gray-500 dark:text-gray-300 mb-2">
                        Tem certeza que deseja excluir esta meta permanentemente?
                    </p>
                    <p className="text-sm text-red-500/80 mb-6 bg-red-50 dark:bg-red-900/20 p-3 rounded-lg border border-red-100 dark:border-red-900/30">
                        <span className="font-bold block mb-1">Atenção:</span>
                        Todo o histórico de conquistas, tarefas e XP associados a esta meta serão removidos da sua conta. Esta ação não pode ser desfeita.
                    </p>
                    <div className="flex justify-end gap-3">
                        <button
                            onClick={() => setShowDeleteModal(false)}
                            className="px-4 py-2 text-sm font-bold text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
                        >
                            Cancelar
                        </button>
                        <button
                            onClick={confirmDelete}
                            className="px-4 py-2 text-sm font-bold text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors"
                            disabled={processing}
                        >
                            Excluir Permanentemente
                        </button>
                    </div>
                </div>
            </Modal>
        </AuthenticatedLayout>
    );
}
