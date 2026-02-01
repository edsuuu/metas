import { PropsWithChildren, ReactNode } from 'react';
import { Link, usePage } from '@inertiajs/react';
import { User } from '@/types';
import Dropdown from '@/Components/Dropdown';
import Footer from '@/Components/Footer';

declare function route(name?: string, params?: any, absolute?: boolean): any;

export default function Authenticated({ children }: PropsWithChildren<{ header?: ReactNode }>) {
    const { auth } = usePage().props as any;
    const user = auth.user as User;

    // Default XP values if not provided (fallback)
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
                            <Link href={route('achievements')} className={`text-sm font-bold border-b-2 transition-colors ${route().current('achievements') ? 'text-primary border-primary' : 'text-[#111815] dark:text-gray-300 border-transparent hover:text-primary'}`}>Conquistas</Link>
                        </nav>
                        <div className="flex items-center gap-4">
                            <div className="hidden sm:flex items-center gap-2 bg-orange-50 dark:bg-orange-950/30 px-3 py-1.5 rounded-full border border-orange-100 dark:border-orange-900/50">
                                <span className="material-symbols-outlined icon-gradient-fire streak-fire text-xl leading-none" style={{ fontVariationSettings: "'FILL' 1" }}>local_fire_department</span>
                                <span className="font-extrabold text-orange-600 dark:text-orange-400 text-sm">{usePage().props.streak as number} Dias</span>
                            </div>

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
                                    <Dropdown.Link href={route('profile.edit')}>Perfil</Dropdown.Link>
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

            <Footer />
        </div>
    );
}
