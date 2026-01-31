import React from 'react';

interface Requirement {
    label: string;
    met: boolean;
}

interface PasswordChecklistProps {
    password: string;
}

export default function PasswordChecklist({ password }: PasswordChecklistProps) {
    const requirements: Requirement[] = [
        {
            label: 'No mínimo 8 caracteres',
            met: password.length >= 8,
        },
        {
            label: 'Pelo menos um número ou símbolo',
            met: /[0-9\W_]/.test(password),
        },
        {
            label: 'Letras maiúsculas e minúsculas',
            met: /[a-z]/.test(password) && /[A-Z]/.test(password),
        },
    ];

    return (
        <div className="bg-gray-50 dark:bg-gray-900/40 p-4 rounded-2xl border border-[#dbe6e1] dark:border-gray-700/50">
            <p className="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Requisitos de senha:</p>
            <ul className="space-y-2">
                {requirements.map((req, index) => (
                    <li key={index} className={`flex items-center gap-2 text-xs transition-colors duration-200 ${req.met ? 'text-primary' : 'text-gray-400 dark:text-gray-500'}`}>
                        <span className="material-symbols-outlined text-sm">
                            {req.met ? 'check_circle' : 'radio_button_unchecked'}
                        </span>
                        {req.label}
                    </li>
                ))}
            </ul>
        </div>
    );
}
