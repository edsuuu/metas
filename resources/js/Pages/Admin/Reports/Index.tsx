import AdminLayout from '@/Layouts/AdminLayout';
import { Head, router } from '@inertiajs/react';
import React from 'react';

interface Report {
    id: number;
    user: {
        id: number;
        name: string;
    };
    post: {
        id: number;
        content: string;
        user: {
            name: string;
        };
        files: Array<{
            uuid: string;
        }>;
    };
    reason: string;
    details: string | null;
    status: string;
    created_at: string;
}

export default function ReportsIndex({ reports }: { reports: Report[] }) {
    const handleResolve = (reportId: number, status: 'resolved' | 'dismissed', deletePost: boolean) => {
        if (confirm(`Tem certeza que deseja marcar esta denúncia como ${status === 'resolved' ? 'resolvida (post punido)' : 'rejeitada (post inocente)'}?`)) {
            router.post(route('admin.reports.resolve', reportId), {
                status,
                delete_post: deletePost
            });
        }
    };

    const getReasonLabel = (reason: string) => {
        const reasons: Record<string, string> = {
            'spam': 'Spam / Propaganda',
            'harassment': 'Assédio / Ofensa',
            'inappropriate': 'Inadequado',
            'hate_speech': 'Discurso de Ódio',
            'other': 'Outro'
        };
        return reasons[reason] || reason;
    };

    return (
        <AdminLayout>
            <Head title="Denúncias de Posts" />

            <div className="space-y-6">
                <div className="flex items-center justify-between">
                    <h2 className="text-2xl font-black dark:text-white uppercase tracking-tighter">Denúncias Pendentes</h2>
                    <span className="bg-primary text-black text-xs font-black px-3 py-1 rounded-full">{reports.length} pendentes</span>
                </div>

                <div className="bg-white dark:bg-gray-800 rounded-3xl border border-[#dbe6e1] dark:border-gray-700 shadow-sm overflow-hidden">
                    <div className="divide-y divide-[#dbe6e1] dark:divide-gray-700">
                        {reports.length > 0 ? reports.map((report) => (
                            <div key={report.id} className="p-6">
                                <div className="grid grid-cols-1 lg:grid-cols-12 gap-6">
                                    <div className="lg:col-span-4">
                                        <div className="flex items-center gap-2 mb-2">
                                            <span className="bg-orange-100 text-orange-600 text-[10px] font-black px-2 py-0.5 rounded-full uppercase">
                                                {getReasonLabel(report.reason)}
                                            </span>
                                            <span className="text-[10px] text-gray-400 font-bold">{new Date(report.created_at).toLocaleString('pt-BR')}</span>
                                        </div>
                                        <p className="text-sm dark:text-white font-bold mb-1">Denunciado por: <span className="font-normal text-gray-500">{report.user.name}</span></p>
                                        <p className="text-xs text-gray-500 italic">"{report.details || 'Sem detalhes adicionais'}"</p>
                                    </div>

                                    <div className="lg:col-span-5 bg-gray-50 dark:bg-gray-900/50 p-4 rounded-2xl border border-dashed border-gray-200 dark:border-gray-700">
                                        <p className="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Conteúdo do Post (de {report.post.user.name})</p>
                                        <p className="text-sm dark:text-white mb-3 line-clamp-3">{report.post.content}</p>
                                        {report.post.files && report.post.files.length > 0 && (
                                            <div className="size-20 rounded-lg overflow-hidden border border-gray-200">
                                                <img src={route('files.show', report.post.files[0].uuid)} className="w-full h-full object-cover" alt="Post file" />
                                            </div>
                                        )}
                                    </div>

                                    <div className="lg:col-span-3 flex flex-col justify-center gap-2">
                                        <button 
                                            onClick={() => handleResolve(report.id, 'resolved', true)}
                                            className="w-full bg-red-600 hover:bg-red-700 text-white text-xs font-black py-3 rounded-xl transition-all shadow-lg shadow-red-500/20 flex items-center justify-center gap-2"
                                        >
                                            <span className="material-symbols-outlined text-sm">delete</span>
                                            Apagar Post e Resolver
                                        </button>
                                        <button 
                                            onClick={() => handleResolve(report.id, 'dismissed', false)}
                                            className="w-full bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-white text-xs font-black py-3 rounded-xl transition-all flex items-center justify-center gap-2"
                                        >
                                            <span className="material-symbols-outlined text-sm">close</span>
                                            Ignorar Denúncia
                                        </button>
                                    </div>
                                </div>
                            </div>
                        )) : (
                            <div className="py-20 text-center">
                                <span className="material-symbols-outlined text-gray-200 text-6xl mb-4">check_circle</span>
                                <p className="text-gray-400 italic">Nenhuma denúncia pendente. Tudo limpo!</p>
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </AdminLayout>
    );
}

declare function route(name: string, params?: any): string;
