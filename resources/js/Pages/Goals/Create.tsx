import React, { useState } from 'react';
import { Head, useForm, Link } from '@inertiajs/react';
import { PageProps } from '@/types';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

export default function CreateGoal({ auth }: PageProps) {
    const [step, setStep] = useState(1);
    
    // Form state handling
    const { data, setData, post, processing, errors } = useForm({
        // Step 1
        title: '',
        category: 'saude',
        frequency: 'unique', // 'recurrent' or 'unique'
        
        // Step 2
        deadline: '',
        target_value: '',
        is_streak_enabled: true,
        reminder_frequency: 'daily', // 'daily' or 'weekly'
        reminder_time: '09:00',

        // Step 3
        micro_tasks: [
            { title: 'Definir destino da viagem', deadline: '2024-05-20' },
            { title: 'Pesquisar passagens aéreas', deadline: '2024-06-15' },
        ],
        new_task_title: '',
        new_task_deadline: ''
    });

    const nextStep = () => setStep(step + 1);
    const prevStep = () => setStep(step - 1);

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        post(route('goals.store'));
    };

    const addMicroTask = () => {
        if (data.new_task_title) {
            setData('micro_tasks', [
                ...data.micro_tasks, 
                { title: data.new_task_title, deadline: data.new_task_deadline }
            ]);
            setData('new_task_title', ''); // Reset input
            setData('new_task_deadline', '');
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
                            {step === 1 && 'Cadastro de Nova Meta'}
                            {step === 2 && 'Cadastro de Meta: Detalhes'}
                            {step === 3 && 'Cadastro de Meta: Micro-tarefas'}
                        </h1>
                        <p className="text-gray-500 dark:text-gray-400">
                            {step === 1 && 'Transforme seus sonhos em passos acionáveis.'}
                            {step === 2 && 'Defina os prazos, valores e lembretes para alcançar seu topo.'}
                            {step === 3 && 'Divida sua jornada em conquistas diárias.'}
                        </p>
                    </div>

                    {/* Stepper */}
                    <div className="flex items-center justify-between mb-12 relative w-full">
                         <div className="absolute top-1/2 left-0 w-full h-0.5 bg-gray-200 dark:bg-gray-800 -translate-y-1/2 z-0"></div>
                         
                         {/* Step 1 Node */}
                         <div className="relative z-10 flex flex-col items-center gap-2">
                            <div className={`size-10 rounded-full flex items-center justify-center font-bold border-2 transition-colors ${step >= 1 ? 'bg-primary text-background-dark border-primary' : 'bg-white dark:bg-gray-800 border-gray-200 text-gray-400'}`}>
                                {step > 1 ? <span className="material-symbols-outlined text-sm">check</span> : '1'}
                            </div>
                            <span className={`text-xs font-bold ${step >= 1 ? 'text-primary' : 'text-gray-400'}`}>Definição</span>
                         </div>

                         {/* Step 2 Node */}
                         <div className="relative z-10 flex flex-col items-center gap-2">
                            <div className={`size-10 rounded-full flex items-center justify-center font-bold border-2 transition-colors ${step >= 2 ? 'bg-primary text-background-dark border-primary' : 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-400'} ${step === 2 ? 'border-primary text-primary' : ''}`}>
                                {step > 2 ? <span className="material-symbols-outlined text-sm">check</span> : '2'}
                            </div>
                            <span className={`text-xs font-bold ${step >= 2 ? 'text-[#111815] dark:text-white' : 'text-gray-400'}`}>Detalhes</span>
                         </div>

                         {/* Step 3 Node */}
                         <div className="relative z-10 flex flex-col items-center gap-2">
                             <div className={`size-10 rounded-full flex items-center justify-center font-bold border-2 transition-colors ${step >= 3 ? 'bg-primary text-background-dark border-primary' : 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-400'} ${step === 3 ? 'border-primary text-primary' : ''}`}>
                                 3
                             </div>
                             <span className={`text-xs font-bold ${step >= 3 ? 'text-[#111815] dark:text-white' : 'text-gray-400'}`}>Micro-tarefas</span>
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
                                            className="w-full h-12 px-4 rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 focus:ring-primary focus:border-primary transition-all" 
                                            placeholder="Ex: Economizar para viagem, Correr 5km..." 
                                            type="text" 
                                            value={data.title}
                                            onChange={e => setData('title', e.target.value)}
                                        />
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
                                    <div className="space-y-2">
                                        <label className="block text-sm font-bold text-[#111815] dark:text-white">Frequência</label>
                                        <div className="flex gap-2">
                                            <button 
                                                type="button"
                                                onClick={() => setData('frequency', 'recurrent')}
                                                className={`flex-1 h-12 px-4 rounded-xl border-2 font-bold text-sm flex items-center justify-center gap-2 transition-colors ${data.frequency === 'recurrent' ? 'border-primary bg-primary/10 text-primary' : 'border-gray-100 dark:border-gray-700 text-gray-500 hover:border-gray-200'}`}
                                            >
                                                <span className="material-symbols-outlined text-[18px]">sync</span>
                                                Recorrente
                                            </button>
                                            <button 
                                                type="button"
                                                onClick={() => setData('frequency', 'unique')}
                                                className={`flex-1 h-12 px-4 rounded-xl border-2 font-bold text-sm flex items-center justify-center gap-2 transition-colors ${data.frequency === 'unique' ? 'border-primary bg-primary/10 text-primary' : 'border-gray-100 dark:border-gray-700 text-gray-500 hover:border-gray-200'}`}
                                            >
                                                <span className="material-symbols-outlined text-[18px]">event</span>
                                                Única
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            )}

                            {/* Step 2 Content */}
                            {step === 2 && (
                                <>
                                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div className="space-y-2">
                                            <label className="block text-sm font-bold text-[#111815] dark:text-white">Data Limite (Deadline)</label>
                                            <div className="relative">
                                                <input 
                                                    className="w-full h-12 px-4 rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 focus:ring-primary focus:border-primary transition-all" 
                                                    type="date" 
                                                    value={data.deadline}
                                                    onChange={e => setData('deadline', e.target.value)}
                                                />
                                            </div>
                                            <p className="text-[10px] text-gray-400 italic">Quando você pretende finalizar esta meta?</p>
                                        </div>
                                        <div className="space-y-2">
                                            <label className="block text-sm font-bold text-[#111815] dark:text-white">Valor Alvo</label>
                                            <div className="relative">
                                                <span className="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold">R$</span>
                                                <input 
                                                    className="w-full h-12 pl-12 pr-4 rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 focus:ring-primary focus:border-primary transition-all" 
                                                    placeholder="0,00" 
                                                    type="number" 
                                                    step="0.01"
                                                    value={data.target_value}
                                                    onChange={e => setData('target_value', e.target.value)}
                                                />
                                            </div>
                                            <p className="text-[10px] text-gray-400 italic">Preencha apenas se houver um objetivo financeiro.</p>
                                        </div>
                                    </div>

                                    <div className="pt-6 border-t border-gray-100 dark:border-gray-700 space-y-6">
                                        <div>
                                            <label className="block text-sm font-bold text-[#111815] dark:text-white mb-4">Configurações de Lembrete</label>
                                            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                 <div 
                                                    onClick={() => setData('reminder_frequency', 'daily')}
                                                    className={`p-4 rounded-xl border-2 flex items-start gap-3 cursor-pointer transition-colors ${data.reminder_frequency === 'daily' ? 'border-primary bg-primary/5' : 'border-gray-100 dark:border-gray-700 hover:border-gray-200'}`}
                                                >
                                                    <div className={`size-5 rounded-full border-4 mt-0.5 ${data.reminder_frequency === 'daily' ? 'border-primary bg-white' : 'border-gray-300 dark:border-gray-600'}`}></div>
                                                    <div>
                                                        <p className="text-sm font-bold text-[#111815] dark:text-white">Lembretes Diários</p>
                                                        <p className="text-xs text-gray-500">Notificações todos os dias no horário escolhido.</p>
                                                    </div>
                                                </div>
                                                <div 
                                                    onClick={() => setData('reminder_frequency', 'weekly')}
                                                    className={`p-4 rounded-xl border-2 flex items-start gap-3 cursor-pointer transition-colors ${data.reminder_frequency === 'weekly' ? 'border-primary bg-primary/5' : 'border-gray-100 dark:border-gray-700 hover:border-gray-200'}`}
                                                >
                                                    <div className={`size-5 rounded-full border-2 mt-0.5 ${data.reminder_frequency === 'weekly' ? 'border-primary bg-white border-4' : 'border-gray-300 dark:border-gray-600'}`}></div>
                                                    <div>
                                                        <p className="text-sm font-bold text-[#111815] dark:text-white">Lembretes Semanais</p>
                                                        <p className="text-xs text-gray-500">Uma notificação por semana para check-in.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div className="space-y-2 max-w-xs">
                                            <label className="block text-sm font-bold text-[#111815] dark:text-white">Horário Preferencial</label>
                                            <input 
                                                className="w-full h-12 px-4 rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 focus:ring-primary focus:border-primary transition-all" 
                                                type="time" 
                                                value={data.reminder_time}
                                                onChange={e => setData('reminder_time', e.target.value)}
                                            />
                                        </div>
                                    </div>

                                    <div className="pt-6 border-t border-gray-100 dark:border-gray-700">
                                        <div className="bg-orange-50 dark:bg-orange-900/10 p-5 rounded-2xl flex items-center justify-between border border-orange-100 dark:border-orange-800/30">
                                            <div className="flex items-center gap-4">
                                                <div className="size-12 rounded-full bg-orange-100 dark:bg-orange-900/40 flex items-center justify-center text-orange-500">
                                                    <span className="material-symbols-outlined !text-[28px]" style={{ fontVariationSettings: "'FILL' 1" }}>local_fire_department</span>
                                                </div>
                                                <div>
                                                    <label className="flex items-center gap-2 text-sm font-extrabold text-[#111815] dark:text-white cursor-pointer" htmlFor="enable-streak">
                                                        Habilitar Ofensiva para esta meta?
                                                    </label>
                                                    <p className="text-xs text-gray-600 dark:text-gray-400">Mantenha a constância para não perder sua sequência de dias.</p>
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
                                </>
                            )}

                            {/* Step 3 Content */}
                            {step === 3 && (
                                <div className="space-y-6">
                                    <div className="flex justify-between items-center border-b border-gray-100 dark:border-gray-700 pb-4">
                                        <div>
                                            <h3 className="text-lg font-bold text-[#111815] dark:text-white">Checklist de Passos</h3>
                                            <p className="text-sm text-gray-500">Defina os marcos para atingir seu objetivo</p>
                                        </div>
                                        <button 
                                            type="button"
                                            onClick={addMicroTask} 
                                            className="flex items-center gap-2 bg-primary/10 text-primary px-4 py-2 rounded-full text-sm font-bold hover:bg-primary/20 transition-all"
                                        >
                                            <span className="material-symbols-outlined text-[18px]">add</span>
                                            Adicionar Tarefa
                                        </button>
                                    </div>
                                    
                                    <div className="space-y-4">
                                        {/* New Task Input Zone */}
                                        <div className="flex flex-col md:flex-row md:items-center gap-4 p-4 bg-white dark:bg-gray-800 rounded-2xl border border-dashed border-gray-300 dark:border-gray-600 opacity-80 hover:opacity-100 transition-opacity">
                                            <div className="flex-1">
                                                <input 
                                                    className="w-full bg-transparent border-none focus:ring-0 p-0 text-sm italic text-gray-400 placeholder:text-gray-400" 
                                                    placeholder="Digite uma nova tarefa..." 
                                                    type="text"
                                                    value={data.new_task_title}
                                                    onChange={e => setData('new_task_title', e.target.value)}
                                                />
                                            </div>
                                            <div className="w-full md:w-40">
                                                <input 
                                                    className="w-full bg-transparent border-none focus:ring-0 p-0 text-xs text-gray-400" 
                                                    type="date"
                                                    value={data.new_task_deadline}
                                                    onChange={e => setData('new_task_deadline', e.target.value)}
                                                />
                                            </div>
                                        </div>

                                        {/* List of Tasks */}
                                        {data.micro_tasks.map((task: any, index: number) => (
                                            <div key={index} className="flex flex-col md:flex-row md:items-center gap-4 p-4 bg-background-light dark:bg-gray-900/50 rounded-2xl border border-gray-100 dark:border-gray-700 group hover:border-primary/50 transition-colors">
                                                <div className="flex-1 space-y-2">
                                                    <label className="text-[10px] uppercase tracking-wider font-bold text-gray-400">Título da Tarefa</label>
                                                    <div className="text-sm font-bold text-[#111815] dark:text-white">{task.title}</div>
                                                </div>
                                                <div className="w-full md:w-40 space-y-2">
                                                    <label className="text-[10px] uppercase tracking-wider font-bold text-gray-400">Prazo</label>
                                                    <div className="text-xs font-medium text-gray-500 dark:text-gray-400">{task.deadline || 'Sem prazo'}</div>
                                                </div>
                                                <button 
                                                    type="button" 
                                                    onClick={() => removeMicroTask(index)}
                                                    className="self-end md:self-center text-gray-300 hover:text-red-500 transition-colors opacity-0 group-hover:opacity-100"
                                                >
                                                    <span className="material-symbols-outlined">delete</span>
                                                </button>
                                            </div>
                                        ))}
                                    </div>

                                    <div className="pt-6 border-t border-gray-100 dark:border-gray-700">
                                        <div className="bg-orange-50 dark:bg-orange-900/10 p-5 rounded-2xl flex items-center justify-between border border-orange-100 dark:border-orange-800/30">
                                            <div className="flex items-center gap-4">
                                                <div className="size-12 rounded-full bg-orange-500 flex items-center justify-center text-white shadow-lg shadow-orange-500/30">
                                                    <span className="material-symbols-outlined !text-[28px]" style={{ fontVariationSettings: "'FILL' 1" }}>local_fire_department</span>
                                                </div>
                                                <div>
                                                    <div className="flex items-center gap-2">
                                                        <label className="block text-base font-bold text-[#111815] dark:text-white" htmlFor="enable-streak-3">
                                                            Habilitar Ofensiva para esta meta?
                                                        </label>
                                                        <span className="material-symbols-outlined text-orange-500 !text-xl" style={{ fontVariationSettings: "'FILL' 1" }}>local_fire_department</span>
                                                    </div>
                                                    <p className="text-sm text-gray-600 dark:text-gray-400">Ative o sistema de streaks para manter o foco diário.</p>
                                                </div>
                                            </div>
                                            <div className="relative inline-block w-14 h-7 transition duration-200 ease-in-out">
                                                 <input 
                                                    id="enable-streak-3" 
                                                    type="checkbox" 
                                                    className="toggle-checkbox absolute block w-7 h-7 rounded-full bg-white border-4 border-gray-300 dark:border-gray-600 appearance-none cursor-pointer z-10 transition-all duration-200"
                                                    checked={data.is_streak_enabled}
                                                    onChange={e => setData('is_streak_enabled', e.target.checked)}
                                                />
                                                <label htmlFor="enable-streak-3" className="toggle-label block overflow-hidden h-7 rounded-full bg-gray-300 dark:bg-gray-700 cursor-pointer transition-colors duration-200"></label>
                                            </div>
                                        </div>
                                    </div>

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
                            <div className="pt-8 flex flex-col sm:flex-row gap-4 items-center justify-between border-t border-gray-100 dark:border-gray-700 mt-8">
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
                                    {step < 3 ? (
                                        <button 
                                            type="button"
                                            onClick={nextStep}
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
                                            Finalizar e Criar Meta
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
