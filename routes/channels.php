<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\SupportTicket;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('support.{ticketId}', function ($user, $ticketId) {
    $ticket = SupportTicket::find($ticketId);
    
    if (!$ticket) {
        return false;
    }

    // Admin pode ver tudo
    if ($user->hasRole('admin') || $user->hasPermissionTo('manage-tickets')) {
        return true;
    }

    // Usuário logado cujo email coincide com o do ticket
    return $user->email === $ticket->email;
});
