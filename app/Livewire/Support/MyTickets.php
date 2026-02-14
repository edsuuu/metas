<?php

namespace App\Livewire\Support;

use App\Models\SupportTicket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Livewire\Component;

class MyTickets extends Component
{
    public string $email = '';
    public bool $isVerified = false;
    public string $search = '';

    public function mount(): void
    {
        if (Auth::check()) {
            $this->email = Auth::user()->email;
            $this->isVerified = true;
            session(['support_email' => $this->email, 'support_verified' => true]);
        } else {
            $this->isVerified = session('support_verified', false);
            $this->email = session('support_email', '');
        }
    }

    public function requestAccess(): void
    {
        $this->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'Digite um e-mail válido.',
        ]);

        try {
            $code = rand(100000, 999999);

            session(['support_access_code' => $code, 'support_email' => $this->email]);

            Mail::send('emails.support.access-code', ['code' => $code], function ($m) {
                $m->to($this->email)->subject('Código de acesso aos seus chamados - Everest');
            });

            $this->redirect(route('support.verify.view'), navigate: true);
        } catch (\Exception $e) {
            Log::error('Erro ao solicitar acesso ao suporte: ' . $e->getMessage(), [
                'email' => $this->email,
                'exception' => $e,
            ]);
            $this->addError('email', 'Não foi possível enviar o código de acesso.');
        }
    }

    public function render(): View
    {
        $tickets = collect();

        if ($this->isVerified && $this->email) {
            $tickets = SupportTicket::query()
                ->where('email', $this->email)
                ->when($this->search, function ($query, $search) {
                    $query->where('protocol', 'like', "%{$search}%");
                })
                ->latest()
                ->get();
        }

        return view('livewire.support.my-tickets', [
            'tickets' => $tickets,
        ]);
    }
}
