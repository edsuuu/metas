import React from "react";
import { Link } from "@inertiajs/react";

declare function route(name: string, params?: any, absolute?: boolean): string;

interface PublicNavbarProps {
    auth: {
        user: any;
    };
}

export default function PublicNavbar({ auth }: PublicNavbarProps) {
    const [isMenuOpen, setIsMenuOpen] = React.useState(false);

    return (
        <header className="sticky top-0 z-50 w-full border-b border-solid border-[#dbe6e1] bg-white/80 dark:bg-background-dark/80 backdrop-blur-md px-4 md:px-20 lg:px-40 py-3">
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

                {/* Desktop Navigation */}
                <nav className="hidden md:flex items-center gap-9">
                    {!auth.user && (
                        <Link
                            className="text-[#111815] dark:text-gray-300 text-sm font-medium hover:text-primary transition-colors"
                            href={route("pricing")}
                        >
                            Planos
                        </Link>
                    )}
                    {auth.user && (
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
                        href={route("support.index")}
                    >
                        Contato
                    </Link>
                </nav>

                {/* Desktop Actions */}
                <div className="hidden md:flex items-center gap-3">
                    {auth.user ? (
                        <>
                            <Link
                                href={route("dashboard")}
                                className="flex min-w-[100px] cursor-pointer items-center justify-center rounded-full h-10 px-5 bg-primary text-[#111815] text-sm font-bold shadow-lg shadow-primary/20 hover:scale-105 transition-transform"
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
                        </>
                    ) : (
                        <>
                            <Link
                                href={route("login")}
                                className="hidden sm:flex min-w-[84px] cursor-pointer items-center justify-center rounded-full h-10 px-5 bg-background-light dark:bg-gray-800 text-[#111815] dark:text-white text-sm font-bold border border-[#dbe6e1] dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                            >
                                Login
                            </Link>
                            <Link
                                href={route("register")}
                                className="flex min-w-[100px] cursor-pointer items-center justify-center rounded-full h-10 px-5 bg-primary text-[#111815] text-sm font-bold shadow-lg shadow-primary/20 hover:scale-105 transition-transform"
                            >
                                Crie sua conta
                            </Link>
                        </>
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
                    {!auth.user && (
                        <Link
                            className="text-[#111815] dark:text-gray-300 text-lg font-medium p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors"
                            href={route("pricing")}
                        >
                            Planos
                        </Link>
                    )}
                    {auth.user && (
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
                        href={route("support.index")}
                    >
                        Contato
                    </Link>
                    <div className="h-px bg-gray-100 dark:bg-gray-800 my-2"></div>
                    {auth.user ? (
                        <>
                            <Link
                                href={route("dashboard")}
                                className="flex items-center justify-center rounded-xl h-12 bg-primary text-[#111815] text-lg font-bold shadow-lg shadow-primary/20"
                            >
                                Dashboard
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
