<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\SupportTicketReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use App\Http\Requests\Admin\StoreTicketReplyRequest;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $tickets = SupportTicket::query()
            ->when($request->search, function ($query, $search) {
                $query->where('protocol', 'like', "%{$search}%")
                      ->orWhere('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('subject', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(20)
            ->through(fn ($ticket) => [
                'id' => $ticket->id,
                'protocol' => $ticket->protocol,
                'name' => $ticket->name,
                'email' => $ticket->email,
                'subject' => $ticket->subject,
                'created_at_formatted' => $ticket->created_at->format('d M Y'),
                'status' => $ticket->status,
                'status_label' => ucfirst($ticket->status),
                'status_color' => match($ticket->status) {
                    'pending' => 'bg-yellow-100 text-yellow-700',
                    'in_progress' => 'bg-blue-100 text-blue-700',
                    'resolved' => 'bg-green-100 text-green-700',
                    'closed' => 'bg-gray-100 text-gray-700',
                    default => 'bg-gray-100 text-gray-700',
                },
            ]);

        return Inertia::render('Admin/Tickets/Index', [
            'tickets' => $tickets,
            'filters' => $request->only(['search']),
        ]);
    }

    public function show(SupportTicket $ticket)
    {
        $ticket->load(['replies.user']);

        $messages = $ticket->replies->map(function ($reply) {
            return [
                'id' => $reply->id,
                'message' => $reply->message,
                'is_admin' => $reply->is_admin,
                'sender_name' => $reply->is_admin ? 'Suporte' : ($reply->user->name ?? 'Usuário'),
                'created_at_time' => $reply->created_at->format('H:i'),
            ];
        })->toArray();

        // Add initial message as first message
        array_unshift($messages, [
            'id' => 0,
            'message' => $ticket->message,
            'is_admin' => false,
            'sender_name' => $ticket->name,
            'created_at_time' => $ticket->created_at->format('H:i'),
        ]);

        return Inertia::render('Admin/Tickets/Show', [
            'ticket' => [
                'id' => $ticket->id,
                'protocol' => $ticket->protocol,
                'subject' => $ticket->subject,
                'name' => $ticket->name,
                'email' => $ticket->email,
                'created_at_formatted' => $ticket->created_at->format('d M Y'),
                'status' => $ticket->status,
                'status_label' => ucfirst($ticket->status),
                'status_color' => match($ticket->status) {
                    'pending' => 'bg-yellow-100 text-yellow-700',
                    'in_progress' => 'bg-blue-100 text-blue-700',
                    'resolved' => 'bg-green-100 text-green-700',
                    'closed' => 'bg-gray-100 text-gray-700',
                    default => 'bg-gray-100 text-gray-700',
                },
            ],
            'messages' => $messages
        ]);
    }

    public function reply(StoreTicketReplyRequest $request, SupportTicket $ticket)
    {
        try {
            if ($ticket->status === 'resolved' || $ticket->status === 'closed') {
                return Redirect::back()->with('error', 'Este chamado está finalizado. Reabra-o para responder.');
            }

            DB::beginTransaction();

            $reply = SupportTicketReply::query()->create([
                'support_ticket_id' => $ticket->id,
                'user_id' => auth()->id(),
                'message' => $request->input('message'),
                'is_admin' => true,
            ]);

            $ticket->update(['status' => 'in_progress']);

            // Send email notification to user
            try {
                Mail::send('emails.support.ticket-replied', [
                    'ticket' => $ticket,
                    'reply' => $reply
                ], function ($m) use ($ticket) {
                    $m->to($ticket->email)->subject('Everest - Seu chamado foi respondido! ' . $ticket->protocol);
                });
            } catch (\Exception $mailException) {
                Log::error('Erro ao enviar e-mail de resposta de chamado: ' . $mailException->getMessage());
                // Non-blocking error for email
            }

            DB::commit();
            return Redirect::back()->with('success', 'Resposta enviada e usuário notificado por e-mail!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao responder chamado (Admin): ' . $e->getMessage());
            return Redirect::back()->withErrors(['message' => 'Erro ao processar resposta.']);
        }
    }

    public function close(SupportTicket $ticket)
    {
        try {
            // No strict need for transaction for single update, but for consistency:
            $ticket->update(['status' => 'resolved']); // or closed
            return Redirect::back()->with('success', 'Chamado finalizado!');
        } catch (\Exception $e) {
            Log::error('Erro ao fechar chamado: ' . $e->getMessage());
            return Redirect::back()->withErrors(['message' => 'Erro ao finalizar chamado.']);
        }
    }
}
