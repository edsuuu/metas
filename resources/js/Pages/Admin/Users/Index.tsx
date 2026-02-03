import AdminLayout from "@/Layouts/AdminLayout";
import { Head, Link } from "@inertiajs/react";

interface User {
    id: number;
    name: string;
    email: string;
    avatar_url: string | null;
    goals_count: number;
    created_at: string;
    roles: string[];
}

interface Props {
    users: {
        data: User[];
        links: any[];
    };
}

export default function UsersIndex({ users }: Props) {
    return (
        <AdminLayout>
            <Head title="Gerenciar Usuários" />

            <div className="bg-white dark:bg-gray-900 rounded-3xl border border-gray-100 dark:border-gray-800 overflow-hidden shadow-sm">
                <div className="p-6 lg:p-8 border-b border-gray-50 dark:border-gray-800 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <div>
                        <h3 className="text-xl font-black dark:text-white">
                            Usuários do Sistema
                        </h3>
                        <p className="text-sm text-gray-400 mt-1">
                            Visualize e gerencie todos os alpinistas da
                            plataforma.
                        </p>
                    </div>
                    <div className="flex items-center gap-3">
                        <span className="text-xs font-bold text-gray-500 uppercase tracking-widest">
                            Total: {users.data.length}
                        </span>
                    </div>
                </div>

                {/* Desktop Table */}
                <div className="hidden lg:block overflow-x-auto">
                    <table className="w-full text-left">
                        <thead>
                            <tr className="bg-gray-50/50 dark:bg-gray-800/30 text-[10px] font-black uppercase text-gray-400 tracking-widest">
                                <th className="px-8 py-4">Usuário</th>
                                <th className="px-8 py-4 text-center">Metas</th>
                                <th className="px-8 py-4">Grupo</th>
                                <th className="px-8 py-4">Cadastro</th>
                                <th className="px-8 py-4 text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-50 dark:divide-gray-800">
                            {users.data.map((user) => (
                                <tr
                                    key={user.id}
                                    className="hover:bg-gray-50/50 dark:hover:bg-gray-800/20 transition-colors group"
                                >
                                    <td className="px-8 py-5">
                                        <div className="flex items-center gap-4">
                                            <div className="size-10 rounded-xl overflow-hidden shadow-sm border border-gray-100 dark:border-gray-700">
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
                                            <div>
                                                <p className="text-sm font-bold dark:text-white group-hover:text-primary transition-colors">
                                                    {user.name}
                                                </p>
                                                <p className="text-xs text-gray-400">
                                                    {user.email}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td className="px-8 py-5 text-center">
                                        <span className="inline-flex items-center justify-center size-8 rounded-lg bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 text-xs font-black">
                                            {user.goals_count}
                                        </span>
                                    </td>
                                    <td className="px-8 py-5">
                                        <div className="flex gap-1">
                                            {user.roles.map((role) => (
                                                <span
                                                    key={role}
                                                    className={`px-2 py-0.5 rounded text-[10px] font-bold uppercase ${
                                                        role === "Administrador"
                                                            ? "bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400"
                                                            : role === "Suporte"
                                                              ? "bg-orange-100 text-orange-600 dark:bg-orange-900/30 dark:text-orange-400"
                                                              : "bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400"
                                                    }`}
                                                >
                                                    {role}
                                                </span>
                                            ))}
                                            {user.roles.length === 0 && (
                                                <span className="text-xs text-gray-400 italic">
                                                    Cliente
                                                </span>
                                            )}
                                        </div>
                                    </td>
                                    <td className="px-8 py-5 text-sm text-gray-500 dark:text-gray-400">
                                        {user.created_at}
                                    </td>
                                    <td className="px-8 py-5 text-right">
                                        <button className="text-gray-400 hover:text-primary transition-colors">
                                            <span className="material-symbols-outlined">
                                                settings
                                            </span>
                                        </button>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>

                {/* Mobile Cards */}
                <div className="lg:hidden divide-y divide-[#dbe6e1] dark:divide-gray-800">
                    {users.data.map((user) => (
                        <div key={user.id} className="p-4 space-y-4">
                            <div className="flex items-start justify-between">
                                <div className="flex items-center gap-3">
                                    <div className="size-10 rounded-xl overflow-hidden shadow-sm border border-gray-100 dark:border-gray-700">
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
                                    <div>
                                        <p className="text-sm font-bold dark:text-white">
                                            {user.name}
                                        </p>
                                        <p className="text-xs text-gray-400">
                                            {user.email}
                                        </p>
                                    </div>
                                </div>
                                <button className="text-gray-400 hover:text-primary transition-colors p-1">
                                    <span className="material-symbols-outlined">
                                        settings
                                    </span>
                                </button>
                            </div>

                            <div className="grid grid-cols-2 gap-4">
                                <div className="bg-gray-50 dark:bg-gray-800/50 p-3 rounded-xl border border-gray-100 dark:border-gray-800">
                                    <p className="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-1">
                                        Metas
                                    </p>
                                    <span className="inline-flex items-center justify-center size-8 rounded-lg bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 text-xs font-black">
                                        {user.goals_count}
                                    </span>
                                </div>
                                <div className="bg-gray-50 dark:bg-gray-800/50 p-3 rounded-xl border border-gray-100 dark:border-gray-800">
                                    <p className="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-1">
                                        Cadastro
                                    </p>
                                    <p className="text-xs font-bold text-gray-600 dark:text-gray-300">
                                        {user.created_at}
                                    </p>
                                </div>
                            </div>

                            <div>
                                <div className="flex flex-wrap gap-1">
                                    {user.roles.map((role) => (
                                        <span
                                            key={role}
                                            className={`px-2 py-0.5 rounded text-[10px] font-bold uppercase ${
                                                role === "Administrador"
                                                    ? "bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400"
                                                    : role === "Suporte"
                                                      ? "bg-orange-100 text-orange-600 dark:bg-orange-900/30 dark:text-orange-400"
                                                      : "bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400"
                                            }`}
                                        >
                                            {role}
                                        </span>
                                    ))}
                                    {user.roles.length === 0 && (
                                        <span className="text-xs text-gray-400 italic">
                                            Cliente
                                        </span>
                                    )}
                                </div>
                            </div>
                        </div>
                    ))}
                </div>

                {users.data.length === 0 && (
                    <div className="p-20 text-center">
                        <span className="material-symbols-outlined text-4xl text-gray-200">
                            group_off
                        </span>
                        <p className="text-gray-400 mt-2 font-bold">
                            Nenhum usuário encontrado.
                        </p>
                    </div>
                )}
            </div>
        </AdminLayout>
    );
}
