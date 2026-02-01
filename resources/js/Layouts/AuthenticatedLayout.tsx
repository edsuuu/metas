import { PropsWithChildren, ReactNode, useEffect, useState } from 'react';
import { Link, usePage } from '@inertiajs/react';
import { User } from '@/types';
import Dropdown from '@/Components/Dropdown';
import Footer from '@/Components/Footer';
import Toast from '@/Components/Toast';
import axios from 'axios';

declare function route(name?: string, params?: any, absolute?: boolean): any;

export default function Authenticated({ children }: PropsWithChildren<{ header?: ReactNode }>) {
    const { auth } = usePage().props as any;
    const user = auth.user as User;
    const initialPendingCount = usePage().props.pendingRequestsCount as number;
    const [pendingRequestsCount, setPendingRequestsCount] = useState(initialPendingCount);

    useEffect(() => {
        setPendingRequestsCount(initialPendingCount);
    }, [initialPendingCount]);

    useEffect(() => {
        const interval = setInterval(() => {
            axios.get(route('social.status'))
                .then(response => {
                    setPendingRequestsCount(response.data.pending_requests);
                })
                .catch(error => console.error("Polling Error", error));
        }, 3000);

        return () => clearInterval(interval);
    }, []);

    // XP Logic moved to Dashboard

    return (
        <div className="bg-background-light dark:bg-background-dark text-[#111815] transition-colors duration-300 min-h-screen flex flex-col">

            <header className="sticky top-0 z-50 w-full border-b border-solid border-[#dbe6e1] bg-white dark:bg-background-dark px-4 md:px-20 lg:px-40 pt-4 pb-2">
                <div className="max-w-[1200px] mx-auto space-y-4">
                    <div className="flex items-center justify-between">
                        <div className="flex items-center gap-3">
                            <Link href={route('dashboard')} className="size-8 text-primary">
                                <svg fill="currentColor" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                                    <path clipRule="evenodd" d="M24 4H42V17.3333V30.6667H24V44H6V30.6667V17.3333H24V4Z" fillRule="evenodd"></path>
                                </svg>
                            </Link>
                            <Link href={route('dashboard')}>
                                <h2 className="text-[#111815] dark:text-white text-xl font-bold leading-tight tracking-tight">Everest</h2>
                            </Link>
                        </div>
                        <nav className="hidden md:flex items-center gap-9">
                            <Link href={route('dashboard')} className={`text-sm font-bold border-b-2 transition-colors ${route().current('dashboard') ? 'text-primary border-primary' : 'text-[#111815] dark:text-gray-300 border-transparent hover:text-primary'}`}>Dashboard</Link>
                            <Link href={route('goals.index')} className={`text-sm font-bold border-b-2 transition-colors ${route().current('goals.*') ? 'text-primary border-primary' : 'text-[#111815] dark:text-gray-300 border-transparent hover:text-primary'}`}>Metas</Link>
                            <Link href={route('social.feed')} className={`text-sm font-bold border-b-2 transition-colors ${route().current('social.feed') ? 'text-primary border-primary' : 'text-[#111815] dark:text-gray-300 border-transparent hover:text-primary'}`}>Social</Link>
                            <Link href={route('social.index')} className={`text-sm font-bold border-b-2 transition-colors relative ${route().current('social.index') ? 'text-primary border-primary' : 'text-[#111815] dark:text-gray-300 border-transparent hover:text-primary'}`}>
                                Explorar
                                {pendingRequestsCount > 0 && (
                                    <span className="absolute -right-2 -top-1 size-2 bg-red-500 rounded-full animate-pulse"></span>
                                )}
                            </Link>
                        </nav>
                        <div className="flex items-center gap-4">
                            {(() => {
                                const streakCount = usePage().props.streak as number || 0;
                                const hasStreak = streakCount > 0;
                                return (
                                    <div className={`hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-full border transition-colors ${
                                        hasStreak 
                                        ? 'bg-orange-50 dark:bg-orange-950/30 border-orange-100 dark:border-orange-900/50' 
                                        : 'bg-gray-100 dark:bg-gray-800 border-gray-200 dark:border-gray-700'
                                    }`}>
                                        <span className={`material-symbols-outlined text-xl leading-none ${hasStreak ? 'icon-gradient-fire streak-fire text-orange-500' : 'text-gray-400'}`} style={{ fontVariationSettings: "'FILL' 1" }}>local_fire_department</span>
                                        <span className={`font-extrabold text-sm ${hasStreak ? 'text-orange-600 dark:text-orange-400' : 'text-gray-500 dark:text-gray-400'}`}>
                                            {streakCount} {streakCount <= 1 ? 'Dia' : 'Dias'}
                                        </span>
                                    </div>
                                );
                            })()}

                            <Dropdown>
                                <Dropdown.Trigger>
                                    <div className="size-10 rounded-full bg-gray-200 dark:bg-gray-800 border-2 border-primary overflow-hidden cursor-pointer">
                                        {user.avatar_url ? (
                                            <img src={user.avatar_url} alt={user.name} className="w-full h-full object-cover" />
                                        ) : (
                                            <div className="w-full h-full flex items-center justify-center bg-primary text-[#111815] font-bold text-lg">
                                                {user.name.charAt(0).toUpperCase()}
                                            </div>
                                        )}
                                    </div>
                                </Dropdown.Trigger>
                                <Dropdown.Content align="right" width="48">
                                    <div className="px-4 py-3 border-b border-gray-100 dark:border-gray-600 mb-1">
                                            <p className="text-sm font-bold text-gray-900 dark:text-white truncate">{user.name}</p>
                                            <p className="text-xs text-gray-500 truncate">{user.email}</p>
                                    </div>
                                    <Dropdown.Link href={route('social.profile')}>Perfil</Dropdown.Link>
                                    {!(user.roles?.includes('Administrador') || user.roles?.includes('Suporte')) && (
                                        <Dropdown.Link href={route('support.my-tickets')}>Meus Chamados</Dropdown.Link>
                                    )}
                                    {(user.roles?.includes('Administrador') || user.roles?.includes('Suporte')) && (
                                        <Dropdown.Link href={route('admin.dashboard')}>Painel Admin</Dropdown.Link>
                                    )}
                                    <Dropdown.Link href={route('logout')} method="post" as="button">Sair</Dropdown.Link>
                                </Dropdown.Content>
                            </Dropdown>
                        </div>
                    </div>
                </div>
            </header>

            <main className="flex-1">
                {children}
            </main>
            <Toast />
        </div>
    );
}
