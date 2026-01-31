import React from 'react';
import { Link } from '@inertiajs/react';

declare function route(name: string, params?: any, absolute?: boolean): string;

interface PublicNavbarProps {
    auth: {
        user: any;
    };
}

export default function PublicNavbar({ auth }: PublicNavbarProps) {
    return (
        <header className="sticky top-0 z-50 w-full border-b border-solid border-[#dbe6e1] bg-white/80 dark:bg-background-dark/80 backdrop-blur-md px-4 md:px-20 lg:px-40 py-3">
            <div className="max-w-[1200px] mx-auto flex items-center justify-between">
                <Link href={route('home')} className="flex items-center gap-3 hover:opacity-80 transition-opacity">
                    <div className="size-8 text-primary">
                        <svg fill="currentColor" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                            <path clipRule="evenodd" d="M24 4H42V17.3333V30.6667H24V44H6V30.6667V17.3333H24V4Z" fillRule="evenodd"></path>
                        </svg>
                    </div>
                    <h2 className="text-[#111815] dark:text-white text-xl font-bold leading-tight tracking-tight">Everest</h2>
                </Link>
                <nav className="hidden md:flex items-center gap-9">
                    <Link className="text-[#111815] dark:text-gray-300 text-sm font-medium hover:text-primary transition-colors" href={route('pricing')}>Planos</Link>
                    <Link className="text-[#111815] dark:text-gray-300 text-sm font-medium hover:text-primary transition-colors" href={route('blog')}>Blog</Link>
                    <Link className="text-[#111815] dark:text-gray-300 text-sm font-medium hover:text-primary transition-colors" href={route('support')}>Contato</Link>
                </nav>
                <div className="flex gap-3">
                    {auth.user ? (
                        <Link
                            href={route('dashboard')}
                            className="flex min-w-[100px] cursor-pointer items-center justify-center rounded-full h-10 px-5 bg-primary text-[#111815] text-sm font-bold shadow-lg shadow-primary/20 hover:scale-105 transition-transform"
                        >
                            Dashboard
                        </Link>
                    ) : (
                        <>
                            <Link
                                href={route('login')}
                                className="hidden sm:flex min-w-[84px] cursor-pointer items-center justify-center rounded-full h-10 px-5 bg-background-light dark:bg-gray-800 text-[#111815] dark:text-white text-sm font-bold border border-[#dbe6e1] dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                            >
                                Login
                            </Link>
                            <Link
                                href={route('register')}
                                className="flex min-w-[100px] cursor-pointer items-center justify-center rounded-full h-10 px-5 bg-primary text-[#111815] text-sm font-bold shadow-lg shadow-primary/20 hover:scale-105 transition-transform"
                            >
                                Teste Grátis de 14 dias
                            </Link>
                        </>
                    )}
                </div>
            </div>
        </header>
    );
}
