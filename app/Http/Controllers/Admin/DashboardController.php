<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SupportTicket;
use App\Models\SecurityEvent;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_supports' => User::role(['Administrador', 'Suporte'])->count(),
            'pending_tickets' => SupportTicket::where('status', 'pending')->count(),
            'active_tickets' => SupportTicket::where('status', 'open')->count(),
        ];

        $recentActivity = SecurityEvent::with('user')
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

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
            'recentActivity' => $recentActivity
        ]);
    }
}
