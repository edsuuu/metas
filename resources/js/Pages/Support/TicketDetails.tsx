import React, { FormEventHandler, useEffect, useRef } from 'react';
import { Head, Link, useForm, router, usePage } from '@inertiajs/react';
import LegalNavbar from '@/Components/LegalNavbar';

declare function route(name: string, params?: any, absolute?: boolean): string;

interface Message {
    id: number;
    message: string;
    is_admin: boolean; // true if support, false if user
    sender_name: string;
    created_at_time: string;
}

interface TicketDetailsProps {
    ticket: {
        id: number;
        protocol: string;
        subject: string;
        created_at_formatted: string;
        status: string;
        status_label: string;
        status_color: string;
    };
    messages: Message[];
}

export default function TicketDetails({ ticket, messages }: TicketDetailsProps) {
    const { flash } = usePage().props as any;
    const messagesEndRef = useRef<HTMLDivElement>(null);
    const scrollContainerRef = useRef<HTMLDivElement>(null);
    const prevMessagesLength = useRef(messages.length);

    const { data, setData, post, processing, reset, errors } = useForm({
        message: '',
    });

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

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('support.ticket.reply', ticket.protocol), {
            preserveScroll: true,
            onSuccess: () => reset(),
        });
    };

    return (
        <div className="bg-background-light dark:bg-background-dark text-[#111815] transition-colors duration-300 min-h-screen flex flex-col font-display">
            <Head title={`Everest - Chamado ${ticket.protocol}`} />
            
            <LegalNavbar>
                <Link className="text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-primary transition-colors mr-6" href={route('support.my-tickets')}>Meus Chamados</Link>
                <Link className="px-5 py-2 rounded-full border-2 border-primary text-primary hover:bg-primary hover:text-[#111815] text-sm font-bold transition-all" href={route('support')}>Central de Ajuda</Link>
            </LegalNavbar>

            <main className="flex-1 w-full max-w-[1000px] mx-auto px-4 py-8 md:py-12">
                <div className="mb-8 flex items-center gap-4">
                    <Link className="flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-primary transition-colors" href={route('support.my-tickets')}>
                        <span className="material-symbols-outlined text-lg">arrow_back</span>
                        Voltar para Meus Chamados
                    </Link>
                </div>

                <div className="bg-white dark:bg-gray-800 rounded-3xl p-6 md:p-8 border border-[#dbe6e1] dark:border-gray-700 shadow-sm mb-6">
                    <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <div className="flex items-center gap-3 mb-2">
                                <span className="text-xs font-bold uppercase tracking-widest text-primary bg-primary/10 px-3 py-1 rounded-full">Protocolo {ticket.protocol}</span>
                                <span className={`inline-flex items-center px-3 py-1 rounded-full text-xs font-bold ${ticket.status_color}`}>
                                    {ticket.status_label}
                                </span>
                            </div>
                            <h1 className="text-2xl md:text-3xl font-black text-[#111815] dark:text-white tracking-tight">{ticket.subject}</h1>
                            <p className="text-sm text-gray-500 dark:text-gray-400 mt-1">Criado em {ticket.created_at_formatted}</p>
                        </div>
                    </div>
                </div>
                
                {flash?.success && (
                    <div className="mb-6 p-4 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-2xl font-bold text-sm animate-fade-in flex items-center gap-2">
                        <span className="material-symbols-outlined text-lg">check_circle</span>
                        {flash.success}
                    </div>
                )}
                {flash?.error && (
                    <div className="mb-6 p-4 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-2xl font-bold text-sm animate-fade-in flex items-center gap-2">
                        <span className="material-symbols-outlined text-lg">error</span>
                        {flash.error}
                    </div>
                )}
                {flash?.warning && (
                    <div className="mb-6 p-4 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 rounded-2xl font-bold text-sm animate-fade-in flex items-center gap-2">
                        <span className="material-symbols-outlined text-lg">warning</span>
                        {flash.warning}
                    </div>
                )}

                <div 
                    ref={scrollContainerRef}
                    className="flex-1 overflow-y-auto pr-2 max-h-[600px] mb-10 custom-scrollbar scroll-smooth"
                >
                    <div className="space-y-6 pb-4">
                        {messages.map((msg) => (
                            <div key={msg.id} className={`flex flex-col ${msg.is_admin ? 'items-start' : 'items-end'}`}>
                                <div className={`flex items-center gap-2 mb-2 ${msg.is_admin ? 'ml-4' : 'mr-4'}`}>
                                    {msg.is_admin ? (
                                        <>
                                            <div className="size-6 bg-primary rounded-full flex items-center justify-center text-[10px] font-bold">E</div>
                                            <span className="text-xs font-bold text-gray-700 dark:text-gray-300">{msg.sender_name}</span>
                                        </>
                                    ) : (
                                        <span className="text-xs font-bold text-gray-500">Você</span>
                                    )}
                                    <span className="text-[10px] text-gray-400">{msg.created_at_time}</span>
                                </div>
                                <div className={`max-w-[85%] p-5 rounded-3xl shadow-md break-words ${
                                    msg.is_admin 
                                        ? 'bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 border border-[#dbe6e1] dark:border-gray-700 rounded-bl-sm' 
                                        : 'bg-primary text-[#111815] shadow-primary/10 rounded-br-sm'
                                }`}>
                                    <p className="text-sm leading-relaxed whitespace-pre-wrap break-all">{msg.message}</p>
                                </div>
                            </div>
                        ))}
                        <div ref={messagesEndRef} />
                    </div>
                </div>

                {ticket.status !== 'resolved' && ticket.status !== 'closed' ? (
                    <div className="bg-white dark:bg-gray-800 rounded-3xl border-2 border-primary/20 dark:border-gray-700 p-4 md:p-6 shadow-xl shadow-primary/5">
                        <div className="flex items-center gap-2 mb-4">
                            <span className="material-symbols-outlined text-primary">reply</span>
                            <h3 className="font-bold text-[#111815] dark:text-white">Enviar uma réplica</h3>
                        </div>
                        <form onSubmit={submit}>
                            <textarea 
                                className="w-full rounded-2xl border-[#dbe6e1] dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 focus:ring-primary focus:border-primary text-sm min-h-[120px] mb-4 p-4 placeholder-gray-400 resize-none dark:text-white" 
                                placeholder="Digite sua mensagem aqui..."
                                value={data.message}
                                onChange={(e) => setData('message', e.target.value)}
                            />
                            {errors.message && <p className="text-red-500 text-xs font-bold mb-4">{errors.message}</p>}
                            <div className="flex justify-end">
                                <button 
                                    className="w-full md:w-auto px-8 py-3 bg-primary text-[#111815] font-bold rounded-full hover:scale-[1.02] active:scale-95 transition-transform shadow-lg shadow-primary/20 disabled:opacity-50"
                                    disabled={processing}
                                >
                                    {processing ? 'Enviando...' : 'Enviar Réplica'}
                                </button>
                            </div>
                        </form>
                    </div>
                ) : (
                    <div className="bg-gray-50 dark:bg-gray-900/50 rounded-3xl border border-[#dbe6e1] dark:border-gray-700 p-6 shadow-sm text-center">
                        <span className="material-symbols-outlined text-gray-400 text-4xl mb-3">lock</span>
                        <h3 className="font-bold text-gray-700 dark:text-white mb-2">Este chamado está finalizado</h3>
                        <p className="text-sm text-gray-500">Não é mais possível enviar mensagens para este chamado poist ele já foi resolvido.</p>
                    </div>
                )}
            </main>

            <footer className="w-full py-12 px-4 bg-background-light dark:bg-background-dark border-t border-[#dbe6e1] dark:border-gray-800">
                <div className="max-w-[1200px] mx-auto text-center">
                    <div className="flex items-center justify-center gap-2 mb-6">
                        <div className="size-6 text-primary">
                            <svg fill="currentColor" viewBox="0 0 48 48">
                                <path d="M24 4H42V17.3333V30.6667H24V44H6V30.6667V17.3333H24V44Z"></path>
                            </svg>
                        </div>
                        <span className="font-bold text-[#111815] dark:text-white">Everest</span>
                    </div>
                    <p className="text-xs text-gray-400 mb-4">© 2024 Everest Technologies Inc. Todos os direitos reservados.</p>
                    <div className="flex justify-center gap-6">
                        <Link className="text-xs text-gray-500 hover:text-primary underline" href="/terms">Privacidade</Link>
                        <Link className="text-xs text-gray-500 hover:text-primary underline" href="/terms">Termos</Link>
                        <Link className="text-xs text-gray-500 hover:text-primary underline" href="/support">Status do Sistema</Link>
                    </div>
                </div>
            </footer>
        </div>
    );
}
