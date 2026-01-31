<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupportTicket;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;
use Illuminate\Support\Str;

use App\Http\Requests\StoreSupportTicketRequest;
use App\Http\Requests\RequestSupportAccessRequest;
use App\Http\Requests\VerifySupportCodeRequest;
use Illuminate\Support\Facades\Log;

class SupportTicketController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSupportTicketRequest $request)
    {
        try {
            $validated = $request->validated();

            $ticket = SupportTicket::query()->create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'subject' => $validated['subject'],
                'message' => $validated['message'],
                'status' => 'pending',
            ]);

            return Redirect::back()->with('success', 'Chamado criado com sucesso! Protocolo: #' . $ticket->id);
        } catch (\Exception $e) {
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
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($ticket) {
                    return [
                        'id' => $ticket->id,
                        'protocol' => '#EV-' . $ticket->id,
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
                        'view_url' => route('support.ticket.show', $ticket->id),
                    ];
                });
        }
        
        return Inertia::render('Support/MyTickets', [
            'tickets' => $tickets,
            'is_verified' => $isVerified,
            'email' => $email ?? '',
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

    public function show($id)
    {
        if (!session('support_verified')) {
             return Redirect::route('support.my-tickets');
        }

        $ticket = SupportTicket::query()->findOrFail($id);
        
        // Security check: ensure ticket belongs to verified email
        if ($ticket->email !== session('support_email')) {
            abort(403);
        }
        
        $messages = [
            [
                'id' => 1,
                'message' => $ticket->message,
                'is_admin' => false,
                'sender_name' => 'Você',
                'created_at_time' => $ticket->created_at->format('H:i'),
            ]
        ];

        return Inertia::render('Support/TicketDetails', [
            'ticket' => [
                'id' => $ticket->id,
                'protocol' => '#EV-' . $ticket->id,
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

    public function reply(Request $request, $id)
    {
        try {
            // Implementation for user reply could go here
            return Redirect::back();
        } catch (\Exception $e) {
            Log::error('Erro ao responder chamado: ' . $e->getMessage());
            return Redirect::back();
        }
    }
}
