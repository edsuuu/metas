import React, { FormEventHandler } from 'react';
import { Head, Link, useForm, usePage } from '@inertiajs/react';
import LegalNavbar from '@/Components/LegalNavbar';
import Footer from '@/Components/Footer';

declare function route(name: string, params?: any, absolute?: boolean): string;

export default function SupportIndex() {
    const { data, setData, post, processing, errors, reset } = useForm({
        name: '',
        email: '',
        subject: 'Dúvida Técnica',
        message: '',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('support.ticket.store'), {
            preserveScroll: true,
            onSuccess: () => reset(),
        });
    };

    const { flash } = usePage().props as any;

    return (
        <div className="bg-background-light dark:bg-background-dark text-[#111815] transition-colors duration-300 min-h-screen flex flex-col font-display">
            <Head title="Everest - Central de Suporte" />

            <LegalNavbar>
                <Link className="px-5 py-2 rounded-full bg-primary text-[#111815] text-sm font-bold shadow-sm hover:scale-105 transition-transform" href={route('support.my-tickets')}>Meus Chamados</Link>
            </LegalNavbar>

            <main className="flex-1">
                <section className="pt-20 pb-16 px-4" style={{
                    background: 'radial-gradient(circle at top right, rgba(19, 236, 146, 0.15), transparent), radial-gradient(circle at bottom left, rgba(19, 236, 146, 0.05), transparent)'
                }}>
                    <div className="max-w-[800px] mx-auto text-center">
                        <h1 className="text-4xl md:text-5xl font-black text-[#111815] dark:text-white mb-6 tracking-tight">Central de Ajuda</h1>
                        <p className="text-lg text-gray-600 dark:text-gray-400 mb-10">Tudo o que você precisa para conquistar seus objetivos sem obstáculos.</p>
                        <div className="relative max-w-2xl mx-auto group">
                            <span className="absolute left-6 top-1/2 -translate-y-1/2 text-gray-400 material-symbols-outlined text-2xl group-focus-within:text-primary transition-colors">search</span>
                            <input className="w-full pl-16 pr-6 h-16 rounded-full bg-white dark:bg-gray-800 border-2 border-[#dbe6e1] dark:border-gray-700 focus:border-primary focus:ring-0 text-lg shadow-xl shadow-primary/5 transition-all outline-none" placeholder="Como podemos te ajudar?" type="text" />
                        </div>
                    </div>
                </section>

                <section className="max-w-[1200px] mx-auto px-4 py-16">
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <a className="group bg-white dark:bg-gray-800 p-8 rounded-3xl border border-[#dbe6e1] dark:border-gray-700 hover:border-primary dark:hover:border-primary transition-all hover:shadow-lg hover:-translate-y-1" href="#">
                            <div className="size-14 bg-background-light dark:bg-gray-700 rounded-2xl flex items-center justify-center text-primary mb-6 group-hover:scale-110 transition-transform">
                                <span className="material-symbols-outlined text-3xl">key</span>
                            </div>
                            <h3 className="text-xl font-bold text-[#111815] dark:text-white mb-2">Problemas de Login</h3>
                            <p className="text-sm text-gray-500 dark:text-gray-400">Recuperação de senha, autenticação e segurança da conta.</p>
                        </a>
                        <a className="group bg-white dark:bg-gray-800 p-8 rounded-3xl border border-[#dbe6e1] dark:border-gray-700 hover:border-primary dark:hover:border-primary transition-all hover:shadow-lg hover:-translate-y-1" href="#">
                            <div className="size-14 bg-background-light dark:bg-gray-700 rounded-2xl flex items-center justify-center text-primary mb-6 group-hover:scale-110 transition-transform">
                                <span className="material-symbols-outlined text-3xl">flag</span>
                            </div>
                            <h3 className="text-xl font-bold text-[#111815] dark:text-white mb-2">Gerenciamento de Metas</h3>
                            <p className="text-sm text-gray-500 dark:text-gray-400">Como criar, editar e acompanhar seu progresso no Everest.</p>
                        </a>
                        <a className="group bg-white dark:bg-gray-800 p-8 rounded-3xl border border-[#dbe6e1] dark:border-gray-700 hover:border-primary dark:hover:border-primary transition-all hover:shadow-lg hover:-translate-y-1" href="#">
                            <div className="size-14 bg-background-light dark:bg-gray-700 rounded-2xl flex items-center justify-center text-primary mb-6 group-hover:scale-110 transition-transform">
                                <span className="material-symbols-outlined text-3xl">auto_awesome</span>
                            </div>
                            <h3 className="text-xl font-bold text-[#111815] dark:text-white mb-2">Gamificação e XP</h3>
                            <p className="text-sm text-gray-500 dark:text-gray-400">Entenda como funcionam os níveis, badges e recompensas.</p>
                        </a>
                        <a className="group bg-white dark:bg-gray-800 p-8 rounded-3xl border border-[#dbe6e1] dark:border-gray-700 hover:border-primary dark:hover:border-primary transition-all hover:shadow-lg hover:-translate-y-1" href="#">
                            <div className="size-14 bg-background-light dark:bg-gray-700 rounded-2xl flex items-center justify-center text-primary mb-6 group-hover:scale-110 transition-transform">
                                <span className="material-symbols-outlined text-3xl">payments</span>
                            </div>
                            <h3 className="text-xl font-bold text-[#111815] dark:text-white mb-2">Financeiro</h3>
                            <p className="text-sm text-gray-500 dark:text-gray-400">Dúvidas sobre assinaturas, planos Pro e faturamento.</p>
                        </a>
                    </div>
                </section>

                <section className="bg-white dark:bg-gray-900 border-y border-[#dbe6e1] dark:border-gray-800 py-20">
                    <div className="max-w-[800px] mx-auto px-4">
                        <div className="text-center mb-12">
                            <h2 className="text-3xl font-black text-[#111815] dark:text-white mb-4">Fale Conosco</h2>
                            <p className="text-gray-500 dark:text-gray-400">Não encontrou o que procurava? Nossa equipe de especialistas está pronta para te ajudar a chegar no topo.</p>
                        </div>

                        <div className="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                             <div className="flex items-start gap-4 p-6 rounded-3xl bg-background-light dark:bg-gray-800 border border-[#dbe6e1] dark:border-gray-700 hover:border-primary transition-colors">
                                <div className="p-3 bg-white dark:bg-gray-700 rounded-2xl text-primary shadow-sm">
                                    <span className="material-symbols-outlined">mail</span>
                                </div>
                                <div>
                                    <h4 className="font-bold text-[#111815] dark:text-white text-lg">E-mail</h4>
                                    <p className="text-gray-500 dark:text-gray-400 text-sm mb-2">Para assuntos gerais e parcerias</p>
                                    <a href="mailto:suporte@everest.app" className="text-primary font-bold hover:underline">suporte@everest.app</a>
                                </div>
                            </div>
                            <div className="flex items-start gap-4 p-6 rounded-3xl bg-background-light dark:bg-gray-800 border border-[#dbe6e1] dark:border-gray-700 hover:border-primary transition-colors">
                                <div className="p-3 bg-white dark:bg-gray-700 rounded-2xl text-primary shadow-sm">
                                    <span className="material-symbols-outlined">folder_open</span>
                                </div>
                                <div>
                                    <h4 className="font-bold text-[#111815] dark:text-white text-lg">Meus Chamados</h4>
                                    <p className="text-gray-500 dark:text-gray-400 text-sm mb-2">Acompanhe suas solicitações</p>
                                    <Link href={route('support.my-tickets')} className="text-primary font-bold hover:underline">Acessar painel</Link>
                                </div>
                            </div>
                        </div>

                        <div className="bg-white dark:bg-gray-800 p-8 md:p-10 rounded-[2rem] border border-[#dbe6e1] dark:border-gray-700 shadow-xl relative overflow-hidden">
                             {flash?.success ? (
                                <div className="flex flex-col items-center justify-center py-10 text-center animate-fade-in">
                                    <div className="size-20 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-full flex items-center justify-center mb-6">
                                        <span className="material-symbols-outlined text-4xl">check_circle</span>
                                    </div>
                                    <h3 className="text-2xl font-black text-[#111815] dark:text-white mb-2">Mensagem enviada!</h3>
                                    <p className="text-gray-500 dark:text-gray-400 max-w-md mx-auto mb-8">
                                        Recebemos sua solicitação e nossa equipe entrará em contato em breve. Um protocolo foi enviado para o seu e-mail.
                                    </p>
                                    <button 
                                        onClick={() => window.location.reload()} 
                                        className="px-8 py-3 rounded-full bg-primary text-[#111815] font-bold shadow-lg shadow-primary/20 hover:scale-[1.02] transition-transform"
                                    >
                                        Enviar nova mensagem
                                    </button>
                                </div>
                            ) : (
                                <form onSubmit={submit} className="space-y-6">
                                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label className="block text-sm font-bold text-[#111815] dark:text-gray-300 mb-2" htmlFor="name">Seu Nome</label>
                                            <input
                                                type="text"
                                                id="name"
                                                className="w-full h-14 px-4 rounded-2xl bg-background-light dark:bg-background-dark border-transparent focus:border-primary focus:ring-primary text-base transition-all"
                                                value={data.name}
                                                onChange={(e) => setData('name', e.target.value)}
                                                placeholder="Digite seu nome completo"
                                            />
                                            {errors.name && <p className="text-red-500 text-xs mt-1">{errors.name}</p>}
                                        </div>

                                        <div>
                                            <label className="block text-sm font-bold text-[#111815] dark:text-gray-300 mb-2" htmlFor="email">Seu E-mail</label>
                                            <input
                                                type="email"
                                                id="email"
                                                className="w-full h-14 px-4 rounded-2xl bg-background-light dark:bg-background-dark border-transparent focus:border-primary focus:ring-primary text-base transition-all"
                                                value={data.email}
                                                onChange={(e) => setData('email', e.target.value)}
                                                placeholder="seu@email.com"
                                            />
                                            {errors.email && <p className="text-red-500 text-xs mt-1">{errors.email}</p>}
                                        </div>
                                    </div>

                                    <div>
                                        <label className="block text-sm font-bold text-[#111815] dark:text-gray-300 mb-2" htmlFor="subject">Assunto</label>
                                        <select
                                            id="subject"
                                            className="w-full h-14 rounded-2xl bg-background-light dark:bg-background-dark border-transparent focus:border-primary focus:ring-primary text-base transition-all"
                                            value={data.subject}
                                            onChange={(e) => setData('subject', e.target.value)}
                                        >
                                            <option value="Dúvida Técnica">Dúvida Técnica</option>
                                            <option value="Problemas com Pagamento">Problemas com Pagamento</option>
                                            <option value="Sugestão de Melhoria">Sugestão de Melhoria</option>
                                            <option value="Outros">Outros</option>
                                        </select>
                                        {errors.subject && <p className="text-red-500 text-xs mt-1">{errors.subject}</p>}
                                    </div>

                                    <div>
                                        <label className="block text-sm font-bold text-[#111815] dark:text-gray-300 mb-2" htmlFor="message">Sua Mensagem</label>
                                        <textarea
                                            id="message"
                                            className="w-full p-4 rounded-2xl bg-background-light dark:bg-background-dark border-transparent focus:border-primary focus:ring-primary text-base transition-all resize-none"
                                            rows={8}
                                            maxLength={5000}
                                            value={data.message}
                                            onChange={(e) => setData('message', e.target.value)}
                                            placeholder="Descreva detalhadamente como podemos te ajudar..."
                                        ></textarea>
                                        <div className="flex justify-end mt-1">
                                            <span className={`text-xs ${data.message.length >= 5000 ? 'text-red-500' : 'text-gray-400'}`}>
                                                {data.message.length}/5000
                                            </span>
                                        </div>
                                        {errors.message && <p className="text-red-500 text-xs mt-1">{errors.message}</p>}
                                    </div>

                                    <button
                                        className="w-full cursor-pointer items-center justify-center rounded-full h-14 px-8 bg-primary text-[#111815] text-lg font-bold shadow-xl shadow-primary/20 hover:scale-[1.01] transition-transform disabled:opacity-50 disabled:cursor-not-allowed"
                                        type="submit"
                                        disabled={processing}
                                    >
                                        {processing ? 'Enviando...' : 'Enviar Mensagem'}
                                    </button>
                                </form>
                            )}
                        </div>
                    </div>
                </section>
            </main>

            <Footer />
        </div>
    );
}
