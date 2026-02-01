<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupportTicket;
use App\Models\SupportTicketReply;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;
use Illuminate\Support\Str;

use App\Http\Requests\StoreSupportTicketRequest;
use App\Http\Requests\RequestSupportAccessRequest;
use App\Http\Requests\VerifySupportCodeRequest;
use App\Http\Requests\StoreSupportTicketReplyRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SupportTicketController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSupportTicketRequest $request)
    {
        try {
            DB::beginTransaction();
            $validated = $request->validated();

            $ticket = SupportTicket::query()->create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'subject' => $validated['subject'],
                'message' => $validated['message'],
                'status' => 'pending',
            ]);

            DB::commit();
            return Redirect::back()->with('success', 'Chamado criado com sucesso! Protocolo: ' . $ticket->protocol);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao criar chamado de suporte: ' . $e->getMessage(), [
                'request' => $request->all(),
                'exception' => $e
            ]);

            return Redirect::back()->withErrors(['message' => 'Ocorreu um erro ao processar sua solicitação. Por favor, tente novamente mais tarde.']);
        }
    }

    public function myTickets(Request $request)
    {
        $email = session('support_email');
        $isVerified = session('support_verified', false);
        
        $tickets = [];

        if ($isVerified && $email) {
            $tickets = SupportTicket::query()
                ->where('email', $email)
                ->when($request->search, function ($query, $search) {
                    $query->where('protocol', 'like', "%{$search}%");
                })
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($ticket) {
                    return [
                        'id' => $ticket->id,
                        'protocol' => $ticket->protocol,
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
                        'view_url' => route('support.ticket.show', ['ticket' => $ticket]),
                    ];
                });
        }
        
        return Inertia::render('Support/MyTickets', [
            'tickets' => $tickets,
            'is_verified' => $isVerified,
            'email' => $email ?? '',
            'filters' => $request->only(['search']),
        ]);
    }

    public function requestAccess(RequestSupportAccessRequest $request)
    {
         try {
             $code = rand(100000, 999999);
             
             session(['support_access_code' => $code, 'support_email' => $request->email]);
             
             Mail::send('emails.support.access-code', ['code' => $code], function ($m) use ($request) {
                $m->to($request->email)->subject('Código de acesso aos seus chamados - Everest');
             });

             return Redirect::route('support.verify.view');
         } catch (\Exception $e) {
             Log::error('Erro ao solicitar acesso ao suporte: ' . $e->getMessage(), [
                 'email' => $request->email,
                 'exception' => $e
             ]);

             return Redirect::back()->withErrors(['email' => 'Não foi possível enviar o código de acesso.']);
         }
    }

    public function verifyView() {
        if (!session('support_email')) {
            return Redirect::route('support.my-tickets');
        }
        
        return Inertia::render('Support/VerifyAccess', [
            'email' => session('support_email')
        ]);
    }
    
    public function verifyCheck(VerifySupportCodeRequest $request) {
        try {
            if ($request->code == session('support_access_code')) {
                session(['support_verified' => true]);
                session()->forget('support_access_code'); // Clear code after use
                return Redirect::route('support.my-tickets');
            }
            
            return back()->withErrors(['code' => 'Código inválido']);
        } catch (\Exception $e) {
            Log::error('Erro ao verificar código de suporte: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            return back()->withErrors(['code' => 'Erro interno ao verificar o código.']);
        }
    }

    public function show(SupportTicket $ticket)
    {
        if (!session('support_verified')) {
             return Redirect::route('support.my-tickets');
        }

        $ticket->load(['replies.user']);
        
        // Security check: ensure ticket belongs to verified email
        if ($ticket->email !== session('support_email')) {
            abort(403);
        }
        
        $messages = $ticket->replies->map(function ($reply) {
            return [
                'id' => $reply->id,
                'message' => $reply->message,
                'is_admin' => $reply->is_admin,
                'sender_name' => $reply->is_admin ? 'Suporte' : 'Você',
                'created_at_time' => $reply->created_at->format('H:i'),
            ];
        })->toArray();

        // Add initial message as first message
        array_unshift($messages, [
            'id' => 0,
            'message' => $ticket->message,
            'is_admin' => false,
            'sender_name' => 'Você',
            'created_at_time' => $ticket->created_at->format('H:i'),
        ]);

        return Inertia::render('Support/TicketDetails', [
            'ticket' => [
                'id' => $ticket->id,
                'protocol' => $ticket->protocol,
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
            ],
            'messages' => $messages
        ]);
    }

    public function reply(StoreSupportTicketReplyRequest $request, SupportTicket $ticket)
    {
        try {
            if (!session('support_verified')) {
                abort(401);
            }

            if ($ticket->status === 'resolved' || $ticket->status === 'closed') {
                return Redirect::back()->with('error', 'Este chamado já foi finalizado e não aceita mais respostas.');
            }

            // Security check
            if ($ticket->email !== session('support_email')) {
                abort(403);
            }

            DB::beginTransaction();

            SupportTicketReply::query()->create([
                'support_ticket_id' => $ticket->id,
                'user_id' => auth()->id(),
                'message' => $request->input('message'),
                'is_admin' => false,
            ]);

            $ticket->update(['status' => 'pending']); // Back to pending when user replies

            DB::commit();

            return Redirect::back()->with('success', 'Sua resposta foi enviada!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao responder chamado: ' . $e->getMessage());
            return Redirect::back()->withErrors(['message' => 'Erro ao enviar resposta.']);
        }
    }
}
