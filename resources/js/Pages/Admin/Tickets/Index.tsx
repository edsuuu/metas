import AdminLayout from '@/Layouts/AdminLayout';
import { Head, Link, router } from '@inertiajs/react';

declare function route(name?: string, params?: any, absolute?: boolean): any;

interface Ticket {
    id: number;
    protocol: string;
    name: string;
    email: string;
    subject: string;
    status: string;
    status_label: string;
    status_color: string;
    created_at_formatted: string;
}

interface Props {
    tickets: {
        data: Ticket[];
        links: any[];
    };
    filters: {
        search?: string;
    };
}

export default function TicketsIndex({ tickets, filters }: Props) {
    const handleSearch = (search?: string) => {
        router.get(route('admin.tickets.index'), 
            search ? { search } : {}, 
            { preserveState: true, preserveScroll: true }
        );
    };

    return (
        <AdminLayout>
            <Head title="Gerenciar Chamados" />
            
            <div className="space-y-6">
                <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h3 className="text-2xl font-black dark:text-white tracking-tight">Central de Chamados</h3>
                        <p className="text-sm text-gray-500 dark:text-gray-400 mt-1">Atenda as solicitações de suporte e dúvidas dos usuários.</p>
                    </div>

                    <div className="flex w-full md:w-auto gap-3">
                        <div className="relative w-full md:w-80 group">
                            <span className="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 material-symbols-outlined text-xl group-focus-within:text-primary transition-colors">search</span>
                            <input 
                                id="admin-protocol-search"
                                className="w-full pl-12 pr-6 h-11 rounded-2xl bg-white dark:bg-gray-800 border border-[#dbe6e1] dark:border-gray-700 focus:border-primary focus:ring-0 text-xs shadow-sm transition-all outline-none dark:text-white" 
                                placeholder="Buscar protocolo, nome ou e-mail..." 
                                type="text"
                                defaultValue={filters?.search || ''}
                                onKeyDown={(e) => {
                                    if (e.key === 'Enter') {
                                        handleSearch(e.currentTarget.value);
                                    }
                                }}
                            />
                        </div>
                        <button 
                            onClick={() => {
                                const search = (document.getElementById('admin-protocol-search') as HTMLInputElement)?.value;
                                handleSearch(search);
                            }}
                            className="px-5 h-11 rounded-2xl bg-primary text-[#111815] font-bold text-xs hover:scale-[1.02] active:scale-95 transition-all shadow-lg shadow-primary/10"
                        >
                            Filtrar
                        </button>
                        {filters?.search && (
                            <button 
                                onClick={() => {
                                    const input = document.getElementById('admin-protocol-search') as HTMLInputElement;
                                    if (input) input.value = '';
                                    handleSearch();
                                }}
                                className="px-5 h-11 rounded-2xl bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 font-bold text-xs hover:bg-red-500 hover:text-white transition-all whitespace-nowrap"
                            >
                                Limpar
                            </button>
                        )}
                    </div>
                </div>

                <div className="bg-white dark:bg-gray-800 rounded-3xl border border-[#dbe6e1] dark:border-gray-700 shadow-sm overflow-hidden">
                    <div className="overflow-x-auto">
                        <table className="w-full text-left border-collapse">
                            <thead>
                                <tr className="bg-gray-50/50 dark:bg-gray-900/50">
                                    <th className="px-8 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Protocolo</th>
                                    <th className="px-8 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Assunto</th>
                                    <th className="px-8 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Usuário</th>
                                    <th className="px-8 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Data</th>
                                    <th className="px-8 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Status</th>
                                    <th className="px-8 py-4 text-right"></th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-[#dbe6e1] dark:divide-gray-700">
                                {tickets.data.map((ticket) => (
                                    <tr 
                                        key={ticket.id} 
                                        className="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors group"
                                    >
                                        <td className="px-8 py-5 text-sm font-bold text-gray-900 dark:text-white">
                                            {ticket.protocol}
                                        </td>
                                        <td className="px-8 py-5">
                                            <p className="text-sm font-bold dark:text-gray-200">{ticket.subject}</p>
                                        </td>
                                        <td className="px-8 py-5">
                                            <div className="text-sm font-bold text-gray-900 dark:text-white">{ticket.name}</div>
                                            <div className="text-xs text-gray-400 break-all">{ticket.email}</div>
                                        </td>
                                        <td className="px-8 py-5 text-sm text-gray-500 dark:text-gray-400">
                                            {ticket.created_at_formatted}
                                        </td>
                                        <td className="px-8 py-5">
                                            <span className={`inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider ${ticket.status_color}`}>
                                                {ticket.status_label}
                                            </span>
                                        </td>
                                        <td className="px-8 py-5 text-right">
                                            <Link 
                                                href={route('admin.tickets.show', ticket.protocol)}
                                                className="inline-flex items-center justify-center size-9 rounded-xl bg-primary text-gray-900 shadow-sm hover:scale-110 transition-all font-bold"
                                                title="Ver detalhes"
                                            >
                                                <span className="material-symbols-outlined text-sm">visibility</span>
                                            </Link>
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>

                    {tickets.data.length === 0 && (
                        <div className="flex flex-col items-center justify-center py-20 px-4 text-center">
                            <div className="size-16 bg-gray-50 dark:bg-gray-700 rounded-full flex items-center justify-center text-gray-400 mb-4">
                                <span className="material-symbols-outlined text-3xl">mail_outline</span>
                            </div>
                            <h3 className="text-lg font-bold text-[#111815] dark:text-white mb-1">Nenhum chamado</h3>
                            <p className="text-sm text-gray-500 dark:text-gray-400">Não há chamados registrados no momento.</p>
                        </div>
                    )}
                </div>
            </div>
        </AdminLayout>
    );
}
