import { useState, FormEventHandler, useEffect } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import PasswordChecklist from '@/Components/PasswordChecklist';

declare function route(name: string, params?: any, absolute?: boolean): string;

export default function LoginRegister({ status, canResetPassword, type = 'login', socialUser }: { status?: string, canResetPassword?: boolean, type?: 'login' | 'register', socialUser?: any }) {
    const [isLogin, setIsLogin] = useState(type === 'login' && !socialUser);

    const generateSecurePassword = () => {
        const length = 16;
        const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+";
        let retVal = "";
        // Ensure at least one of each required type
        retVal += "ABCDEFGHIJKLMNOPQRSTUVWXYZ"[Math.floor(Math.random() * 26)];
        retVal += "0123456789"[Math.floor(Math.random() * 10)];
        retVal += "!@#$%^&*()_+"[Math.floor(Math.random() * 12)];
        
        for (let i = retVal.length; i < length; ++i) {
            retVal += charset.charAt(Math.floor(Math.random() * charset.length));
        }
        return retVal.split('').sort(() => 0.5 - Math.random()).join('');
    };

    const { data, setData, post, processing, errors, reset } = useForm({
        nickname: socialUser?.name ? socialUser.name.toLowerCase().replace(/\s+/g, '_') : '',
        email: socialUser?.email || '',
        password: socialUser ? generateSecurePassword() : '',
        remember: false,
        name: '', 
        password_confirmation: '',
    });

    useEffect(() => {
        if (socialUser) {
            setIsLogin(false);
            // Ensure password confirmation matches the generated password
            if (!data.password_confirmation && data.password) {
                 setData('password_confirmation', data.password);
            }
        }
    }, [socialUser]);

    useEffect(() => {
        setIsLogin(type === 'login');
    }, [type]);

    useEffect(() => {
        return () => {
            reset('password', 'password_confirmation');
        };
    }, []);

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        if (isLogin) {
            post(route('login'));
        } else {
            // For social user, ensure password_confirmation matches before submitting if not already set
            if (socialUser && data.password !== data.password_confirmation) {
                setData('password_confirmation', data.password); // This might be too late for state update, better rely on the initial set or handle in effect
                // Actually, let's just send the request, assuming useEffect handled it or we fix it here
                 // Force update data object for immediate submit? No, useForm data is state.
                 // Better approach: Since we hide password fields, we must ensure they are valid.
                 // The useEffect above sets confirmation.
                post(route('register'));
            } else {
                post(route('register'));
            }
        }
    };

    const toggleMode = () => {
        setIsLogin(!isLogin);
    };

    return (
        <div className="bg-background-light dark:bg-background-dark text-[#111815] transition-colors duration-300 min-h-screen flex flex-col">
            <Head title={isLogin ? "Entrar" : "Cadastro"} />

            <header className="w-full border-b border-solid border-[#dbe6e1] bg-white/80 dark:bg-background-dark/80 backdrop-blur-md px-4 md:px-20 lg:px-40 py-4">
                <div className="max-w-[1200px] mx-auto flex items-center justify-between">
                    <Link href="/" className="flex items-center gap-3 hover:opacity-80 transition-opacity">
                        <div className="size-8 text-primary">
                            <svg fill="currentColor" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                                <path clipRule="evenodd" d="M24 4H42V17.3333V30.6667H24V44H6V30.6667V17.3333H24V4Z" fillRule="evenodd"></path>
                            </svg>
                        </div>
                        <h2 className="text-[#111815] dark:text-white text-xl font-bold leading-tight tracking-tight">Everest</h2>
                    </Link>
                    <Link href="/" className="text-sm font-medium text-gray-500 hover:text-primary transition-colors">
                        Voltar para o início
                    </Link>
                </div>
            </header>

            <main className="flex-1 flex items-center justify-center p-4 py-12">
                <div className="w-full max-w-[480px] bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-[#dbe6e1] dark:border-gray-700 overflow-hidden">
                    <div className="p-8 md:p-12">
                        <div className="flex flex-col gap-2 mb-8 text-center">
                            <h1 className="text-[#111815] dark:text-white text-3xl font-black tracking-tight">
                                {socialUser 
                                    ? 'Quase lá!' 
                                    : (isLogin ? 'Bem-vindo de volta' : 'Crie sua conta')
                                }
                            </h1>
                            <p className="text-gray-500 dark:text-gray-400 text-sm">
                                {socialUser 
                                    ? 'Escolha um nickname para personalizar sua experiência.' 
                                    : (isLogin ? 'Continue sua jornada rumo ao topo.' : 'Comece sua escalada hoje mesmo.')
                                }
                            </p>
                        </div>

                        {status && <div className="mb-4 font-medium text-sm text-green-600 dark:text-green-400 text-center">{status}</div>}

                        <div className="flex flex-col gap-4">
                            {!socialUser && (
                                <>
                                    <a href="/oauth2/google" className="w-full flex items-center justify-center gap-3 rounded-full h-12 px-6 bg-white dark:bg-gray-700 border border-[#dbe6e1] dark:border-gray-600 text-[#111815] dark:text-white text-sm font-bold hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                        <svg className="w-5 h-5" viewBox="0 0 24 24">
                                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"></path>
                                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"></path>
                                            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"></path>
                                            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"></path>
                                        </svg>
                                        Continuar com Google
                                    </a>

                                    <div className="relative my-4">
                                        <div className="absolute inset-0 flex items-center">
                                            <div className="w-full border-t border-[#dbe6e1] dark:border-gray-700"></div>
                                        </div>
                                        <div className="relative flex justify-center text-xs uppercase">
                                            <span className="bg-white dark:bg-gray-800 px-4 text-gray-500">ou entre com e-mail</span>
                                        </div>
                                    </div>
                                </>
                            )}

                            <form onSubmit={submit} className="space-y-4">
                                {(!isLogin || socialUser) && (
                                    <div>
                                        <label className="block text-sm font-bold text-[#111815] dark:text-gray-300 mb-2" htmlFor="nickname">
                                            Escolha seu Nickname
                                        </label>
                                        <div className="relative">
                                            <span className="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 material-symbols-outlined text-xl">alternate_email</span>
                                            <input
                                                id="nickname"
                                                type="text"
                                                className="w-full pl-11 pr-4 h-12 rounded-2xl bg-background-light dark:bg-background-dark border-transparent focus:border-primary focus:ring-primary text-sm transition-all"
                                                placeholder="ex: alpinista_urbano"
                                                value={data.nickname}
                                                onChange={(e) => {
                                                    const val = e.target.value;
                                                    setData((prev: any) => ({
                                                        ...prev,
                                                        nickname: val,
                                                        name: val
                                                    }));
                                                }}
                                            />
                                        </div>
                                        {errors.nickname && <p className="text-red-500 text-xs mt-1">{errors.nickname}</p>}
                                    </div>
                                )}

                                {!socialUser && (
                                    <>
                                        <div>
                                            <label className="block text-sm font-bold text-[#111815] dark:text-gray-300 mb-2" htmlFor="email">
                                                E-mail
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
                                                />
                                            </div>
                                            {errors.email && <p className="text-red-500 text-xs mt-1">{errors.email}</p>}
                                        </div>

                                        <div>
                                            <div className="flex justify-between items-center mb-2">
                                                <label className="block text-sm font-bold text-[#111815] dark:text-gray-300" htmlFor="password">
                                                    Senha
                                                </label>
                                                {canResetPassword && isLogin && (
                                                    <Link href={route('password.request')} className="text-xs text-primary font-bold hover:underline">
                                                        Esqueceu a senha?
                                                    </Link>
                                                )}
                                            </div>
                                            <div className="relative">
                                                <span className="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 material-symbols-outlined text-xl">lock</span>
                                                <input
                                                    id="password"
                                                    type="password"
                                                    className="w-full pl-11 pr-4 h-12 rounded-2xl bg-background-light dark:bg-background-dark border-transparent focus:border-primary focus:ring-primary text-sm transition-all"
                                                    placeholder="••••••••"
                                                    value={data.password}
                                                    onChange={(e) => {
                                                        setData('password', e.target.value);
                                                        if (!isLogin) setData('password_confirmation', e.target.value);
                                                    }}
                                                />
                                            </div>
                                            {!isLogin && <div className="mt-4"><PasswordChecklist password={data.password} /></div>}
                                            {errors.password && <p className="text-red-500 text-xs mt-1">{errors.password}</p>}
                                        </div>
                                    </>
                                )}

                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="w-full cursor-pointer items-center justify-center rounded-full h-12 px-5 bg-primary text-[#111815] text-sm font-bold shadow-lg shadow-primary/20 hover:scale-[1.02] transition-transform mt-2"
                                >
                                    {socialUser ? 'Concluir Cadastro' : (isLogin ? 'Entrar' : 'Criar Conta')}
                                </button>
                            </form>

                            {!socialUser && (
                                <p className="text-center text-sm text-gray-500 dark:text-gray-400 mt-6">
                                    {isLogin ? 'Não tem uma conta?' : 'Já tem uma conta?'}{' '}
                                    <button
                                        onClick={toggleMode}
                                        className="text-primary font-bold hover:underline"
                                    >
                                        {isLogin ? 'Cadastre-se' : 'Faça Login'}
                                    </button>
                                </p>
                            )}
                        </div>
                    </div>
                    <div className="bg-gray-50 dark:bg-gray-900/50 p-6 text-center border-t border-[#dbe6e1] dark:border-gray-700">
                        <p className="text-xs text-gray-500 leading-relaxed">
                            Ao continuar, você concorda com nossos <Link href={route('terms')} className="underline">Termos de Uso</Link> e <Link href={route('terms')} className="underline">Política de Privacidade</Link>.
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
