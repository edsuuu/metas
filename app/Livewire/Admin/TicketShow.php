<?php

namespace App\Livewire\Admin;

use App\Models\SupportTicket;
use App\Models\SupportTicketReply;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Illuminate\View\View;

class TicketShow extends Component
{
    public SupportTicket $ticket;
    public string $message = '';

    protected function rules(): array
    {
        return [
            'message' => 'required|string|min:2',
        ];
    }

    protected function messages(): array
    {
        return [
            'message.required' => 'A mensagem é obrigatória.',
            'message.min' => 'A mensagem deve ter pelo menos 2 caracteres.',
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'message' => 'mensagem',
        ];
    }

    public function mount(string $ticket): void
    {
        $this->ticket = SupportTicket::query()
            ->where('protocol', $ticket)
            ->firstOrFail();
    }

    public function reply(): void
    {
        $this->validate();

        if ($this->ticket->status === 'resolved' || $this->ticket->status === 'closed') {
            $this->dispatch('toast', message: 'Este chamado está finalizado.', type: 'error');
            return;
        }

        try {
            DB::beginTransaction();

            $reply = SupportTicketReply::query()->create([
                'support_ticket_id' => $this->ticket->id,
                'user_id' => auth()->id(),
                'message' => $this->message,
                'is_admin' => true,
            ]);

            $this->ticket->update(['status' => 'in_progress']);

            try {
                Mail::send('emails.support.ticket-replied', [
                    'ticket' => $this->ticket,
                    'reply' => $reply,
                ], function ($m) {
                    $m->to($this->ticket->email)
                      ->subject('Everest - Seu chamado foi respondido! ' . $this->ticket->protocol);
                });
            } catch (\Exception $mailException) {
                Log::error('Erro ao enviar e-mail de resposta: ' . $mailException->getMessage());
            }

            DB::commit();

            $this->reset('message');
            $this->dispatch('toast', message: 'Resposta enviada!', type: 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao responder chamado: ' . $e->getMessage());
            $this->dispatch('toast', message: 'Erro ao processar resposta.', type: 'error');
        }
    }

    public function closeTicket(): void
    {
        try {
            $this->ticket->update(['status' => 'resolved']);
            $this->dispatch('toast', message: 'Chamado finalizado!', type: 'success');
        } catch (\Exception $e) {
            Log::error('Erro ao fechar chamado: ' . $e->getMessage());
            $this->dispatch('toast', message: 'Erro ao finalizar chamado.', type: 'error');
        }
    }

    public function render(): View
    {
        $this->ticket->load(['replies.user']);

        return view('livewire.admin.ticket-show');
    }
}
