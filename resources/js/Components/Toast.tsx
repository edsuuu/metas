import { Transition } from '@headlessui/react';
import { usePage } from '@inertiajs/react';
import { useEffect, useState } from 'react';

export default function Toast() {
    const { flash } = usePage().props as any;
    const [show, setShow] = useState(false);
    const [message, setMessage] = useState('');
    const [type, setType] = useState<'success' | 'error' | 'warning' | 'info'>('success');

    useEffect(() => {
        if (flash?.success) {
            setMessage(flash.success);
            setType('success');
            setShow(true);
        } else if (flash?.error) {
            setMessage(flash.error);
            setType('error');
            setShow(true);
        } else if (flash?.warning) {
            setMessage(flash.warning);
            setType('warning');
            setShow(true);
        } else if (flash?.message) {
            setMessage(flash.message);
            setType('info');
            setShow(true);
        }
    }, [flash]);

    useEffect(() => {
        const handleCustomToast = (event: CustomEvent) => {
            setMessage(event.detail.message);
            setType(event.detail.type || 'success');
            setShow(true);
        };

        window.addEventListener('show-toast' as any, handleCustomToast);

        return () => {
            window.removeEventListener('show-toast' as any, handleCustomToast);
        };
    }, []);

    useEffect(() => {
        if (show) {
            const timer = setTimeout(() => {
                setShow(false);
            }, 3000);
            return () => clearTimeout(timer);
        }
    }, [show]);

    const colors = {
        success: 'bg-green-500 text-white',
        error: 'bg-red-500 text-white',
        warning: 'bg-orange-500 text-white',
        info: 'bg-blue-500 text-white',
    };

    const icons = {
        success: 'check_circle',
        error: 'error',
        warning: 'warning',
        info: 'info',
    };

    return (
        <Transition
            as="div"
            show={show}
            enter="transition ease-out duration-300 transform"
            enterFrom="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
            enterTo="translate-y-0 opacity-100 sm:translate-x-0"
            leave="transition ease-in duration-100"
            leaveFrom="opacity-100"
            leaveTo="opacity-0"
            className="fixed bottom-4 right-4 z-[100] max-w-sm w-full"
        >
            <div className={`rounded-xl shadow-lg p-4 flex items-center gap-3 ${colors[type]}`}>
                <span className="material-symbols-outlined">{icons[type]}</span>
                <p className="font-bold text-sm">{message}</p>
                <button 
                    onClick={() => setShow(false)} 
                    className="ml-auto hover:bg-white/20 rounded-full p-1 transition-colors"
                >
                    <span className="material-symbols-outlined text-sm">close</span>
                </button>
            </div>
        </Transition>
    );
}
