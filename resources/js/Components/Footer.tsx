import React from 'react';
import { Link } from '@inertiajs/react';

declare function route(name: string, params?: any, absolute?: boolean): string;

export default function Footer() {
    return (
        <footer className="w-full bg-white dark:bg-background-dark border-t border-[#dbe6e1] dark:border-gray-800 py-12 px-4 md:px-20 lg:px-40">
            <div className="max-w-[1200px] mx-auto flex flex-col md:flex-row justify-between items-center gap-8">
                <div className="flex flex-col gap-4">
                    <div className="flex items-center gap-3">
                        <div className="size-6 text-primary">
                            <svg fill="currentColor" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                                <path clipRule="evenodd" d="M24 4H42V17.3333V30.6667H24V44H6V30.6667V17.3333H24V44Z" fillRule="evenodd"></path>
                            </svg>
                        </div>
                        <h2 className="text-[#111815] dark:text-white text-lg font-bold">Everest</h2>
                    </div>
                    <p className="text-gray-500 text-sm">Capacitando pessoas a alcançarem seu auge desde 2024.</p>
                </div>
                <div className="flex gap-8 text-sm font-medium text-gray-600 dark:text-gray-400">
                    <Link className="hover:text-primary transition-colors" href={route('privacy')}>Privacidade</Link>
                    <Link className="hover:text-primary transition-colors" href={route('terms')}>Termos</Link>
                    <Link className="hover:text-primary transition-colors" href={route('blog')}>Blog</Link>
                    <Link className="hover:text-primary transition-colors" href={route('support')}>Contato</Link>
                </div>
            </div>
            <div className="max-w-[1200px] mx-auto mt-12 pt-8 border-t border-gray-100 dark:border-gray-800 text-center">
                <p className="text-xs text-gray-400">© 2024 Everest Technologies Inc. Todos os direitos reservados.</p>
            </div>
        </footer>
    );
}
