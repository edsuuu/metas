import React, { useState, useEffect } from 'react';
import { Head, useForm, Link } from '@inertiajs/react';
import { PageProps } from '@/types';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

declare function route(name: string, params?: any): string;

interface CreateGoalProps extends PageProps {
    goal?: any;
}

export default function CreateGoal({ goal }: CreateGoalProps) {
    const isEditing = !!goal;
    const [step, setStep] = useState(1);
    
    // Form state handling
    const { data, setData, post, put, processing, errors } = useForm({
        // Step 1
        title: goal?.title || '',
        category: goal?.category || 'saude',
        is_streak_enabled: goal?.is_streak_enabled || false,
        
        // Step 2 (Former Step 3)
        micro_tasks: goal?.micro_tasks?.map((t: any) => ({ title: t.title })) || [] as { title: string }[],
        new_task_title: '',
    });

    const [valErrors, setValErrors] = useState<Record<string, string>>({});

    // Auto-return to step 1 if there are backend errors for those fields
    useEffect(() => {
        if (Object.keys(errors).length > 0) {
            if (errors.title || errors.category) {
                setStep(1);
            }
        }
    }, [errors]);

    const validateStep = (currentStep: number) => {
        const errors: Record<string, string> = {};
        if (currentStep === 1) {
            if (!data.title) errors.title = 'O nome da meta é obrigatório';
        }
        // Step 2 validation is optional (micro-tasks are optional)
        setValErrors(errors);
        return Object.keys(errors).length === 0;
    };

    const nextStep = () => {
        if (validateStep(step)) {
            setStep(step + 1);
        }
    };
    const prevStep = () => setStep(step - 1);

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        
        if (step !== 2) {
            nextStep();
            return;
        }

        if (!data.is_streak_enabled && data.micro_tasks.length === 0) {
            setValErrors({ 
                ...valErrors, 
                micro_tasks: 'A meta precisa ter pelo menos uma sub-tarefa OU o sistema de ofensivas ativado.' 
            });
            return;
        }

        if (isEditing) {
            put(route('goals.update', goal.id));
        } else {
            post(route('goals.store'));
        }
    };

    const addMicroTask = () => {
        const title = data.new_task_title.trim();
        if (title) {
            const isDuplicate = data.micro_tasks.some(
                (task: any) => task.title.toLowerCase() === title.toLowerCase()
            );

            if (isDuplicate) {
                setValErrors({ ...valErrors, new_task_title: 'Esta tarefa já foi adicionada!' });
                return;
            }

            const updatedTasks = [
                ...data.micro_tasks, 
                { title: title }
            ];
            
            // Clear specific error if adding a non-duplicate
            if (valErrors.new_task_title) {
                const newErrors = { ...valErrors };
                delete newErrors.new_task_title;
                setValErrors(newErrors);
            }

            setData({
                ...data,
                micro_tasks: updatedTasks,
                new_task_title: '',
            });
        }
    };

    const removeMicroTask = (index: number) => {
        const newTasks = [...data.micro_tasks];
        newTasks.splice(index, 1);
        setData('micro_tasks', newTasks);
    };

    return (
        <AuthenticatedLayout>
            <Head title="Nova Meta" />



            <main className="max-w-[1200px] mx-auto px-4 py-12 flex-grow w-full">
                <div className="max-w-3xl mx-auto">
                    <div className="mb-10 flex flex-col items-center text-center">
                        <h1 className="text-3xl font-black text-[#111815] dark:text-white mb-2">
                            {isEditing ? 'Editar Meta' : (
                                <>
                                    {step === 1 && 'Cadastro de Nova Meta'}
                                    {step === 2 && 'Cadastro de Meta: Micro-tarefas'}
                                </>
                            )}
                        </h1>
                        <p className="text-gray-500 dark:text-gray-400">
                            {step === 1 && 'Transforme seus sonhos em passos acionáveis.'}
                            {step === 2 && 'Divida sua jornada em conquistas diárias.'}
                        </p>
                    </div>

                    {/* Stepper */}
                    <div className="relative mb-12 w-full px-4 md:px-10">
                         <div className="absolute top-5 left-0 w-full h-[2px] bg-gray-100 dark:bg-gray-800 z-0"></div>
                         
                         <div className="relative z-10 flex items-center justify-between w-full max-w-sm mx-auto">
                             {/* Step 1 Node */}
                             <div 
                                className="flex flex-col items-center gap-2 cursor-pointer group"
                                onClick={() => setStep(1)}
                             >
                                <div className={`size-10 rounded-full flex items-center justify-center font-bold border-2 transition-all group-hover:scale-110 ${step >= 1 ? 'bg-primary text-background-dark border-primary shadow-lg shadow-primary/20' : 'bg-white dark:bg-gray-800 border-gray-100 text-gray-400'}`}>
                                    {step > 1 ? <span className="material-symbols-outlined text-base">check</span> : '1'}
                                </div>
                                <span className={`text-[10px] font-bold uppercase tracking-widest ${step >= 1 ? 'text-primary' : 'text-gray-400'}`}>Objetivo</span>
                             </div>

                             {/* Step 2 Node */}
                             <div 
                                className="flex flex-col items-center gap-2 cursor-pointer group"
                                onClick={() => nextStep()}
                             >
                                 <div className={`size-10 rounded-full flex items-center justify-center font-bold border-2 transition-all group-hover:scale-110 ${step >= 2 ? 'bg-primary text-background-dark border-primary shadow-lg shadow-primary/20' : 'bg-white dark:bg-gray-800 border-gray-100 dark:border-gray-700 text-gray-400'}`}>
                                     2
                                 </div>
                                 <span className={`text-[10px] font-bold uppercase tracking-widest ${step >= 2 ? 'text-[#111815] dark:text-white' : 'text-gray-400'}`}>Passos</span>
                             </div>
                         </div>
                    </div>

                    <div className="bg-white dark:bg-gray-800 border border-[#dbe6e1] dark:border-gray-700 rounded-2xl shadow-xl p-8 md:p-10">
                        <form onSubmit={submit} className="space-y-8">
                            {/* Step 1 Content */}
                            {step === 1 && (
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div className="col-span-1 md:col-span-2 space-y-2">
                                        <label className="block text-sm font-bold text-[#111815] dark:text-white">Qual o nome da sua meta?</label>
                                        <input 
                                            className={`w-full h-12 px-4 rounded-xl border-2 dark:bg-gray-900 focus:ring-primary focus:border-primary transition-all ${ (valErrors.title || errors.title) ? 'border-red-500' : 'border-gray-100 dark:border-gray-700'}`} 
                                            placeholder="Ex: Economizar para viagem, Correr 5km..." 
                                            type="text" 
                                            maxLength={50}
                                            value={data.title}
                                            onChange={e => setData('title', e.target.value)}
                                        />
                                        {valErrors.title && <p className="text-red-500 text-xs font-bold">{valErrors.title}</p>}
                                        {errors.title && <p className="text-red-500 text-xs">{errors.title}</p>}
                                    </div>
                                    <div className="space-y-2">
                                        <label className="block text-sm font-bold text-[#111815] dark:text-white">Categoria</label>
                                        <select 
                                            className="w-full h-12 px-4 rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 focus:ring-primary focus:border-primary transition-all appearance-none"
                                            value={data.category}
                                            onChange={e => setData('category', e.target.value)}
                                        >
                                            <option value="saude">💪 Saúde</option>
                                            <option value="financeiro">💰 Financeiro</option>
                                            <option value="carreira">🚀 Carreira</option>
                                            <option value="pessoal">🧠 Pessoal</option>
                                        </select>
                                    </div>
                                    <div className="col-span-1 md:col-span-2">
                                        <div className="bg-orange-50 dark:bg-orange-900/10 p-5 rounded-2xl flex items-center justify-between border border-orange-100 dark:border-orange-800/30 w-full mt-2">
                                            <div className="flex items-center gap-4">
                                                <div className="size-12 rounded-full bg-orange-100 dark:bg-orange-900/40 flex items-center justify-center text-orange-500">
                                                    <span className="material-symbols-outlined !text-[28px]" style={{ fontVariationSettings: "'FILL' 1" }}>local_fire_department</span>
                                                </div>
                                                <div>
                                                    <label className="flex items-center gap-2 text-sm font-extrabold text-[#111815] dark:text-white cursor-pointer" htmlFor="enable-streak">
                                                        Habilitar Ofensiva para esta meta?
                                                    </label>
                                                    <p className="text-xs text-gray-600 dark:text-gray-400">Ative o sistema de streaks para manter o foco diário.</p>
                                                </div>
                                            </div>
                                            <div className="relative inline-block w-12 h-6 transition duration-200 ease-in-out">
                                                <input 
                                                    id="enable-streak" 
                                                    type="checkbox" 
                                                    className="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 border-gray-300 dark:border-gray-600 appearance-none cursor-pointer z-10 transition-all duration-200"
                                                    checked={data.is_streak_enabled}
                                                    onChange={e => setData('is_streak_enabled', e.target.checked)}
                                                />
                                                <label htmlFor="enable-streak" className="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 dark:bg-gray-700 cursor-pointer transition-colors duration-200"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            )}

                            {/* Old Step 2 Removed */}

                            {/* Step 2 (Micro-tasks) Content */}
                            {step === 2 && (
                                <div className="space-y-6">
                                    <div className="flex justify-between items-center border-b border-gray-100 dark:border-gray-700 pb-4">
                                        <div>
                                            <h3 className="text-lg font-bold text-[#111815] dark:text-white">Checklist de Passos</h3>
                                            <p className="text-sm text-gray-500">Defina os marcos para atingir seu objetivo</p>
                                        </div>
                                    </div>

                                    {/* New Task Input Zone */}
                                    <div className="space-y-2">
                                        <div className="flex flex-col md:flex-row md:items-center gap-4 p-6 bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm">
                                            <div className={`flex-1 bg-white dark:bg-gray-900 rounded-xl px-4 h-12 flex items-center border ${valErrors.new_task_title ? 'border-red-500' : 'border-gray-200 dark:border-gray-600'} focus-within:border-primary focus-within:ring-1 focus-within:ring-primary transition-all`}>
                                                <input 
                                                    className="w-full bg-transparent border-none focus:ring-0 p-0 text-sm text-[#111815] dark:text-white placeholder:text-gray-400" 
                                                    placeholder="Ex: Abrir conta poupança..." 
                                                    type="text"
                                                    value={data.new_task_title}
                                                    onChange={e => {
                                                        setData('new_task_title', e.target.value);
                                                        if (valErrors.new_task_title) {
                                                            const newErrors = { ...valErrors };
                                                            delete newErrors.new_task_title;
                                                            setValErrors(newErrors);
                                                        }
                                                    }}
                                                    onKeyDown={e => e.key === 'Enter' && (e.preventDefault(), addMicroTask())}
                                                />
                                            </div>
                                            <button 
                                                type="button"
                                                onClick={addMicroTask} 
                                                className="flex items-center justify-center gap-2 bg-primary text-background-dark h-12 px-6 rounded-xl text-sm font-bold shadow-lg shadow-primary/20 hover:scale-105 transition-all"
                                            >
                                                <span className="material-symbols-outlined text-[18px]">add</span>
                                                Add
                                            </button>
                                        </div>
                                        {valErrors.new_task_title && (
                                            <p className="text-red-500 text-xs font-bold pl-2">{valErrors.new_task_title}</p>
                                        )}
                                    </div>
                                    
                                    {data.micro_tasks.length > 0 && (
                                        <div className="space-y-4">
                                            {/* List of Tasks */}
                                            {data.micro_tasks.map((task: any, index: number) => (
                                                <div key={index} className="flex items-center gap-4 p-4 bg-background-light dark:bg-gray-900/50 rounded-2xl border border-gray-100 dark:border-gray-700 group hover:border-primary/50 transition-colors">
                                                    <div className="flex-1 space-y-1">
                                                        <label className="text-[10px] uppercase tracking-wider font-bold text-gray-400">Título da Tarefa</label>
                                                        <div className="text-sm font-bold text-[#111815] dark:text-white">{task.title}</div>
                                                    </div>
                                                    <button 
                                                        type="button" 
                                                        onClick={() => removeMicroTask(index)}
                                                        className="text-gray-300 hover:text-red-500 transition-colors opacity-0 group-hover:opacity-100"
                                                    >
                                                        <span className="material-symbols-outlined">delete</span>
                                                    </button>
                                                </div>
                                            ))}
                                        </div>
                                    )}


                                    <div className="mt-8 p-6 bg-blue-50 dark:bg-blue-900/10 rounded-2xl border border-blue-100 dark:border-blue-800/30 flex gap-4">
                                        <span className="material-symbols-outlined text-blue-500">lightbulb</span>
                                        <div>
                                            <h4 className="text-sm font-bold text-blue-900 dark:text-blue-100">Dica de Especialista</h4>
                                            <p className="text-xs text-blue-700 dark:text-blue-300 mt-1">Metas quebradas em tarefas menores de até 30 minutos são 3x mais propensas a serem concluídas sem procrastinação.</p>
                                        </div>
                                    </div>
                                </div>
                            )}

                            {/* Navigation Buttons */}
                            <div className="pt-8 flex flex-col sm:flex-row gap-4 items-center justify-between mt-8 border-t border-gray-100 dark:border-gray-700">
                                {step > 1 ? (
                                    <button 
                                        type="button"
                                        onClick={prevStep}
                                        className="order-2 sm:order-1 flex items-center justify-center gap-2 h-14 px-8 text-gray-500 font-bold hover:text-gray-800 transition-colors"
                                    >
                                        <span className="material-symbols-outlined">arrow_back</span>
                                        Voltar
                                    </button>
                                ) : (
                                    <Link 
                                        href={route('dashboard')}
                                        className="order-2 sm:order-1 flex items-center justify-center gap-2 h-14 px-8 text-gray-500 font-bold hover:text-gray-800 transition-colors"
                                    >
                                        Cancelar
                                    </Link>
                                )}
                                
                                <div className="order-1 sm:order-2 flex gap-3 w-full sm:w-auto">
                                    {step < 2 ? (
                                        <button 
                                            type="button"
                                            onClick={(e) => { e.preventDefault(); nextStep(); }}
                                            className="w-full sm:min-w-[200px] flex items-center justify-center rounded-full h-14 px-8 bg-primary text-[#111815] text-lg font-bold shadow-xl shadow-primary/30 hover:scale-[1.02] transition-all"
                                        >
                                            Próximo Passo
                                        </button>
                                    ) : (
                                        <button 
                                            type="submit"
                                            disabled={processing}
                                            className="w-full sm:min-w-[200px] flex items-center justify-center rounded-full h-14 px-8 bg-primary text-[#111815] text-lg font-bold shadow-xl shadow-primary/30 hover:scale-[1.02] transition-all"
                                        >
                                            {isEditing ? 'Salvar Alterações' : 'Finalizar e Criar Meta'}
                                        </button>
                                    )}
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </AuthenticatedLayout>
    );
}
