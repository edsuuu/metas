<?php

namespace App\Livewire\Support;

use App\Models\SupportTicket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Livewire\Component;

class Index extends Component
{
    public string $name = '';
    public string $email = '';
    public string $subject = 'Dúvida Técnica';
    public string $message = '';
    public bool $sent = false;
    public string $protocol = '';

    protected function rules(): array
    {
        return [
            'name' => 'required|min:3',
            'email' => 'required|email',
            'subject' => 'required',
            'message' => 'required|min:10|max:5000',
        ];
    }

    protected function messages(): array
    {
        return [
            'name.required' => 'O nome é obrigatório.',
            'name.min' => 'O nome deve ter pelo menos 3 caracteres.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'Digite um e-mail válido.',
            'subject.required' => 'Selecione um assunto.',
            'message.required' => 'A mensagem é obrigatória.',
            'message.min' => 'A mensagem deve ter pelo menos 10 caracteres.',
            'message.max' => 'A mensagem não pode exceder 5000 caracteres.',
        ];
    }

    public function submit(): void
    {
        $this->validate();

        try {
            DB::beginTransaction();

            $ticket = SupportTicket::query()->create([
                'name' => $this->name,
                'email' => $this->email,
                'subject' => $this->subject,
                'message' => $this->message,
                'status' => 'pending',
            ]);

            DB::commit();

            $this->protocol = $ticket->protocol;
            $this->sent = true;
            $this->reset(['name', 'email', 'subject', 'message']);

            $this->dispatch('toast', message: "Chamado criado! Protocolo: {$this->protocol}", type: 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao criar chamado de suporte: ' . $e->getMessage(), ['exception' => $e]);
            $this->dispatch('toast', message: 'Erro ao processar sua solicitação. Tente novamente.', type: 'error');
        }
    }

    public function sendNew(): void
    {
        $this->sent = false;
        $this->protocol = '';
    }

    public function render(): View
    {
        return view('livewire.support.index');
    }
}
