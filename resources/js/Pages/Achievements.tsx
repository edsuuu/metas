import React from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';
import { PageProps } from '@/types';

declare function route(name: string, params?: any, absolute?: boolean): string;

export default function Achievements({ auth }: PageProps) {
    return (
        <AuthenticatedLayout>
            <Head title="Conquistas e Rankings" />

            <div className="max-w-[1200px] mx-auto px-4 md:px-10 py-10 font-display text-[#111815] dark:text-white">
                <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div className="lg:col-span-2 space-y-8">
                        <section className="bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-sm border border-[#dbe6e1] dark:border-gray-700">
                            <div className="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
                                <div className="flex items-center gap-6">
                                    <div className="size-24 rounded-2xl bg-gradient-to-br from-primary to-blue-500 flex items-center justify-center text-white relative">
                                        <span className="text-4xl font-black">15</span>
                                        <div className="absolute -bottom-2 bg-white dark:bg-gray-700 text-[#111815] dark:text-white px-3 py-0.5 rounded-full text-xs font-bold border border-gray-100 dark:border-gray-600">NÍVEL</div>
                                    </div>
                                    <div>
                                        <h1 className="text-3xl font-extrabold dark:text-white">Explorador Senior</h1>
                                        <p className="text-gray-500 dark:text-gray-400">Você está no top 5% dos usuários este mês!</p>
                                    </div>
                                </div>
                                <div className="text-right">
                                    <p className="text-sm font-bold text-primary uppercase tracking-wider">Próximo: Mestre das Montanhas</p>
                                    <p className="text-gray-400 text-xs">250 / 500 XP para o nível 16</p>
                                </div>
                            </div>
                            <div className="w-full h-4 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                                <div className="h-full bg-primary w-1/2 rounded-full shadow-[0_0_15px_rgba(19,236,146,0.4)]"></div>
                            </div>
                        </section>

                        <section className="space-y-6">
                            <div className="flex items-center justify-between">
                                <h2 className="text-2xl font-bold dark:text-white">Distintivos Conquistados</h2>
                                <button className="text-primary font-bold text-sm hover:underline">Ver todos (24)</button>
                            </div>
                            <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                <div className="bg-white dark:bg-gray-800 p-6 rounded-3xl border border-[#dbe6e1] dark:border-gray-700 flex flex-col items-center text-center gap-3 transition-transform hover:scale-105">
                                    <div className="size-16 rounded-full bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center text-yellow-500">
                                        <span className="material-symbols-outlined text-4xl">workspace_premium</span>
                                    </div>
                                    <h3 className="font-bold text-sm dark:text-white">Madrugador</h3>
                                    <p className="text-[10px] text-gray-500 uppercase font-bold tracking-tighter">7/7 Dias às 6h</p>
                                </div>
                                <div className="bg-white dark:bg-gray-800 p-6 rounded-3xl border border-[#dbe6e1] dark:border-gray-700 flex flex-col items-center text-center gap-3 transition-transform hover:scale-105">
                                    <div className="size-16 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-500">
                                        <span className="material-symbols-outlined text-4xl">menu_book</span>
                                    </div>
                                    <h3 className="font-bold text-sm dark:text-white">Leitor Voraz</h3>
                                    <p className="text-[10px] text-gray-500 uppercase font-bold tracking-tighter">5 Livros Lidos</p>
                                </div>
                                <div className="bg-white dark:bg-gray-800 p-6 rounded-3xl border border-[#dbe6e1] dark:border-gray-700 flex flex-col items-center text-center gap-3 transition-transform hover:scale-105">
                                    <div className="size-16 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-green-500">
                                        <span className="material-symbols-outlined text-4xl">fitness_center</span>
                                    </div>
                                    <h3 className="font-bold text-sm dark:text-white">Atleta Pro</h3>
                                    <p className="text-[10px] text-gray-500 uppercase font-bold tracking-tighter">30 Treinos</p>
                                </div>
                                <div className="bg-gray-50 dark:bg-gray-800/50 p-6 rounded-3xl border border-dashed border-gray-300 dark:border-gray-700 flex flex-col items-center text-center gap-3 opacity-60">
                                    <div className="size-16 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-400">
                                        <span className="material-symbols-outlined text-4xl">lock</span>
                                    </div>
                                    <h3 className="font-bold text-sm dark:text-gray-400">Imbatível</h3>
                                    <p className="text-[10px] text-gray-400 uppercase font-bold tracking-tighter">30 Dias Seguidos</p>
                                </div>
                            </div>
                        </section>

                        <section className="bg-gradient-to-r from-orange-500 to-red-500 rounded-3xl p-8 text-white relative overflow-hidden">
                            <div className="absolute right-[-20px] top-[-20px] opacity-20">
                                <span className="material-symbols-outlined text-[160px]">local_fire_department</span>
                            </div>
                            <div className="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                                <div>
                                    <h2 className="text-3xl font-black mb-2">Ofensiva Lendária!</h2>
                                    <p className="text-white/80 max-w-[400px]">Você completou suas metas por 12 dias consecutivos. Mantenha o fogo aceso para ganhar o dobro de XP amanhã!</p>
                                </div>
                                <div className="flex flex-col items-center">
                                    <span className="text-6xl font-black">12</span>
                                    <span className="text-sm font-bold uppercase tracking-widest">Dias de Fogo</span>
                                </div>
                            </div>
                        </section>
                    </div>

                    <div className="lg:col-span-1">
                        <div className="bg-white dark:bg-gray-800 rounded-3xl border border-[#dbe6e1] dark:border-gray-700 shadow-sm overflow-hidden flex flex-col h-full">
                            <div className="p-6 border-b border-[#dbe6e1] dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                                <div className="flex items-center justify-between mb-4">
                                    <h2 className="text-xl font-bold dark:text-white">Ranking Semanal</h2>
                                    <span className="material-symbols-outlined text-primary">leaderboard</span>
                                </div>
                                <div className="flex bg-white dark:bg-gray-700 rounded-full p-1 border border-gray-200 dark:border-gray-600">
                                    <button className="flex-1 py-2 text-xs font-bold rounded-full bg-primary text-[#111815]">AMIGOS</button>
                                    <button className="flex-1 py-2 text-xs font-bold rounded-full text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-600">GLOBAL</button>
                                </div>
                            </div>
                            <div className="flex-1 p-6 space-y-4">
                                <div className="flex flex-col gap-3 mb-6">
                                    <div className="rank-card flex items-center gap-4 p-3 rounded-2xl bg-yellow-50 dark:bg-yellow-900/10 border border-yellow-200 dark:border-yellow-900/30 transition-all">
                                        <span className="text-xl font-black text-yellow-600">1</span>
                                        <div className="size-10 rounded-full overflow-hidden border-2 border-yellow-400">
                                            <img alt="User" className="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDcTPJYH4JN0TBu2TY3-Uo1hf9k7elU8OT_He5zr1kzlqvU1qujAVUZozInfo-BcI6B9K_A4gjTdEDv3VWXk03hMs29NtGqHIGB-MB9EPsxCGEOBKBBKy90RkD_82NLeVfXJNfvY1SstxesOOJqLJSp0RXObCIJYYQrHg7bePjJRB5ca_j6wcaQChRd0nId8bne6V5va3Jo7gsu1nAYJxw0GvMiXqiHDuzx6aHegBjLELW9e9i1A15iQEvigqoSrMkp40RyP-ODJ94" />
                                        </div>
                                        <div className="flex-1">
                                            <p className="text-sm font-bold dark:text-white">Ricardo M.</p>
                                            <p className="text-[10px] text-gray-500">2,450 XP</p>
                                        </div>
                                        <span className="material-symbols-outlined text-yellow-500">emoji_events</span>
                                    </div>
                                    <div className="rank-card flex items-center gap-4 p-3 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 transition-all">
                                        <span className="text-xl font-black text-slate-400">2</span>
                                        <div className="size-10 rounded-full overflow-hidden border-2 border-slate-300">
                                            <img alt="User" className="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAECXUGJLJQPE8nsVI31bahqgCiAvATaa6Zasl6NUo1hH9qnl6nxDhJZmcnlOtX8WAK2zzMcZvyt640qqLnmJSyTSEdiiFQgGEH05_WRepZ7250UNWVLckI9s0aBiDY75vu14AlJr8mhYkBviSrllA5YhR_c1RSaJY3La_pqa7iQl19kCJ9sE98aaPCqxph-OSlHN1sk_c-K5xC4mYQPSlQBZJHEO1kgi07jEmKXqfMXyLPpf6HH1vpNjU2G4TQ_Ob7IagFDv1ZQFk" />
                                        </div>
                                        <div className="flex-1">
                                            <p className="text-sm font-bold dark:text-white">Ana Clara</p>
                                            <p className="text-[10px] text-gray-500">2,120 XP</p>
                                        </div>
                                    </div>
                                    <div className="rank-card flex items-center gap-4 p-3 rounded-2xl bg-primary/10 border-2 border-primary transition-all scale-105 shadow-lg">
                                        <span className="text-xl font-black text-primary">3</span>
                                        <div className="size-10 rounded-full overflow-hidden border-2 border-primary">
                                            <img alt="User" className="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDqydBISeNSHW_BuxfPyKZEXKWqCU8uKoBVdg-s0x9kCUkQWJxRQvPfC2Y2TnLK3pFhUn0Khtgc4LvGyIdGjZtFs6ZL1oogluz9xQCSKJUDXUszhOFPFSW8b6Xsu8oGFv4seeSWjTvXQ6-nUFKxYLEGbc5_erTYwzyifspaA3qBYQh_q5InBJIaTo53vfwJeQF6Qa7qSzIWKDu3FTaCNxa_VkI7Nx518rKlaor1TfO4fhteqmZ1pzMRnZYSCA5x97vcLZxsezuYQDY" />
                                        </div>
                                        <div className="flex-1">
                                            <p className="text-sm font-bold dark:text-white">Você</p>
                                            <p className="text-[10px] text-primary font-bold">1,890 XP</p>
                                        </div>
                                        <div className="bg-primary text-[#111815] text-[10px] font-black px-2 py-1 rounded">VOCÊ</div>
                                    </div>
                                </div>
                                <div className="space-y-4 opacity-70">
                                    <div className="flex items-center gap-4 px-3">
                                        <span className="text-sm font-bold text-gray-400 w-4">4</span>
                                        <div className="size-8 rounded-full bg-gray-200 dark:bg-gray-700"></div>
                                        <p className="text-sm font-medium dark:text-gray-300 flex-1">Marcos Silva</p>
                                        <p className="text-xs font-bold dark:text-gray-400">1,400</p>
                                    </div>
                                    <div className="flex items-center gap-4 px-3">
                                        <span className="text-sm font-bold text-gray-400 w-4">5</span>
                                        <div className="size-8 rounded-full bg-gray-200 dark:bg-gray-700"></div>
                                        <p className="text-sm font-medium dark:text-gray-300 flex-1">Juliana P.</p>
                                        <p className="text-xs font-bold dark:text-gray-400">1,250</p>
                                    </div>
                                </div>
                            </div>
                            <div className="p-6 bg-primary/5 dark:bg-primary/10 border-t border-[#dbe6e1] dark:border-gray-700">
                                <button className="w-full py-3 bg-[#111815] dark:bg-primary text-white dark:text-[#111815] font-bold rounded-xl hover:scale-105 transition-transform">
                                    Desafiar Amigos
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <section className="mt-16 text-center space-y-8 py-12 border-t border-[#dbe6e1] dark:border-gray-800">
                    <div className="inline-block p-4 rounded-full bg-primary/10 text-primary mb-4 animate-bounce">
                        <span className="material-symbols-outlined text-5xl">celebration</span>
                    </div>
                    <h2 className="text-4xl font-black dark:text-white leading-tight">Cada passo é uma vitória. <br /><span className="text-primary italic">Continue subindo!</span></h2>
                    <div className="flex justify-center gap-4">
                        <Link href={route('goals.create')} className="px-8 py-4 bg-primary text-[#111815] font-bold rounded-full shadow-lg shadow-primary/20 hover:scale-105 transition-all">Ver Minhas Metas</Link>
                        <button className="px-8 py-4 bg-white dark:bg-gray-800 text-[#111815] dark:text-white font-bold border border-[#dbe6e1] dark:border-gray-700 rounded-full hover:bg-gray-50 transition-all">Compartilhar Progresso</button>
                    </div>
                </section>
            </div>
        </AuthenticatedLayout>
    );
}
