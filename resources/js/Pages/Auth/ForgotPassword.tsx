import { Head, useForm, Link } from '@inertiajs/react';
import { FormEventHandler } from 'react';

export default function ForgotPassword({ status }: { status?: string }) {
    const { data, setData, post, processing, errors } = useForm({
        email: '',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('password.email'));
    };

    return (
        <div className="bg-background-light dark:bg-background-dark text-[#111815] transition-colors duration-300 min-h-screen flex flex-col">
            <Head title="Esqueci minha senha" />
            
            <header className="w-full border-b border-solid border-[#dbe6e1] bg-white/80 dark:bg-background-dark/80 backdrop-blur-md px-4 md:px-20 lg:px-40 py-4">
                <div className="max-w-[1200px] mx-auto flex items-center justify-between">
                    <div className="flex items-center gap-3">
                        <div className="size-8 text-primary">
                            <svg fill="currentColor" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                                <path clipRule="evenodd" d="M24 4H42V17.3333V30.6667H24V44H6V30.6667V17.3333H24V4Z" fillRule="evenodd"></path>
                            </svg>
                        </div>
                        <h2 className="text-[#111815] dark:text-white text-xl font-bold leading-tight tracking-tight">Everest</h2>
                    </div>
                    <Link className="text-sm font-medium text-gray-500 hover:text-primary transition-colors" href={route('login')}>Voltar para a home</Link>
                </div>
            </header>

            <main className="flex-1 flex items-center justify-center p-4 py-12">
                <div className="w-full max-w-[480px] bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-[#dbe6e1] dark:border-gray-700 overflow-hidden">
                    <div className="p-8 md:p-12">
                        <div className="flex flex-col gap-2 mb-8 text-center">
                            <div className="mx-auto size-16 bg-primary/10 rounded-full flex items-center justify-center mb-4">
                                <span className="material-symbols-outlined text-primary text-3xl">lock_reset</span>
                            </div>
                            <h1 className="text-[#111815] dark:text-white text-3xl font-black tracking-tight">Recupere seu acesso</h1>
                            <p className="text-gray-500 dark:text-gray-400 text-sm">Digite seu e-mail para receber um link de redefinição.</p>
                        </div>

                        {status && <div className="mb-4 font-medium text-sm text-green-600 dark:text-green-400 text-center">{status}</div>}

                        <form onSubmit={submit} className="space-y-6">
                            <div>
                                <label className="block text-sm font-bold text-[#111815] dark:text-gray-300 mb-2" htmlFor="email">
                                    E-mail de recuperação
                                </label>
                                <div className="relative">
                                    <span className="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 material-symbols-outlined text-xl">mail</span>
                                    <input 
                                        id="email" 
                                        type="email" 
                                        className="w-full pl-11 pr-4 h-12 rounded-2xl bg-background-light dark:bg-background-dark border-transparent focus:border-primary focus:ring-primary text-sm transition-all" 
                                        placeholder="seu@email.com" 
                                        value={data.email}
                                        onChange={(e) => setData('email', e.target.value)}
                                        required 
                                    />
                                </div>
                                {errors.email && <p className="text-red-500 text-xs mt-1">{errors.email}</p>}
                            </div>
                            <button 
                                type="submit" 
                                disabled={processing}
                                className="w-full cursor-pointer items-center justify-center rounded-full h-12 px-5 bg-primary text-[#111815] text-sm font-bold shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all"
                            >
                                Enviar link de recuperação
                            </button>
                        </form>
                        <div className="mt-8 text-center">
                            <Link className="inline-flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 font-bold hover:text-primary transition-colors" href={route('login')}>
                                <span className="material-symbols-outlined text-lg">arrow_back</span>
                                Voltar para o login
                            </Link>
                        </div>
                    </div>
                    <div className="bg-gray-50 dark:bg-gray-900/50 p-6 text-center border-t border-[#dbe6e1] dark:border-gray-700">
                        <p className="text-xs text-gray-500 leading-relaxed">
                            Precisa de ajuda? Entre em contato com nosso <a className="underline" href="#">Suporte</a>.
                        </p>
                    </div>
                </div>
            </main>
            <footer className="w-full py-8 px-4 text-center">
                <p className="text-xs text-gray-400">© 2024 Everest Technologies Inc. Todos os direitos reservados.</p>
            </footer>
        </div>
    );
}
