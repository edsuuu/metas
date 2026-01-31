import { Link } from '@inertiajs/react';
import React, { ReactNode } from 'react';

declare function route(name: string, params?: any, absolute?: boolean): string;

interface LegalNavbarProps {
    children?: ReactNode;
}

export default function LegalNavbar({ children }: LegalNavbarProps) {
    return (
        <header className="w-full border-b border-solid border-[#dbe6e1] dark:border-gray-800 bg-white/80 dark:bg-background-dark/80 backdrop-blur-md px-4 md:px-20 lg:px-40 py-4 sticky top-0 z-50">
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
                    <Link className="text-[#111815] dark:text-gray-300 text-sm font-medium hover:text-primary transition-colors" href={route('home') + '#features'}>Funcionalidades</Link>
                    <Link className="text-[#111815] dark:text-gray-300 text-sm font-medium hover:text-primary transition-colors" href={route('pricing')}>Planos</Link>
                    <Link className="text-[#111815] dark:text-gray-300 text-sm font-medium hover:text-primary transition-colors" href={route('blog')}>Blog</Link>
                    <Link className="text-[#111815] dark:text-gray-300 text-sm font-medium hover:text-primary transition-colors" href={route('support')}>Contato</Link>
                </nav>

                {children ? (
                    <div className="flex items-center gap-6">
                        {children}
                    </div>
                ) : (
                    <div className="flex items-center gap-6">
                        <Link className="text-sm font-bold text-primary px-4 py-2 rounded-full border border-primary hover:bg-primary hover:text-white transition-all" href={route('dashboard')}>Ir para o App</Link>
                    </div>
                )}
            </div>
        </header>
    );
}
