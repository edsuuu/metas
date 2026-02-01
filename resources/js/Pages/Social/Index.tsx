import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, useForm, router } from '@inertiajs/react';
import React, { useState } from 'react';

interface User {
    id: number;
    name: string;
    nickname: string | null;
    avatar_url: string | null;
    current_xp?: number;
}

interface Friendship {
    id: number;
    user: User;
    friend: User;
    status: string;
}

interface SocialIndexProps {
    auth: any;
    users: User[];
    pendingReceived: Friendship[];
    pendingSent: Friendship[];
    search: string;
    tab: 'discovery' | 'sent' | 'received';
    hasReceivedBonus: boolean;
}

export default function SocialIndex({ auth, users, pendingReceived, pendingSent, search: initialSearch, tab: initialTab, hasReceivedBonus }: SocialIndexProps) {
    const [search, setSearch] = useState(initialSearch || '');
    const [tab, setTab] = useState<'discovery' | 'sent' | 'received'>(initialTab || 'discovery');

    // Sync tab state with prop to ensure back/forward navigation or server redirects update the view
    React.useEffect(() => {
        if (initialTab) {
            setTab(initialTab);
        }
    }, [initialTab]);

    // Polling para atualizar listas de pedidos
    React.useEffect(() => {
        const interval = setInterval(() => {
            router.reload({
                only: ['pendingReceived', 'pendingSent', 'hasReceivedBonus'],
            });
        }, 5000);

        return () => clearInterval(interval);
    }, []);

    const handleTabChange = (newTab: 'discovery' | 'sent' | 'received') => {
        setTab(newTab);
        router.get(route('social.index'), { 
            search, 
            tab: newTab 
        }, { 
            preserveState: true, 
            preserveScroll: true,
            replace: true 
        });
    };

    const handleSearch = (e: React.FormEvent) => {
        e.preventDefault();
        router.get(route('social.index'), { search }, { preserveState: true });
    };

    const sendRequest = (userId: number) => {
        router.post(route('social.request.send', userId), {}, { preserveScroll: true });
    };

    const acceptRequest = (friendshipId: number) => {
        router.post(route('social.request.accept', friendshipId), {}, { preserveScroll: true });
    };

    const declineRequest = (friendshipId: number) => {
        router.post(route('social.request.decline', friendshipId), {}, { preserveScroll: true });
    };

    return (
        <AuthenticatedLayout>
            <Head title="Social - Expandir Círculo" />

            <main className="max-w-[1200px] mx-auto px-4 md:px-10 py-10">
                <div className="max-w-3xl mx-auto space-y-12">
                    <section className="text-center space-y-6">
                        <h1 className="text-3xl md:text-4xl font-black dark:text-white">Expandir Círculo</h1>
                        <p className="text-gray-500 dark:text-gray-400">Encontre novos parceiros de jornada para subir a montanha juntos.</p>
                        
                        <form onSubmit={handleSearch} className="relative max-w-2xl mx-auto bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-[#dbe6e1] dark:border-gray-700 p-2 transition-all focus-within:ring-2 focus-within:ring-primary focus-within:ring-opacity-50">
                            <div className="flex items-center px-4">
                                <span className="material-symbols-outlined text-gray-400">search</span>
                                <input 
                                    className="w-full border-none focus:ring-0 bg-transparent text-[#111815] dark:text-white py-3 px-3 text-lg font-medium" 
                                    placeholder="Buscar amigos pelo nickname ou e-mail" 
                                    type="text"
                                    value={search}
                                    onChange={(e) => setSearch(e.target.value)}
                                />
                            </div>
                        </form>
                    </section>

                    <div className="flex flex-col sm:flex-row items-center justify-center gap-4">
                        <div className="bg-gray-100 dark:bg-gray-800 p-1 rounded-full flex w-full max-w-md border border-gray-200 dark:border-gray-700">
                            <button 
                                onClick={() => handleTabChange('discovery')}
                                className={`flex-1 py-2.5 px-4 font-bold rounded-full transition-all ${tab === 'discovery' ? 'bg-white dark:bg-gray-700 text-[#111815] dark:text-white shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-primary'}`}
                            >
                                Descobrir
                            </button>
                            <button 
                                onClick={() => handleTabChange('sent')}
                                className={`flex-1 py-2.5 px-4 font-bold rounded-full transition-all ${tab === 'sent' ? 'bg-white dark:bg-gray-700 text-[#111815] dark:text-white shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-primary'}`}
                            >
                                Enviados ({pendingSent.length})
                            </button>
                            <button 
                                onClick={() => handleTabChange('received')}
                                className={`flex-1 py-2.5 px-4 font-bold rounded-full transition-all relative ${tab === 'received' ? 'bg-white dark:bg-gray-700 text-[#111815] dark:text-white shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-primary'}`}
                            >
                                Recebidos ({pendingReceived.length})
                                {pendingReceived.length > 0 && <span className="absolute top-2 right-2 size-2 bg-secondary rounded-full"></span>}
                            </button>
                        </div>
                    </div>

                    <section className="space-y-6">
                        <div className="flex items-center justify-between">
                            <h2 className="text-xl font-bold dark:text-white flex items-center gap-2">
                                {tab === 'discovery' ? 'Sugestões para você' : tab === 'sent' ? 'Pedidos Enviados' : 'Pedidos Recebidos'}
                                {tab === 'discovery' && <span className="px-2 py-0.5 bg-primary/10 text-primary text-[10px] rounded uppercase tracking-wider">Baseado no seu XP</span>}
                            </h2>
                        </div>

                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {tab === 'discovery' && users.map((user) => (
                                <div key={user.id} className="bg-white dark:bg-gray-800 p-5 rounded-3xl border border-[#dbe6e1] dark:border-gray-700 flex items-center gap-4 transition-all hover:-translate-y-1">
                                    <Link href={route('social.profile', user.nickname || user.id)} className="flex-1 flex items-center gap-4 group">
                                        <div className="relative shrink-0">
                                            <div className="size-16 rounded-2xl overflow-hidden bg-gray-100 group-hover:scale-105 transition-transform">
                                                <img alt={user.name} className="w-full h-full object-cover" src={user.avatar_url || `https://ui-avatars.com/api/?name=${user.name}`} />
                                            </div>
                                        </div>
                                        <div className="flex-1">
                                            <h3 className="font-bold dark:text-white group-hover:underline">{user.name}</h3>
                                            <p className="text-xs text-gray-500 dark:text-gray-400 font-medium">@{user.nickname || user.name.toLowerCase().replace(' ', '_')}</p>
                                        </div>
                                    </Link>
                                    <button 
                                        onClick={() => sendRequest(user.id)}
                                        className="px-4 py-2 bg-primary text-[#111815] font-bold text-sm rounded-xl hover:scale-105 transition-transform"
                                    >
                                        Adicionar
                                    </button>
                                </div>
                            ))}

                            {tab === 'sent' && pendingSent.map((f) => (
                                <div key={f.id} className="bg-white dark:bg-gray-800 p-5 rounded-3xl border border-[#dbe6e1] dark:border-gray-700 flex items-center gap-4 opacity-75">
                                    <Link href={route('social.profile', f.friend.nickname || f.friend.id)} className="flex-1 flex items-center gap-4 group">
                                        <div className="size-16 rounded-2xl overflow-hidden bg-gray-100 group-hover:scale-105 transition-transform shrink-0">
                                            <img alt={f.friend.name} className="w-full h-full object-cover" src={f.friend.avatar_url || `https://ui-avatars.com/api/?name=${f.friend.name}`} />
                                        </div>
                                        <div className="flex-1">
                                            <h3 className="font-bold dark:text-white group-hover:underline">{f.friend.name}</h3>
                                            <p className="text-xs text-gray-500 dark:text-gray-400">Aguardando aceitação...</p>
                                        </div>
                                    </Link>
                                </div>
                            ))}

                            {tab === 'received' && pendingReceived.map((f) => (
                                <div key={f.id} className="bg-white dark:bg-gray-800 p-5 rounded-3xl border border-[#dbe6e1] dark:border-gray-700 flex items-center gap-4">
                                    <Link href={route('social.profile', f.user.nickname || f.user.id)} className="flex-1 flex items-center gap-4 group">
                                        <div className="size-16 rounded-2xl overflow-hidden bg-gray-100 group-hover:scale-105 transition-transform shrink-0">
                                            <img alt={f.user.name} className="w-full h-full object-cover" src={f.user.avatar_url || `https://ui-avatars.com/api/?name=${f.user.name}`} />
                                        </div>
                                        <div className="flex-1">
                                            <h3 className="font-bold dark:text-white group-hover:underline">{f.user.name}</h3>
                                            <p className="text-xs text-gray-500">Quer ser seu amigo</p>
                                        </div>
                                    </Link>
                                    <div className="flex gap-2">
                                        <button 
                                            onClick={() => acceptRequest(f.id)}
                                            className="size-10 flex items-center justify-center bg-primary text-black rounded-full hover:scale-110 transition-all shadow-lg shadow-primary/20"
                                            title="Aceitar"
                                        >
                                            <span className="material-symbols-outlined">check</span>
                                        </button>
                                        <button 
                                            onClick={() => declineRequest(f.id)}
                                            className="size-10 flex items-center justify-center bg-red-100 text-red-500 rounded-full hover:scale-110 transition-all hover:bg-red-200"
                                            title="Recusar"
                                        >
                                            <span className="material-symbols-outlined">close</span>
                                        </button>
                                    </div>
                                </div>
                            ))}
                        </div>
                        
                        {(tab === 'discovery' && users.length === 0) && (
                            <div className="text-center py-10">
                                <p className="text-gray-500">Nenhum usuário encontrado.</p>
                            </div>
                        )}
                        {(tab === 'sent' && pendingSent.length === 0) && (
                            <div className="text-center py-10">
                                <p className="text-gray-500">Nenhum pedido enviado.</p>
                            </div>
                        )}
                        {(tab === 'received' && pendingReceived.length === 0) && (
                            <div className="text-center py-10">
                                <p className="text-gray-500">Nenhum pedido recebido.</p>
                            </div>
                        )}
                    </section>

                    {!hasReceivedBonus && (
                        <section className="bg-gradient-to-r from-primary/20 to-blue-500/20 rounded-3xl p-8 border border-primary/20 relative overflow-hidden">
                            <div className="absolute right-[-20px] top-[-20px] opacity-10">
                                <span className="material-symbols-outlined text-[160px]">group</span>
                            </div>
                            <div className="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                                <div>
                                    <h2 className="text-2xl font-black mb-2 dark:text-white text-[#111815]">Subir é melhor acompanhado!</h2>
                                    <p className="text-gray-600 dark:text-gray-300 max-w-[480px]">Usuários que adicionam pelo menos 3 amigos têm 60% mais chances de manter sua ofensiva por mais de 30 dias.</p>
                                </div>
                                <div className="flex items-center gap-4">
                                    <span className="text-sm font-bold text-primary">+200 XP Bônus</span>
                                </div>
                            </div>
                        </section>
                    )}
                </div>

                <section className="mt-16 text-center space-y-8 py-12 border-t border-[#dbe6e1] dark:border-gray-800">
                    <div className="inline-block p-4 rounded-full bg-primary/10 text-primary mb-4">
                        <span className="material-symbols-outlined text-5xl">share</span>
                    </div>
                    <h2 className="text-4xl font-black dark:text-white leading-tight">Não encontrou quem procurava? <br/><span className="text-primary italic">Convide por link!</span></h2>
                    <div className="flex justify-center gap-4">
                        <button 
                            onClick={() => {
                                const url = route('social.profile', auth.user.nickname || auth.user.id);
                                navigator.clipboard.writeText(url);
                                window.dispatchEvent(new CustomEvent('show-toast', { 
                                    detail: { message: 'Link copiado para a área de transferência!', type: 'success' } 
                                }));
                            }}
                            className="px-8 py-4 bg-primary text-[#111815] font-bold rounded-full shadow-lg shadow-primary/20 hover:scale-105 transition-all flex items-center gap-2"
                        >
                            <span className="material-symbols-outlined">link</span>
                            Copiar Link de Convite
                        </button>
                    </div>
                </section>
            </main>
        </AuthenticatedLayout>
    );
}

declare function route(name: string, params?: any): string;
