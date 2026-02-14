<div>
    <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden shadow-sm">
        <div class="p-6 lg:p-8 border-b border-gray-50 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div>
                <h3 class="text-xl font-black">Usuários do Sistema</h3>
                <p class="text-sm text-gray-400 mt-1">Visualize e gerencie todos os alpinistas da plataforma.</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="relative group">
                    <span
                        class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 material-symbols-outlined text-lg group-focus-within:text-primary transition-colors">search</span>
                    <input wire:model.live.debounce.300ms="search" type="search" placeholder="Buscar usuário..."
                        class="pl-10 pr-4 h-10 rounded-xl bg-white border border-[#dbe6e1] focus:border-primary focus:ring-0 text-xs shadow-sm transition-all outline-none w-60" />
                </div>
            </div>
        </div>

        {{-- Desktop Table --}}
        <div class="hidden lg:block overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50 text-[10px] font-black uppercase text-gray-400 tracking-widest">
                        <th class="px-8 py-4">Usuário</th>
                        <th class="px-8 py-4 text-center">Metas</th>
                        <th class="px-8 py-4">Grupo</th>
                        <th class="px-8 py-4">Cadastro</th>
                        <th class="px-8 py-4 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach ($users as $user)
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="size-10 rounded-xl overflow-hidden shadow-sm border border-gray-100">
                                        <img src="{{ $user->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}"
                                            alt="{{ $user->name }}" class="w-full h-full object-cover" />
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold group-hover:text-primary transition-colors">
                                            {{ $user->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <span
                                    class="inline-flex items-center justify-center size-8 rounded-lg bg-blue-50 text-blue-600 text-xs font-black">{{ $user->goals_count }}</span>
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex flex-wrap gap-1">
                                    @forelse($user->getRoleNames() as $role)
                                        <span
                                            class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $role === 'Administrador' ? 'bg-red-100 text-red-600' : ($role === 'Suporte' ? 'bg-orange-100 text-orange-600' : 'bg-gray-100 text-gray-600') }}">{{ $role }}</span>
                                    @empty
                                        <span class="text-xs text-gray-400 italic">Cliente</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-8 py-5 text-sm text-gray-500">{{ $user->created_at->format('d/m/Y') }}</td>
                            <td class="px-8 py-5 text-right">
                                <button class="text-gray-400 hover:text-primary transition-colors">
                                    <span class="material-symbols-outlined">settings</span>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards --}}
        <div class="lg:hidden divide-y divide-gray-100">
            @foreach ($users as $user)
                <div class="p-4 space-y-4">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-3">
                            <div class="size-10 rounded-xl overflow-hidden shadow-sm border border-gray-100">
                                <img src="{{ $user->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}"
                                    alt="{{ $user->name }}" class="w-full h-full object-cover" />
                            </div>
                            <div>
                                <p class="text-sm font-bold">{{ $user->name }}</p>
                                <p class="text-xs text-gray-400">{{ $user->email }}</p>
                            </div>
                        </div>
                        <button class="text-gray-400 hover:text-primary transition-colors p-1">
                            <span class="material-symbols-outlined">settings</span>
                        </button>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 p-3 rounded-xl border border-gray-50">
                            <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-1">Metas</p>
                            <span
                                class="inline-flex items-center justify-center size-8 rounded-lg bg-blue-50 text-blue-600 text-xs font-black">{{ $user->goals_count }}</span>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-xl border border-gray-50">
                            <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-1">Cadastro</p>
                            <p class="text-xs font-bold text-gray-600">{{ $user->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-1">
                        @forelse($user->getRoleNames() as $role)
                            <span
                                class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $role === 'Administrador' ? 'bg-red-100 text-red-600' : ($role === 'Suporte' ? 'bg-orange-100 text-orange-600' : 'bg-gray-100 text-gray-600') }}">{{ $role }}</span>
                        @empty
                            <span class="text-xs text-gray-400 italic">Cliente</span>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>

        <div class="p-6 border-t border-gray-50">
            {{ $users->links() }}
        </div>
    </div>
</div>
