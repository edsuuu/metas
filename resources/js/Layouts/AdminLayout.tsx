import { PropsWithChildren, useState } from "react";
import { Link, usePage } from "@inertiajs/react";
import { User } from "@/types";
import Dropdown from "@/Components/Dropdown";

declare function route(name?: string, params?: any, absolute?: boolean): any;

export default function AdminLayout({ children }: PropsWithChildren) {
    const { auth } = usePage().props as any;
    const user = auth.user as User;
    const [isSidebarOpen, setIsSidebarOpen] = useState(false);

    const navItems = [
        {
            name: "Dashboard",
            icon: "dashboard",
            route: "admin.dashboard",
            active: "admin.dashboard",
        },
        {
            name: "Usuários",
            icon: "group",
            route: "admin.users.index",
            active: "admin.users.index",
        },
        {
            name: "Denúncias",
            icon: "report",
            route: "admin.reports.index",
            active: "admin.reports.index",
        },
        {
            name: "Chamados",
            icon: "support_agent",
            route: "admin.tickets.index",
            active: "admin.tickets.index",
        },
        {
            name: "Notificações",
            icon: "notifications",
            route: "admin.notifications.index",
            active: "admin.notifications.index",
        },
    ];

    const isRouteActive = (pattern: string) => {
        try {
            return route().current(pattern) || route().current(pattern + ".*");
        } catch (e) {
            return false;
        }
    };

    return (
        <div className="min-h-screen bg-gray-50 dark:bg-gray-950 flex transition-colors duration-300">
            {/* Mobile Sidebar Overlay */}
            {isSidebarOpen && (
                <div
                    className="fixed inset-0 bg-black/50 z-40 lg:hidden backdrop-blur-sm"
                    onClick={() => setIsSidebarOpen(false)}
                ></div>
            )}

            {/* Sidebar */}
            <aside
                className={`fixed inset-y-0 left-0 w-72 bg-gray-900 text-white flex flex-col z-50 transition-transform duration-300 ease-in-out ${isSidebarOpen ? "translate-x-0" : "-translate-x-full lg:translate-x-0"}`}
            >
                <div className="p-8 flex items-center gap-3 border-b border-gray-800">
                    <div className="size-10 bg-primary rounded-xl flex items-center justify-center text-gray-900">
                        <span className="material-symbols-outlined font-black">
                            shield_person
                        </span>
                    </div>
                    <div>
                        <h1 className="text-xl font-black tracking-tight">
                            EVEREST
                        </h1>
                        <p className="text-[10px] font-bold text-primary uppercase tracking-widest">
                            Painel Admin
                        </p>
                    </div>
                </div>

                <nav className="flex-1 p-6 space-y-2">
                    {navItems.map((item) => (
                        <Link
                            key={item.route}
                            href={route(item.route)}
                            className={`flex items-center gap-4 px-5 py-4 rounded-2xl transition-all font-bold text-sm ${
                                isRouteActive(item.active)
                                    ? "bg-primary text-gray-900 shadow-lg shadow-primary/20 scale-[1.02]"
                                    : "text-gray-400 hover:text-white hover:bg-gray-800"
                            }`}
                        >
                            <span className="material-symbols-outlined">
                                {item.icon}
                            </span>
                            {item.name}
                        </Link>
                    ))}
                </nav>

                <div className="p-6 border-t border-gray-800">
                    <Link
                        href={route("dashboard")}
                        className="flex items-center gap-3 text-gray-500 hover:text-white transition-colors text-sm font-bold"
                    >
                        <span className="material-symbols-outlined text-sm">
                            arrow_back
                        </span>
                        Voltar ao App
                    </Link>
                </div>
            </aside>

            {/* Main Content */}
            <div className="flex-1 lg:ml-72 transition-all duration-300 min-w-0">
                <header className="h-20 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 px-4 lg:px-10 flex items-center justify-between sticky top-0 z-40">
                    <div className="flex items-center gap-4">
                        <button
                            className="lg:hidden p-2 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors"
                            onClick={() => setIsSidebarOpen(true)}
                        >
                            <span className="material-symbols-outlined">
                                menu
                            </span>
                        </button>
                        <h2 className="text-xl font-black text-gray-900 dark:text-white">
                            Gerenciamento
                        </h2>
                    </div>

                    <div className="flex items-center gap-6">
                        <div className="text-right hidden sm:block">
                            <p className="text-sm font-bold dark:text-white">
                                {user.name}
                            </p>
                            <p className="text-[10px] text-primary font-black uppercase">
                                Administrador
                            </p>
                        </div>

                        <Dropdown>
                            <Dropdown.Trigger>
                                <div className="size-11 rounded-full border-2 border-primary overflow-hidden cursor-pointer shadow-sm">
                                    <img
                                        src={
                                            user.avatar_url ||
                                            "https://ui-avatars.com/api/?name=" +
                                                user.name
                                        }
                                        alt={user.name}
                                        className="w-full h-full object-cover"
                                    />
                                </div>
                            </Dropdown.Trigger>
                            <Dropdown.Content align="right" width="48">
                                <Dropdown.Link href={route("profile.edit")}>
                                    Perfil
                                </Dropdown.Link>
                                <Dropdown.Link
                                    href={route("logout")}
                                    method="post"
                                    as="button"
                                >
                                    Sair
                                </Dropdown.Link>
                            </Dropdown.Content>
                        </Dropdown>
                    </div>
                </header>

                <main className="p-4 lg:p-10">{children}</main>
            </div>
        </div>
    );
}
