import React from 'react';
import PublicNavbar from '@/Components/PublicNavbar';
import Footer from '@/Components/Footer';
import { Head } from '@inertiajs/react';
import { PageProps } from '@/types';

export default function BlogIndex({ auth }: PageProps) {
    return (
        <div className="bg-background-light dark:bg-background-dark text-[#111815] transition-colors duration-300 font-display min-h-screen flex flex-col">
            <Head title="Blog - Everest" />
            <PublicNavbar auth={auth} />

            <main className="flex-1 flex flex-col items-center justify-center p-8 text-center pt-24">
                <div className="size-24 rounded-full bg-primary/10 flex items-center justify-center text-primary mb-8 animate-pulse text-5xl">
                    <span className="material-symbols-outlined text-5xl">edit_note</span>
                </div>
                <h1 className="text-4xl md:text-5xl font-black mb-4 dark:text-white tracking-tight">Blog Everest</h1>
                <p className="text-gray-500 dark:text-gray-400 text-lg max-w-[600px] mb-12">
                    Estamos preparando conteúdos incríveis sobre produtividade, foco e conquista de grandes metas para você. Fique atento!
                </p>
                {/* <div className="flex gap-4">
                    <button className="px-8 py-4 bg-primary text-[#111815] font-bold rounded-full shadow-lg shadow-primary/20 hover:scale-105 transition-all">
                        Me avise por e-mail
                    </button>
                </div> */}
            </main>
            
            <Footer />
        </div>
    );
}
