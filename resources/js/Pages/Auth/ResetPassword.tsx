import { Head, useForm, Link } from '@inertiajs/react';
import { FormEventHandler, useEffect } from 'react';

export default function ResetPassword({ token, email }: { token: string, email: string }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        token: token,
        email: email,
        password: '',
        password_confirmation: '',
    });

    useEffect(() => {
        return () => {
            reset('password', 'password_confirmation');
        };
    }, []);

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('password.store'));
    };

    return (
        <div className="bg-background-light dark:bg-background-dark text-[#111815] transition-colors duration-300 min-h-screen flex flex-col">
            <Head title="Redefinir Senha" />

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
                    <Link className="text-sm font-medium text-gray-500 hover:text-primary transition-colors flex items-center gap-1" href={route('login')}>
                        <span className="material-symbols-outlined text-base">arrow_back</span>
                        Voltar para o login
                    </Link>
                </div>
            </header>

            <main className="flex-1 flex items-center justify-center p-4 py-12">
                <div className="w-full max-w-[480px] bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-[#dbe6e1] dark:border-gray-700 overflow-hidden">
                    <div className="p-8 md:p-12">
                        <div className="flex flex-col gap-2 mb-8 text-center">
                            <h1 className="text-[#111815] dark:text-white text-3xl font-black tracking-tight">Crie uma nova senha</h1>
                            <p className="text-gray-500 dark:text-gray-400 text-sm">Escolha uma senha forte para proteger seu progresso.</p>
                        </div>

                        <form onSubmit={submit} className="space-y-6">
                            <div>
                                <label className="block text-sm font-bold text-[#111815] dark:text-gray-300 mb-2" htmlFor="email">
                                    Email
                                </label>
                                <div className="relative">
                                     <span className="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 material-symbols-outlined text-xl">mail</span>
                                    <input 
                                        id="email" 
                                        type="email" 
                                        className="w-full pl-11 pr-4 h-12 rounded-2xl bg-background-light dark:bg-background-dark border-transparent focus:border-primary focus:ring-primary text-sm transition-all" 
                                        value={data.email}
                                        onChange={(e) => setData('email', e.target.value)}
                                        required
                                        readOnly
                                    />
                                </div>
                                {errors.email && <p className="text-red-500 text-xs mt-1">{errors.email}</p>}
                            </div>

                            <div>
                                <label className="block text-sm font-bold text-[#111815] dark:text-gray-300 mb-2" htmlFor="password">
                                    Nova senha
                                </label>
                                <div className="relative">
                                    <span className="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 material-symbols-outlined text-xl">lock</span>
                                    <input 
                                        id="password" 
                                        type="password" 
                                        className="w-full pl-11 pr-4 h-12 rounded-2xl bg-background-light dark:bg-background-dark border-transparent focus:border-primary focus:ring-primary text-sm transition-all" 
                                        placeholder="••••••••" 
                                        value={data.password}
                                        onChange={(e) => setData('password', e.target.value)}
                                        required 
                                    />
                                </div>
                                {errors.password && <p className="text-red-500 text-xs mt-1">{errors.password}</p>}
                            </div>

                            <div>
                                <label className="block text-sm font-bold text-[#111815] dark:text-gray-300 mb-2" htmlFor="password_confirmation">
                                    Confirmar nova senha
                                </label>
                                <div className="relative">
                                    <span className="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 material-symbols-outlined text-xl">lock_reset</span>
                                    <input 
                                        id="password_confirmation" 
                                        type="password" 
                                        className="w-full pl-11 pr-4 h-12 rounded-2xl bg-background-light dark:bg-background-dark border-transparent focus:border-primary focus:ring-primary text-sm transition-all" 
                                        placeholder="••••••••" 
                                        value={data.password_confirmation}
                                        onChange={(e) => setData('password_confirmation', e.target.value)}
                                        required 
                                    />
                                </div>
                                {errors.password_confirmation && <p className="text-red-500 text-xs mt-1">{errors.password_confirmation}</p>}
                            </div>

                            <div className="bg-gray-50 dark:bg-gray-900/40 p-4 rounded-2xl border border-[#dbe6e1] dark:border-gray-700/50">
                                <p className="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Requisitos de senha:</p>
                                <ul className="space-y-2">
                                    <li className="flex items-center gap-2 text-xs text-gray-600 dark:text-gray-400">
                                        <span className="material-symbols-outlined text-primary text-sm">check_circle</span>
                                        No mínimo 8 caracteres
                                    </li>
                                    <li className="flex items-center gap-2 text-xs text-gray-400 dark:text-gray-500">
                                        <span className="material-symbols-outlined text-sm">radio_button_unchecked</span>
                                        Pelo menos um número ou símbolo
                                    </li>
                                    <li className="flex items-center gap-2 text-xs text-gray-400 dark:text-gray-500">
                                        <span className="material-symbols-outlined text-sm">radio_button_unchecked</span>
                                        Letras maiúsculas e minúsculas
                                    </li>
                                </ul>
                            </div>
                            <button 
                                type="submit" 
                                disabled={processing}
                                className="w-full cursor-pointer items-center justify-center rounded-full h-12 px-5 bg-primary text-[#111815] text-sm font-bold shadow-lg shadow-primary/20 hover:scale-[1.02] transition-transform mt-2"
                            >
                                Atualizar senha
                            </button>
                        </form>
                        <p className="text-center text-sm text-gray-500 dark:text-gray-400 mt-8">
                            Teve problemas? <a className="text-primary font-bold hover:underline" href="#">Contate o suporte</a>
                        </p>
                    </div>
                    <div className="bg-gray-50 dark:bg-gray-900/50 p-6 text-center border-t border-[#dbe6e1] dark:border-gray-700">
                        <p className="text-xs text-gray-500 leading-relaxed">
                            Sua segurança é nossa prioridade. Recomendamos não reutilizar senhas de outros sites.
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
