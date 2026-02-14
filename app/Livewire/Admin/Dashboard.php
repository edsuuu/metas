<?php

namespace App\Livewire\Admin;

use App\Models\SecurityEvent;
use App\Models\SupportTicket;
use App\Models\User;
use Livewire\Component;
use Illuminate\View\View;

class Dashboard extends Component
{
    public function render(): View
    {
        $stats = [
            'total_users' => User::query()->count(),
            'total_supports' => User::role(['Administrador', 'Suporte'])->count(),
            'pending_tickets' => SupportTicket::query()->where('status', 'pending')->count(),
            'active_tickets' => SupportTicket::query()->where('status', 'open')->count(),
        ];

        $recentActivity = SecurityEvent::query()
            ->with('user')
            ->latest()
            ->limit(10)
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'type' => $event->event_type,
                    'user_name' => $event->user->name ?? 'Sistema',
                    'ip' => $event->ip_address,
                    'details' => $event->details,
                    'created_at_time' => $event->created_at->diffForHumans(),
                ];
            });

        return view('livewire.admin.dashboard', [
            'stats' => $stats,
            'recentActivity' => $recentActivity,
        ]);
    }
}
