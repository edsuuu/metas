import { Link, usePage } from "@inertiajs/react";
import React, { ReactNode } from "react";

declare function route(name: string, params?: any, absolute?: boolean): string;

interface LegalNavbarProps {
    children?: ReactNode;
}

export default function LegalNavbar({ children }: LegalNavbarProps) {
    const { auth } = usePage().props as any;
    const [isMenuOpen, setIsMenuOpen] = React.useState(false);

    return (
        <header className="w-full border-b border-solid border-[#dbe6e1] dark:border-gray-800 bg-white/80 dark:bg-background-dark/80 backdrop-blur-md px-4 md:px-20 lg:px-40 py-4 sticky top-0 z-50">
            <div className="max-w-[1200px] mx-auto flex items-center justify-between">
                <Link
                    href={route("home")}
                    className="flex items-center gap-3 hover:opacity-80 transition-opacity"
                >
                    <div className="size-8 text-primary">
                        <svg
                            fill="currentColor"
                            viewBox="0 0 48 48"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                clipRule="evenodd"
                                d="M24 4H42V17.3333V30.6667H24V44H6V30.6667V17.3333H24V4Z"
                                fillRule="evenodd"
                            ></path>
                        </svg>
                    </div>
                    <h2 className="text-[#111815] dark:text-white text-xl font-bold leading-tight tracking-tight">
                        Everest
                    </h2>
                </Link>

                <nav className="hidden md:flex items-center gap-9">
                    {!auth?.user && (
                        <Link
                            className="text-[#111815] dark:text-gray-300 text-sm font-medium hover:text-primary transition-colors"
                            href={route("pricing")}
                        >
                            Planos
                        </Link>
                    )}
                    {auth?.user && (
                        <Link
                            className="text-[#111815] dark:text-gray-300 text-sm font-medium hover:text-primary transition-colors"
                            href={route("achievements")}
                        >
                            Conquistas
                        </Link>
                    )}
                    <Link
                        className="text-[#111815] dark:text-gray-300 text-sm font-medium hover:text-primary transition-colors"
                        href={route("blog")}
                    >
                        Blog
                    </Link>
                    <Link
                        className="text-[#111815] dark:text-gray-300 text-sm font-medium hover:text-primary transition-colors"
                        href={route("support")}
                    >
                        Contato
                    </Link>
                </nav>

                <div className="hidden md:flex items-center gap-6">
                    {children ? (
                        children
                    ) : auth?.user ? (
                        <div className="flex items-center gap-3">
                            <Link
                                className="text-sm font-bold text-primary px-4 py-2 rounded-full border border-primary hover:bg-primary hover:text-white transition-all"
                                href={route("dashboard")}
                            >
                                Dashboard
                            </Link>
                            <Link
                                href={route("logout")}
                                method="post"
                                as="button"
                                className="flex cursor-pointer items-center justify-center rounded-full h-10 px-5 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-sm font-bold hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors"
                            >
                                Sair
                            </Link>
                        </div>
                    ) : (
                        <Link
                            className="text-sm font-bold text-primary px-4 py-2 rounded-full border border-primary hover:bg-primary hover:text-white transition-all"
                            href={route("dashboard")}
                        >
                            Ir para o App
                        </Link>
                    )}
                </div>

                {/* Mobile Menu Button */}
                <button
                    className="md:hidden p-2 text-gray-600 dark:text-gray-300"
                    onClick={() => setIsMenuOpen(!isMenuOpen)}
                >
                    <span className="material-symbols-outlined text-3xl">
                        {isMenuOpen ? "close" : "menu"}
                    </span>
                </button>
            </div>

            {/* Mobile Menu Overlay */}
            {isMenuOpen && (
                <div className="md:hidden absolute top-full left-0 w-full bg-white dark:bg-background-dark border-b border-[#dbe6e1] dark:border-gray-800 shadow-xl py-4 px-4 flex flex-col gap-4 animate-in slide-in-from-top-2">
                    {!auth?.user && (
                        <Link
                            className="text-[#111815] dark:text-gray-300 text-lg font-medium p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors"
                            href={route("pricing")}
                        >
                            Planos
                        </Link>
                    )}
                    {auth?.user && (
                        <Link
                            className="text-[#111815] dark:text-gray-300 text-lg font-medium p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors"
                            href={route("achievements")}
                        >
                            Conquistas
                        </Link>
                    )}
                    <Link
                        className="text-[#111815] dark:text-gray-300 text-lg font-medium p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors"
                        href={route("blog")}
                    >
                        Blog
                    </Link>
                    <Link
                        className="text-[#111815] dark:text-gray-300 text-lg font-medium p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors"
                        href={route("support")}
                    >
                        Contato
                    </Link>
                    <div className="h-px bg-gray-100 dark:bg-gray-800 my-2"></div>

                    {/* Render Children in Mobile Menu if feasible, or standard links */}
                    {/* For simplicity and robustness, showing standard auth links in mobile menu ensuring access */}

                    {auth?.user ? (
                        <>
                            <Link
                                href={route("dashboard")}
                                className="flex items-center justify-center rounded-xl h-12 bg-primary text-[#111815] text-lg font-bold shadow-lg shadow-primary/20"
                            >
                                Dashboard
                            </Link>

                            <Link
                                href={route("support.my-tickets")}
                                className="flex items-center justify-center rounded-xl h-12 bg-white dark:bg-gray-800 text-[#111815] dark:text-white text-lg font-bold border border-[#dbe6e1] dark:border-gray-700"
                            >
                                Meus Chamados
                            </Link>

                            <Link
                                href={route("logout")}
                                method="post"
                                as="button"
                                className="flex w-full items-center justify-center rounded-xl h-12 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-lg font-bold hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors"
                            >
                                Sair
                            </Link>
                        </>
                    ) : (
                        <>
                            <Link
                                href={route("login")}
                                className="flex items-center justify-center rounded-xl h-12 bg-background-light dark:bg-gray-800 text-[#111815] dark:text-white text-lg font-bold border border-[#dbe6e1] dark:border-gray-700"
                            >
                                Login
                            </Link>
                            <Link
                                href={route("register")}
                                className="flex items-center justify-center rounded-xl h-12 bg-primary text-[#111815] text-lg font-bold shadow-lg shadow-primary/20"
                            >
                                Crie sua conta
                            </Link>
                        </>
                    )}
                </div>
            )}
        </header>
    );
}
