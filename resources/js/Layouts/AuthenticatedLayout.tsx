import { useState, PropsWithChildren, ReactNode } from 'react';
import { Link, usePage } from '@inertiajs/react';
import { User } from '@/types';
import Dropdown from '@/Components/Dropdown';

export default function Authenticated({ header, children }: PropsWithChildren<{ header?: ReactNode }>) {
    const user = usePage().props.auth.user as User;

    return (
        <div className="bg-background-light dark:bg-background-dark text-[#111815] transition-colors duration-300 min-h-screen flex flex-col">
            
            <header className="sticky top-0 z-50 w-full border-b border-solid border-[#dbe6e1] bg-white/80 dark:bg-background-dark/80 backdrop-blur-md px-4 md:px-20 lg:px-40 py-3">
                <div className="max-w-[1200px] mx-auto flex items-center justify-between">
                    <div className="flex items-center gap-12">
                        <Link href={route('dashboard')} className="flex items-center gap-3 hover:opacity-80 transition-opacity">
                            <div className="size-8 text-primary">
                                <svg fill="currentColor" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                                    <path clipRule="evenodd" d="M24 4H42V17.3333V30.6667H24V44H6V30.6667V17.3333H24V4Z" fillRule="evenodd"></path>
                                </svg>
                            </div>
                            <h2 className="text-[#111815] dark:text-white text-xl font-bold leading-tight tracking-tight">Everest</h2>
                        </Link>

                        <nav className="hidden md:flex items-center gap-8">
                            <Link 
                                href={route('dashboard')} 
                                className={`text-sm font-bold transition-colors ${route().current('dashboard') ? 'text-[#111815] dark:text-white' : 'text-gray-400 hover:text-primary'}`}
                            >
                                Visão Geral
                            </Link>
                            <Link 
                                href={route('goals.create')} 
                                className={`text-sm font-bold transition-colors ${route().current('goals.*') ? 'text-[#111815] dark:text-white' : 'text-gray-400 hover:text-primary'}`}
                            >
                                Metas
                            </Link>
                            <Link href="#" className="text-sm font-bold text-gray-400 hover:text-primary transition-colors">Conquistas</Link>
                        </nav>
                    </div>

                    <div className="flex items-center gap-6">
                        <button className="hidden md:flex size-10 items-center justify-center rounded-full bg-white dark:bg-gray-800 border border-[#dbe6e1] dark:border-gray-700 text-gray-400 hover:text-primary hover:border-primary transition-all shadow-sm">
                            <span className="material-symbols-outlined text-xl">notifications</span>
                        </button>
                        
                        <div className="relative">
                            <Dropdown>
                                <Dropdown.Trigger>
                                    <button className="flex items-center gap-3 focus:outline-none">
                                        <div className="flex flex-col items-end hidden md:flex">
                                            <span className="text-sm font-bold text-[#111815] dark:text-white leading-none">{user.name}</span>
                                            <span className="text-xs text-gray-400 font-medium">@{user.nickname || 'usuario'}</span>
                                        </div>
                                        <div className="size-10 rounded-full bg-gray-200 overflow-hidden border-2 border-transparent hover:border-primary transition-all cursor-pointer">
                                            {user.avatar_url ? (
                                                <img src={user.avatar_url} alt={user.name} className="w-full h-full object-cover" />
                                            ) : (
                                                <div className="w-full h-full flex items-center justify-center bg-primary text-[#111815] font-bold text-lg">
                                                    {user.name.charAt(0).toUpperCase()}
                                                </div>
                                            )}
                                        </div>
                                    </button>
                                </Dropdown.Trigger>

                                <Dropdown.Content align="right" width="48">
                                    <div className="px-4 py-3 border-b border-gray-100 dark:border-gray-600 mb-1">
                                         <p className="text-sm font-bold text-gray-900 dark:text-white truncate">{user.name}</p>
                                         <p className="text-xs text-gray-500 truncate">{user.email}</p>
                                    </div>
                                    
                                    <Dropdown.Link href={route('profile.edit')}>
                                        Perfil
                                    </Dropdown.Link>
                                    <Dropdown.Link href={route('logout')} method="post" as="button">
                                        Sair
                                    </Dropdown.Link>
                                </Dropdown.Content>
                            </Dropdown>
                        </div>
                    </div>
                </div>
            </header>

            <main className="flex-1">
                {children}
            </main>
            
            <footer className="w-full py-8 text-center border-t border-[#dbe6e1] dark:border-gray-800 mt-auto">
                <p className="text-xs text-gray-400">© 2024 Everest Technologies Inc. Todos os direitos reservados.</p>
            </footer>
        </div>
    );
}
