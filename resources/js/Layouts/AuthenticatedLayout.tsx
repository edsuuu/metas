import { PropsWithChildren, ReactNode, useEffect, useState } from "react";
import { Link, usePage } from "@inertiajs/react";
import { User } from "@/types";
import Dropdown from "@/Components/Dropdown";
import Footer from "@/Components/Footer";
import Toast from "@/Components/Toast";
import axios from "axios";

declare function route(name?: string, params?: any, absolute?: boolean): any;

export default function Authenticated({
    children,
}: PropsWithChildren<{ header?: ReactNode }>) {
    const { auth } = usePage().props as any;
    const user = auth.user as User;
    const initialPendingCount = usePage().props.pendingRequestsCount as number;
    const [pendingRequestsCount, setPendingRequestsCount] =
        useState(initialPendingCount);

    const [showingNavigationDropdown, setShowingNavigationDropdown] =
        useState(false);

    useEffect(() => {
        setPendingRequestsCount(initialPendingCount);
    }, [initialPendingCount]);

    useEffect(() => {
        const interval = setInterval(() => {
            axios
                .get(route("social.status"))
                .then((response) => {
                    setPendingRequestsCount(response.data.pending_requests);
                })
                .catch((error) => console.error("Polling Error", error));
        }, 3000);

        return () => clearInterval(interval);
    }, []);

    // XP Logic moved to Dashboard

    return (
        <div className="bg-background-light dark:bg-background-dark text-[#111815] transition-colors duration-300 min-h-screen flex flex-col">
            <header className="sticky top-0 z-50 w-full border-b border-solid border-[#dbe6e1] bg-white dark:bg-background-dark px-4 md:px-20 lg:px-40 pt-4 pb-2">
                <div className="max-w-[1200px] mx-auto space-y-4">
                    <div className="flex items-center justify-between">
                        <div className="flex items-center gap-3">
                            <Link
                                href={route("dashboard")}
                                className="size-8 text-primary"
                            >
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
                            </Link>
                            <Link href={route("dashboard")}>
                                <h2 className="text-[#111815] dark:text-white text-xl font-bold leading-tight tracking-tight">
                                    Everest
                                </h2>
                            </Link>
                        </div>

                        {/* Desktop Navigation */}
                        <nav className="hidden md:flex items-center gap-9">
                            <Link
                                href={route("dashboard")}
                                className={`text-sm font-bold border-b-2 transition-colors ${route().current("dashboard") ? "text-primary border-primary" : "text-[#111815] dark:text-gray-300 border-transparent hover:text-primary"}`}
                            >
                                Dashboard
                            </Link>
                            <Link
                                href={route("goals.index")}
                                className={`text-sm font-bold border-b-2 transition-colors ${route().current("goals.*") ? "text-primary border-primary" : "text-[#111815] dark:text-gray-300 border-transparent hover:text-primary"}`}
                            >
                                Metas
                            </Link>

                            <Link
                                href={route("achievements")}
                                className={`text-sm font-bold border-b-2 transition-colors ${route().current("achievements") ? "text-primary border-primary" : "text-[#111815] dark:text-gray-300 border-transparent hover:text-primary"}`}
                            >
                                Conquistas
                            </Link>
                            <Link
                                href={route("social.feed")}
                                className={`text-sm font-bold border-b-2 transition-colors ${route().current("social.feed") ? "text-primary border-primary" : "text-[#111815] dark:text-gray-300 border-transparent hover:text-primary"}`}
                            >
                                Social
                            </Link>
                            <Link
                                href={route("social.index")}
                                className={`text-sm font-bold border-b-2 transition-colors relative ${route().current("social.index") ? "text-primary border-primary" : "text-[#111815] dark:text-gray-300 border-transparent hover:text-primary"}`}
                            >
                                Explorar
                                {pendingRequestsCount > 0 && (
                                    <span className="absolute -right-2 -top-1 size-2 bg-red-500 rounded-full animate-pulse"></span>
                                )}
                            </Link>
                            <Link
                                href={route("support.index")}
                                className={`text-sm font-bold border-b-2 transition-colors ${route().current("support") || route().current("support.*") ? "text-primary border-primary" : "text-[#111815] dark:text-gray-300 border-transparent hover:text-primary"}`}
                            >
                                Suporte
                            </Link>
                        </nav>

                        <div className="flex items-center gap-1">
                            {/* Streak Badge - Hidden on very small screens if needed, otherwise kept as is */}
                            {(() => {
                                const streakCount =
                                    (usePage().props.streak as number) || 0;
                                const hasStreak = streakCount > 0;
                                return (
                                    <div
                                        className={`flex items-center gap-2 px-3 py-1.5 rounded-full border transition-colors ${
                                            hasStreak
                                                ? "bg-orange-50 dark:bg-orange-950/30 border-orange-100 dark:border-orange-900/50"
                                                : "bg-gray-100 dark:bg-gray-800 border-gray-200 dark:border-gray-700"
                                        }`}
                                    >
                                        <span
                                            className={`material-symbols-outlined text-xl leading-none ${hasStreak ? "icon-gradient-fire streak-fire text-orange-500" : "text-gray-400"}`}
                                            style={{
                                                fontVariationSettings:
                                                    "'FILL' 1",
                                            }}
                                        >
                                            local_fire_department
                                        </span>
                                        <span
                                            className={`font-extrabold text-sm ${hasStreak ? "text-orange-600 dark:text-orange-400" : "text-gray-500 dark:text-gray-400"}`}
                                        >
                                            {streakCount}{" "}
                                            {streakCount <= 1 ? "Dia" : "Dias"}
                                        </span>
                                    </div>
                                );
                            })()}

                            <Dropdown>
                                <Dropdown.Trigger>
                                    <div className="hidden md:flex size-10 rounded-full bg-gray-200 dark:bg-gray-800 border-2 border-primary overflow-hidden cursor-pointer">
                                        {user.avatar_url ? (
                                            <img
                                                src={user.avatar_url}
                                                alt={user.name}
                                                className="w-full h-full object-cover"
                                            />
                                        ) : (
                                            <div className="w-full h-full flex items-center justify-center bg-primary text-[#111815] font-bold text-lg">
                                                {user.name
                                                    .charAt(0)
                                                    .toUpperCase()}
                                            </div>
                                        )}
                                    </div>
                                </Dropdown.Trigger>
                                <Dropdown.Content align="right" width="48">
                                    <div className="px-4 py-3 border-b border-gray-100 dark:border-gray-600 mb-1">
                                        <p className="text-sm font-bold text-gray-900 dark:text-white truncate">
                                            {user.name}
                                        </p>
                                        <p className="text-xs text-gray-500 truncate">
                                            {user.email}
                                        </p>
                                    </div>
                                    <Dropdown.Link
                                        href={route("social.profile")}
                                    >
                                        Perfil
                                    </Dropdown.Link>
                                    {!(
                                        user.roles?.includes("Administrador") ||
                                        user.roles?.includes("Suporte")
                                    ) && (
                                        <Dropdown.Link
                                            href={route("support.my-tickets")}
                                        >
                                            Meus Chamados
                                        </Dropdown.Link>
                                    )}
                                    {(user.roles?.includes("Administrador") ||
                                        user.roles?.includes("Suporte")) && (
                                        <Dropdown.Link
                                            href={route("admin.dashboard")}
                                        >
                                            Painel Admin
                                        </Dropdown.Link>
                                    )}
                                    <Dropdown.Link
                                        href={route("logout")}
                                        method="post"
                                        as="button"
                                    >
                                        Sair
                                    </Dropdown.Link>
                                </Dropdown.Content>
                            </Dropdown>

                            {/* Hamburger Menu Button */}
                            <div className="-mr-2 flex items-center md:hidden">
                                <button
                                    onClick={() =>
                                        setShowingNavigationDropdown(
                                            (previousState) => !previousState,
                                        )
                                    }
                                    className="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out"
                                >
                                    <svg
                                        className="h-6 w-6"
                                        stroke="currentColor"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            className={
                                                !showingNavigationDropdown
                                                    ? "inline-flex"
                                                    : "hidden"
                                            }
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            strokeWidth="2"
                                            d="M4 6h16M4 12h16M4 18h16"
                                        />
                                        <path
                                            className={
                                                showingNavigationDropdown
                                                    ? "inline-flex"
                                                    : "hidden"
                                            }
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            strokeWidth="2"
                                            d="M6 18L18 6M6 6l12 12"
                                        />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Mobile Navigation Menu */}
                <div
                    className={
                        (showingNavigationDropdown ? "block" : "hidden") +
                        " md:hidden absolute top-full left-0 w-full bg-white dark:bg-background-dark border-b border-[#dbe6e1] dark:border-gray-800 shadow-xl z-50"
                    }
                >
                    <div className="pt-2 pb-3 space-y-1">
                        <Link
                            href={route("dashboard")}
                            className={`block w-full pl-3 pr-4 py-2 border-l-4 text-left text-base font-medium transition duration-150 ease-in-out ${route().current("dashboard") ? "border-primary text-primary bg-primary/5 focus:text-primary focus:bg-primary/10 focus:border-primary" : "border-transparent text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-600"}`}
                        >
                            Dashboard
                        </Link>
                        <Link
                            href={route("goals.index")}
                            className={`block w-full pl-3 pr-4 py-2 border-l-4 text-left text-base font-medium transition duration-150 ease-in-out ${route().current("goals.*") ? "border-primary text-primary bg-primary/5 focus:text-primary focus:bg-primary/10 focus:border-primary" : "border-transparent text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-600"}`}
                        >
                            Metas
                        </Link>

                        <Link
                            href={route("achievements")}
                            className={`block w-full pl-3 pr-4 py-2 border-l-4 text-left text-base font-medium transition duration-150 ease-in-out ${route().current("achievements") ? "border-primary text-primary bg-primary/5 focus:text-primary focus:bg-primary/10 focus:border-primary" : "border-transparent text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-600"}`}
                        >
                            Conquistas
                        </Link>
                        <Link
                            href={route("social.feed")}
                            className={`block w-full pl-3 pr-4 py-2 border-l-4 text-left text-base font-medium transition duration-150 ease-in-out ${route().current("social.feed") ? "border-primary text-primary bg-primary/5 focus:text-primary focus:bg-primary/10 focus:border-primary" : "border-transparent text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-600"}`}
                        >
                            Social
                        </Link>
                        <Link
                            href={route("social.index")}
                            className={`block w-full pl-3 pr-4 py-2 border-l-4 text-left text-base font-medium transition duration-150 ease-in-out ${route().current("social.index") ? "border-primary text-primary bg-primary/5 focus:text-primary focus:bg-primary/10 focus:border-primary" : "border-transparent text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-600"}`}
                        >
                            Explorar{" "}
                            {pendingRequestsCount > 0 && (
                                <span className="ml-2 text-xs text-red-500 font-bold">
                                    ({pendingRequestsCount})
                                </span>
                            )}
                        </Link>
                        <Link
                            href={route("support.index")}
                            className={`block w-full pl-3 pr-4 py-2 border-l-4 text-left text-base font-medium transition duration-150 ease-in-out ${route().current("support") || route().current("support.*") ? "border-primary text-primary bg-primary/5 focus:text-primary focus:bg-primary/10 focus:border-primary" : "border-transparent text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-600"}`}
                        >
                            Suporte
                        </Link>
                    </div>

                    <div className="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                        <div className="px-4">
                            <div className="font-medium text-base text-gray-800 dark:text-gray-200">
                                {user.name}
                            </div>
                            <div className="font-medium text-sm text-gray-500">
                                {user.email}
                            </div>
                        </div>

                        <div className="mt-3 space-y-1">
                            <Link
                                href={route("social.profile")}
                                className="block w-full pl-3 pr-4 py-2 border-l-4 border-transparent text-left text-base font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-600 transition duration-150 ease-in-out"
                            >
                                Perfil
                            </Link>
                            {!(
                                user.roles?.includes("Administrador") ||
                                user.roles?.includes("Suporte")
                            ) && (
                                <Link
                                    href={route("support.my-tickets")}
                                    className="block w-full pl-3 pr-4 py-2 border-l-4 border-transparent text-left text-base font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-600 transition duration-150 ease-in-out"
                                >
                                    Meus Chamados
                                </Link>
                            )}
                            {(user.roles?.includes("Administrador") ||
                                user.roles?.includes("Suporte")) && (
                                <Link
                                    href={route("admin.dashboard")}
                                    className="block w-full pl-3 pr-4 py-2 border-l-4 border-transparent text-left text-base font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-600 transition duration-150 ease-in-out"
                                >
                                    Painel Admin
                                </Link>
                            )}
                            <Link
                                href={route("logout")}
                                method="post"
                                as="button"
                                className="block w-full pl-3 pr-4 py-2 border-l-4 border-transparent text-left text-base font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-600 transition duration-150 ease-in-out"
                            >
                                Sair
                            </Link>
                        </div>
                    </div>
                </div>
            </header>

            <main className="flex-1">{children}</main>
            <Toast />
        </div>
    );
}
