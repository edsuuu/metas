import AdminLayout from '@/Layouts/AdminLayout';
import { Head, Link, useForm, router, usePage } from '@inertiajs/react';
import { FormEvent, useEffect, useRef, useState } from 'react';
import Modal from '@/Components/Modal';

declare function route(name?: string, params?: any, absolute?: boolean): any;

interface Message {
    id: number;
    message: string;
    is_admin: boolean;
    sender_name: string;
    created_at_time: string;
}

interface Ticket {
    id: number;
    protocol: string;
    subject: string;
    name: string;
    email: string;
    created_at_formatted: string;
    status: string;
    status_label: string;
    status_color: string;
}

interface Props {
    ticket: Ticket;
    messages: Message[];
}

export default function TicketShow({ ticket, messages }: Props) {
    const { flash } = usePage().props as any;
    const messagesEndRef = useRef<HTMLDivElement>(null);
    const scrollContainerRef = useRef<HTMLDivElement>(null);
    const prevMessagesLength = useRef(messages.length);

    const { data, setData, post, processing, reset, errors } = useForm({
        message: '',
    });

    const [showCloseModal, setShowCloseModal] = useState(false);

    const scrollToBottom = (behavior: ScrollBehavior = 'smooth') => {
        messagesEndRef.current?.scrollIntoView({ behavior });
    };

    // Initial scroll
    useEffect(() => {
        scrollToBottom('auto');
    }, []);

    // Scroll only when new messages arrive
    useEffect(() => {
        if (messages.length > prevMessagesLength.current) {
            scrollToBottom('smooth');
        }
        prevMessagesLength.current = messages.length;
    }, [messages]);

    useEffect(() => {
        const interval = setInterval(() => {
            router.reload({ 
                only: ['messages', 'ticket'],
                preserveScroll: true,
                preserveState: true
            } as any);
        }, 5000);

        return () => clearInterval(interval);
    }, []);

    const submitReply = (e: FormEvent) => {
        e.preventDefault();
        post(route('admin.tickets.reply', ticket.protocol), {
            preserveScroll: true,
            onSuccess: () => reset('message'),
        });
    };

    const closeTicket = () => {
        post(route('admin.tickets.close', ticket.protocol), {
            onSuccess: () => setShowCloseModal(false),
        });
    };

    return (
        <AdminLayout>
            <Head title={`Admin - Chamado ${ticket.protocol}`} />
            
            <div className="max-w-[1000px] mx-auto space-y-8">
                <div className="mb-4 flex items-center gap-4">
                    <Link className="flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-primary transition-colors" href={route('admin.tickets.index')}>
                        <span className="material-symbols-outlined text-lg">arrow_back</span>
                        Voltar para Listagem
                    </Link>
                </div>

                <div className="bg-white dark:bg-gray-800 rounded-3xl p-6 md:p-8 border border-[#dbe6e1] dark:border-gray-700 shadow-sm mb-6">
                    <div className="flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div>
                            <div className="flex items-center gap-3 mb-2">
                                <span className="text-xs font-bold uppercase tracking-widest text-primary bg-primary/10 px-3 py-1 rounded-full">Protocolo {ticket.protocol}</span>
                                <span className={`inline-flex items-center px-3 py-1 rounded-full text-xs font-bold ${ticket.status_color}`}>
                                    {ticket.status_label}
                                </span>
                            </div>
                            <h1 className="text-2xl md:text-3xl font-black text-[#111815] dark:text-white tracking-tight break-all">{ticket.subject}</h1>
                            <div className="flex flex-col mt-2">
                                <p className="text-sm font-bold text-gray-700 dark:text-gray-300">De: {ticket.name}</p>
                                <p className="text-xs text-gray-500 break-all">{ticket.email}</p>
                                <p className="text-xs text-gray-400 mt-1">Aberto em {ticket.created_at_formatted}</p>
                            </div>
                        </div>
                        <div>
                            {ticket.status !== 'resolved' && (
                                <button 
                                    onClick={() => setShowCloseModal(true)}
                                    className="w-full md:w-auto px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 font-bold rounded-full hover:bg-green-500 hover:text-white transition-all flex items-center justify-center gap-2 shadow-sm"
                                >
                                    <span className="material-symbols-outlined text-sm">check_circle</span>
                                    Finalizar Chamado
                                </button>
                            )}
                        </div>
                    </div>
                </div>
                
                {flash?.success && (
                    <div className="mb-6 p-4 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-3xl font-bold text-sm animate-fade-in flex items-center gap-2">
                        <span className="material-symbols-outlined text-lg">check_circle</span>
                        {flash.success}
                    </div>
                )}
                {flash?.warning && (
                    <div className="mb-6 p-4 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 rounded-3xl font-bold text-sm animate-fade-in flex items-center gap-2">
                        <span className="material-symbols-outlined text-lg">warning</span>
                        {flash.warning}
                    </div>
                )}
                {flash?.error && (
                    <div className="mb-6 p-4 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-3xl font-bold text-sm animate-fade-in flex items-center gap-2">
                        <span className="material-symbols-outlined text-lg">error</span>
                        {flash.error}
                    </div>
                )}

                <div 
                    ref={scrollContainerRef}
                    className="flex-1 overflow-y-auto pr-2 max-h-[600px] mb-10 custom-scrollbar scroll-smooth"
                >
                    <div className="space-y-6 pb-4">
                        {messages.map((msg) => (
                            <div key={msg.id} className={`flex flex-col ${msg.is_admin ? 'items-end' : 'items-start'}`}>
                                <div className={`flex items-center gap-2 mb-2 ${msg.is_admin ? 'mr-4' : 'ml-4'}`}>
                                    {!msg.is_admin && (
                                        <>
                                            <div className="size-6 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center text-[10px] font-bold">U</div>
                                            <span className="text-xs font-bold text-gray-700 dark:text-gray-300">{msg.sender_name}</span>
                                        </>
                                    )}
                                    <span className="text-[10px] text-gray-400">{msg.created_at_time}</span>
                                </div>
                                <div className={`max-w-[85%] p-5 rounded-3xl shadow-md break-words ${
                                    msg.is_admin 
                                        ? 'bg-primary text-[#111815] shadow-primary/10 rounded-br-sm'
                                        : 'bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 border border-[#dbe6e1] dark:border-gray-700 rounded-bl-sm' 
                                }`}>
                                    <p className="text-sm leading-relaxed whitespace-pre-wrap break-all">{msg.message}</p>
                                </div>
                            </div>
                        ))}
                        <div ref={messagesEndRef} />
                    </div>
                </div>

                {ticket.status !== 'resolved' && (
                    <div className="bg-white dark:bg-gray-800 rounded-3xl border-2 border-primary/20 dark:border-gray-700 p-4 md:p-6 shadow-xl shadow-primary/5">
                        <div className="flex items-center gap-2 mb-4">
                            <span className="material-symbols-outlined text-primary">reply</span>
                            <h3 className="font-bold text-[#111815] dark:text-white">Responder ao Usuário</h3>
                        </div>
                        <form onSubmit={submitReply}>
                            <textarea 
                                className="w-full rounded-2xl border-[#dbe6e1] dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 focus:ring-primary focus:border-primary text-sm min-h-[120px] mb-4 p-4 placeholder-gray-400 resize-none dark:text-white" 
                                placeholder="Digite sua resposta técnica aqui..."
                                value={data.message}
                                onChange={e => setData('message', e.target.value)}
                            />
                            {errors.message && <p className="text-red-500 text-xs font-bold mb-4">{errors.message}</p>}
                            <div className="flex justify-end">
                                <button 
                                    disabled={processing || !data.message}
                                    className="w-full md:w-auto px-8 py-3 bg-primary text-[#111815] font-bold rounded-full hover:scale-[1.02] active:scale-95 transition-transform shadow-lg shadow-primary/20 disabled:opacity-50"
                                >
                                    {processing ? 'Enviando...' : 'Enviar Resposta'}
                                </button>
                            </div>
                        </form>
                    </div>
                )}
            </div>
            {/* Close Ticket Modal */}
            <Modal show={showCloseModal} onClose={() => setShowCloseModal(false)}>
                <div className="p-6">
                    <h2 className="text-xl font-bold dark:text-white mb-4">Finalizar Chamado</h2>
                    <p className="text-gray-500 dark:text-gray-300 mb-6">
                        Tem certeza que deseja finalizar este chamado? O usuário será notificado.
                    </p>
                    <div className="flex justify-end gap-3">
                        <button
                            onClick={() => setShowCloseModal(false)}
                            className="px-4 py-2 text-sm font-bold text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
                        >
                            Cancelar
                        </button>
                        <button
                            onClick={closeTicket}
                            className="px-4 py-2 text-sm font-bold text-white bg-green-500 hover:bg-green-600 rounded-lg transition-colors"
                            disabled={processing}
                        >
                            Confirmar Finalização
                        </button>
                    </div>
                </div>
            </Modal>
        </AdminLayout>
    );
}
