import AdminLayout from '@/Layouts/AdminLayout';
import { Head } from '@inertiajs/react';

interface Stats {
    total_users: number;
    total_supports: number;
    pending_tickets: number;
    active_tickets: number;
}

interface Activity {
    id: number;
    type: string;
    user_name: string;
    ip: string;
    details: any;
    created_at_time: string;
}

export default function Dashboard({ stats, recentActivity }: { stats: Stats, recentActivity: Activity[] }) {
    const cards = [
        { title: 'Total Usuários', value: stats.total_users, icon: 'person', color: 'blue' },
        { title: 'Equipe Suporte', value: stats.total_supports, icon: 'support_agent', color: 'purple' },
        { title: 'Tickets Pendentes', value: stats.pending_tickets, icon: 'pending', color: 'orange' },
        { title: 'Tickets Ativos', value: stats.active_tickets, icon: 'forum', color: 'green' },
    ];

    const getActivityConfig = (type: string) => {
        switch (type) {
            case 'login': return { icon: 'login', color: 'text-green-500', bg: 'bg-green-50', label: 'Login realizado' };
            case 'logout': return { icon: 'logout', color: 'text-gray-500', bg: 'bg-gray-50', label: 'Logout realizado' };
            case 'login_failed': return { icon: 'error', color: 'text-orange-500', bg: 'bg-orange-50', label: 'Falha de autenticação' };
            case 'lockout': return { icon: 'lock', color: 'text-red-500', bg: 'bg-red-50', label: 'Bloqueio de IP (Brute Force)' };
            case 'unauthorized_access': return { icon: 'security_update_warning', color: 'text-red-600', bg: 'bg-red-50', label: 'Acesso negado ao Admin' };
            default: return { icon: 'info', color: 'text-blue-500', bg: 'bg-blue-50', label: 'Evento de sistema' };
        }
    };

    return (
        <AdminLayout>
            <Head title="Admin Dashboard" />
            
            <div className="space-y-10">
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    {cards.map((card) => (
                        <div key={card.title} className="bg-white dark:bg-gray-800 p-8 rounded-3xl border border-[#dbe6e1] dark:border-gray-700 shadow-sm transition-all hover:shadow-md">
                            <div className="flex items-center justify-between mb-4">
                                <div className={`size-12 rounded-2xl flex items-center justify-center text-${card.color}-500 bg-${card.color}-50 dark:bg-${card.color}-900/20`}>
                                    <span className="material-symbols-outlined font-bold">{card.icon}</span>
                                </div>
                                <span className="text-[10px] font-black text-gray-400 uppercase tracking-widest">Resumo</span>
                            </div>
                            <h3 className="text-gray-500 dark:text-gray-400 text-sm font-bold">{card.title}</h3>
                            <p className="text-3xl font-black mt-1 dark:text-white uppercase tracking-tighter">{card.value}</p>
                        </div>
                    ))}
                </div>

                <div className="bg-white dark:bg-gray-800 rounded-3xl border border-[#dbe6e1] dark:border-gray-700 shadow-sm overflow-hidden">
                    <div className="p-8 border-b border-[#dbe6e1] dark:border-gray-700 flex items-center justify-between bg-gray-50/30 dark:bg-gray-900/10">
                        <h3 className="text-lg font-black dark:text-white flex items-center gap-3">
                            <span className="material-symbols-outlined text-primary">security</span>
                            Atividade Recente de Segurança
                        </h3>
                        <span className="text-[10px] font-black text-gray-400 uppercase tracking-widest">Últimos 10 eventos</span>
                    </div>

                    <div className="divide-y divide-[#dbe6e1] dark:divide-gray-700">
                        {recentActivity.length > 0 ? (
                            recentActivity.map((activity) => {
                                const config = getActivityConfig(activity.type);
                                return (
                                    <div key={activity.id} className="p-5 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors group">
                                        <div className="flex items-center gap-4">
                                            <div className={`size-10 rounded-xl flex items-center justify-center ${config.bg} ${config.color} dark:bg-gray-700`}>
                                                <span className="material-symbols-outlined text-xl">{config.icon}</span>
                                            </div>
                                            <div>
                                                <div className="flex items-center gap-2">
                                                    <p className="text-sm font-bold dark:text-white">{config.label}</p>
                                                    <span className="text-[10px] bg-gray-200 dark:bg-gray-800 px-1.5 py-0.5 rounded text-gray-500 font-bold tracking-tight">{activity.ip}</span>
                                                </div>
                                                <p className="text-xs text-gray-500">
                                                    {activity.user_name} • {activity.type === 'unauthorized_access' ? activity.details?.url : (activity.details?.credentials?.email || activity.details?.email || 'N/A')}
                                                </p>
                                            </div>
                                        </div>
                                        <div className="text-right">
                                            <p className="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{activity.created_at_time}</p>
                                        </div>
                                    </div>
                                );
                            })
                        ) : (
                            <div className="text-center py-20 text-gray-400 italic text-sm">
                                Nenhuma atividade de segurança registrada até o momento.
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </AdminLayout>
    );
}
