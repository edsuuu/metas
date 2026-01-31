import React, { FormEventHandler } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import LegalNavbar from '@/Components/LegalNavbar';

declare function route(name: string, params?: any, absolute?: boolean): string;

interface Ticket {
    id: number;
    protocol: string;
    subject: string;
    created_at_formatted: string;
    status: 'pending' | 'in_progress' | 'resolved' | 'closed';
    status_label: string;
    status_color: string;
    view_url: string;
}

interface MyTicketsProps {
    tickets?: Ticket[];
    is_verified?: boolean;
    email?: string;
}

export default function MyTickets({ tickets = [], is_verified = false, email = '' }: MyTicketsProps) {
    const { data, setData, post, processing, errors } = useForm({
        email: email,
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('support.access.request'));
    };

    return (
        <div className="bg-background-light dark:bg-background-dark text-[#111815] transition-colors duration-300 min-h-screen flex flex-col font-display">
            <Head title="Everest - Meus Chamados" />
            
            <LegalNavbar>
                 <Link className="px-5 py-2 rounded-full border-2 border-primary text-primary hover:bg-primary hover:text-[#111815] text-sm font-bold transition-all" href={route('support')}>Central de Ajuda</Link>
            </LegalNavbar>

            <main className="flex-1">
                <section className="pt-20 pb-12 px-4" style={{
                    background: 'radial-gradient(circle at top right, rgba(19, 236, 146, 0.15), transparent), radial-gradient(circle at bottom left, rgba(19, 236, 146, 0.05), transparent)'
                }}>
                    <div className="max-w-[800px] mx-auto text-center">
                        <h1 className="text-4xl md:text-5xl font-black text-[#111815] dark:text-white mb-4 tracking-tight">Acompanhe seus chamados</h1>
                        <p className="text-lg text-gray-600 dark:text-gray-400 mb-10">
                            {is_verified 
                                ? `Mostrando chamados associados a ${email}`
                                : 'Digite seu e-mail para ver o status de suas solicitações'
                            }
                        </p>
                        
                        {!is_verified && (
                            <div className="max-w-xl mx-auto">
                                <form onSubmit={submit} className="flex flex-col md:flex-row gap-4">
                                    <div className="relative flex-1 group">
                                        <span className="absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 material-symbols-outlined text-xl group-focus-within:text-primary transition-colors">mail</span>
                                        <input 
                                            className="w-full pl-14 pr-6 h-14 rounded-full bg-white dark:bg-gray-800 border-2 border-[#dbe6e1] dark:border-gray-700 focus:border-primary focus:ring-0 text-base shadow-xl shadow-primary/5 transition-all outline-none" 
                                            placeholder="seu@email.com" 
                                            required 
                                            type="email"
                                            value={data.email}
                                            onChange={(e) => setData('email', e.target.value)}
                                        />
                                    </div>
                                    <button 
                                        className="px-8 h-14 rounded-full bg-primary text-[#111815] text-sm font-bold shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-transform whitespace-nowrap disabled:opacity-50" 
                                        type="submit"
                                        disabled={processing}
                                    >
                                        {processing ? 'Enviando...' : 'Buscar Chamados'}
                                    </button>
                                </form>
                                {errors.email && <p className="text-red-500 text-sm mt-2">{errors.email}</p>}
                            </div>
                        )}
                    </div>
                </section>

                {is_verified && (
                    <section className="max-w-[1000px] mx-auto px-4 py-12 mb-20">
                        {tickets.length > 0 ? (
                            <div className="bg-white dark:bg-gray-800 rounded-3xl border border-[#dbe6e1] dark:border-gray-700 shadow-sm overflow-hidden">
                                <div className="overflow-x-auto">
                                    <table className="w-full text-left border-collapse">
                                        <thead>
                                            <tr className="bg-background-light dark:bg-gray-900/50">
                                                <th className="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Protocolo</th>
                                                <th className="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Assunto</th>
                                                <th className="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Data</th>
                                                <th className="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody className="divide-y divide-[#dbe6e1] dark:divide-gray-700">
                                            {tickets.map((ticket) => (
                                                <tr key={ticket.id} className="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors cursor-pointer" onClick={() => window.location.href = ticket.view_url}>
                                                    <td className="px-6 py-5 text-sm font-medium text-gray-900 dark:text-white">{ticket.protocol}</td>
                                                    <td className="px-6 py-5 text-sm text-gray-600 dark:text-gray-300">{ticket.subject}</td>
                                                    <td className="px-6 py-5 text-sm text-gray-500 dark:text-gray-400">{ticket.created_at_formatted}</td>
                                                    <td className="px-6 py-5 text-sm">
                                                        <span className={`inline-flex items-center px-3 py-1 rounded-full text-xs font-bold ${ticket.status_color}`}>
                                                            {ticket.status_label}
                                                        </span>
                                                    </td>
                                                </tr>
                                            ))}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        ) : (
                            <div className="flex flex-col items-center justify-center py-20 px-4 text-center">
                                <div className="size-16 bg-background-light dark:bg-gray-700 rounded-full flex items-center justify-center text-gray-400 mb-4">
                                    <span className="material-symbols-outlined text-3xl">search_off</span>
                                </div>
                                <h3 className="text-lg font-bold text-[#111815] dark:text-white mb-1">Nenhum chamado encontrado</h3>
                                <p className="text-sm text-gray-500 dark:text-gray-400">Não encontramos chamados associados a este e-mail.</p>
                            </div>
                        )}
                        
                        <p className="text-center mt-8 text-sm text-gray-500 dark:text-gray-400">
                            Precisa de um novo atendimento? 
                            <Link className="text-primary font-bold hover:underline ml-1" href={route('support')}>Abra um novo chamado</Link>
                        </p>
                    </section>
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
