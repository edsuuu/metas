import React, { FormEventHandler } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import LegalNavbar from '@/Components/LegalNavbar';

declare function route(name: string, params?: any, absolute?: boolean): string;

interface VerifyAccessProps {
    email: string;
}

export default function VerifyAccess({ email }: VerifyAccessProps) {
    const { data, setData, post, processing, errors } = useForm({
        code: '',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('support.verify.check'));
    };

    return (
        <div className="bg-background-light dark:bg-background-dark text-[#111815] transition-colors duration-300 min-h-screen flex flex-col font-display">
            <Head title="Everest - Verificar Acesso" />
            
            <LegalNavbar>
                 <Link className="px-5 py-2 rounded-full border-2 border-primary text-primary hover:bg-primary hover:text-[#111815] text-sm font-bold transition-all" href={route('support')}>Central de Ajuda</Link>
            </LegalNavbar>

            <main className="flex-1 flex flex-col items-center justify-center p-4">
                 <div className="w-full max-w-md bg-white dark:bg-gray-800 rounded-[2rem] border border-[#dbe6e1] dark:border-gray-700 shadow-xl p-8 md:p-12 text-center">
                    <div className="size-16 bg-primary/10 text-primary rounded-full flex items-center justify-center mx-auto mb-6">
                        <span className="material-symbols-outlined text-3xl">lock_open</span>
                    </div>
                    
                    <h1 className="text-2xl md:text-3xl font-black text-[#111815] dark:text-white mb-4">Verifique seu acesso</h1>
                    
                    <p className="text-gray-600 dark:text-gray-400 mb-8">
                        Enviamos um código de acesso para <strong>{email}</strong>. Digite-o abaixo para ver seus chamados.
                    </p>

                    <form onSubmit={submit} className="space-y-6">
                        <div>
                             <input 
                                className="w-full h-16 text-center text-3xl font-black tracking-[0.5em] rounded-2xl bg-background-light dark:bg-background-dark border-2 border-[#dbe6e1] dark:border-gray-700 focus:border-primary focus:ring-0 transition-all outline-none" 
                                placeholder="000000" 
                                maxLength={6}
                                type="text"
                                value={data.code}
                                onChange={(e) => setData('code', e.target.value.replace(/\D/g, ''))}
                            />
                            {errors.code && <p className="text-red-500 text-sm mt-2">{errors.code}</p>}
                        </div>

                        <button 
                            className="w-full h-14 rounded-full bg-primary text-[#111815] text-base font-bold shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-transform disabled:opacity-50" 
                            type="submit"
                            disabled={processing}
                        >
                            {processing ? 'Verificando...' : 'Confirmar Acesso'}
                        </button>
                    </form>
                    
                    <p className="mt-8 text-sm text-gray-500 dark:text-gray-400">
                        Não recebeu o código? <Link href={route('support.my-tickets')} className="text-primary font-bold hover:underline">Tentar novamente</Link>
                    </p>
                </div>
            </main>

            <footer className="w-full py-8 text-center text-xs text-gray-400">
                © 2024 Everest Technologies Inc.
            </footer>
        </div>
    );
}
