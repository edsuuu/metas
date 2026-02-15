<aside
    class="w-64 h-screen bg-white border-r border-[#dbe6e1] transition-transform duration-300 z-50 fixed inset-y-0 left-0 lg:translate-x-0"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
    <div class="p-6 flex items-center gap-3 border-b border-[#dbe6e1]">
        <div class="size-8 text-primary">
            <svg fill="currentColor" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                <path clipRule="evenodd" d="M24 4H42V17.3333V30.6667H24V44H6V30.6667V17.3333H24V4Z" fillRule="evenodd">
                </path>
            </svg>
        </div>
        <span class="text-xl font-bold tracking-tight">Admin</span>
        <button x-on:click="sidebarOpen = false" class="lg:hidden ml-auto p-2 text-gray-500">
            <span class="material-symbols-outlined">close</span>
        </button>
    </div>

    <nav class="flex-1 p-4 flex flex-col gap-2 overflow-y-auto">
        <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Menu Principal</p>

        <x-sidebar-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')" icon="dashboard">
            Dashboard
        </x-sidebar-link>

        <x-sidebar-link href="{{ route('admin.users.index') }}" :active="request()->routeIs('admin.users.*')" icon="group">
            Usuários
        </x-sidebar-link>

        <x-sidebar-link href="{{ route('admin.tickets.index') }}" :active="request()->routeIs('admin.tickets.*')" icon="confirmation_number">
            Tickets
            @php
                $pendingTickets = \App\Models\SupportTicket::where('status', 'pending')->count();
            @endphp
            @if ($pendingTickets > 0)
                <span
                    class="ml-auto bg-red-100 text-red-600 text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $pendingTickets }}</span>
            @endif
        </x-sidebar-link>

        <x-sidebar-link href="{{ route('admin.reports.index') }}" :active="request()->routeIs('admin.reports.*')" icon="report">
            Denúncias
        </x-sidebar-link>

        <div class="my-4 h-px bg-gray-100"></div>

        <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Ferramentas</p>

        <x-sidebar-link href="{{ route('admin.notifications.index') }}" :active="request()->routeIs('admin.notifications.*')" icon="notifications">
            Notificações
        </x-sidebar-link>

        <div class="mt-auto">
            <a href="{{ route('dashboard') }}"
                class="flex items-center gap-3 px-4 py-2.5 text-gray-600 hover:text-primary hover:bg-primary/5 rounded-xl transition-all group"
                wire:navigate>
                <span class="material-symbols-outlined transition-colors">arrow_back</span>
                <span class="font-bold text-sm">Voltar ao App</span>
            </a>
        </div>
    </nav>
</aside>
