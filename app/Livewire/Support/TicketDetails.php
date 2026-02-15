<?php

namespace App\Livewire\Support;

use App\Models\SupportTicket;
use App\Models\SupportTicketReply;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use Livewire\Component;
use App\Events\SupportTicketReplySent;
use App\Services\FileService;

class TicketDetails extends Component
{
    use WithFileUploads;

    public ?SupportTicket $ticket = null;
    public string $message = '';
    public $attachment;

    #[On('echo-private:support.{ticket.id},SupportTicketReplySent')]
    public function refreshTicket($data = null): void
    {
        $this->ticket->refresh();
    }

    public function mount(string $protocol): void
    {
        if (!session('support_verified')) {
            $this->redirect(route('support.my-tickets'), navigate: true);
            return;
        }

        $this->ticket = SupportTicket::where('protocol', $protocol)->firstOrFail();

        // Verificação de segurança: ticket pertence ao email verificado
        if ($this->ticket->email !== session('support_email')) {
            abort(403);
        }
    }

    protected function rules(): array
    {
        return [
            'message' => 'required|min:3|max:5000',
            'attachment' => 'nullable|image|max:5120', // 5MB
        ];
    }

    protected function messages(): array
    {
        return [
            'message.required' => 'A mensagem é obrigatória.',
            'message.min' => 'A mensagem deve ter pelo menos 3 caracteres.',
            'message.max' => 'A mensagem não pode exceder 5000 caracteres.',
            'attachment.image' => 'O anexo deve ser uma imagem.',
            'attachment.max' => 'A imagem não pode exceder 5MB.',
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'message' => 'mensagem',
            'attachment' => 'anexo',
        ];
    }

    public function reply(FileService $fileService): void
    {
        $this->validate();

        if (!session('support_verified')) {
            abort(401);
        }

        if (in_array($this->ticket->status, ['resolved', 'closed'])) {
            $this->dispatch('toast', message: 'Este chamado já foi finalizado.', type: 'error');
            return;
        }

        try {
            DB::beginTransaction();

            $reply = SupportTicketReply::query()->create([
                'support_ticket_id' => $this->ticket->id,
                'user_id' => auth()->id(),
                'message' => $this->message,
                'is_admin' => false,
            ]);

            if ($this->attachment) {
                $file = $fileService->upload($this->attachment, $reply, 'support');
                if ($file) {
                    $reply->update(['file_id' => $file->id]);
                }
            }

            $this->ticket->update(['status' => 'pending']);

            DB::commit();

            broadcast(new SupportTicketReplySent($this->ticket->id))->toOthers();

            $this->reset(['message', 'attachment']);
            $this->ticket->refresh();
            $this->dispatch('message-sent');
            $this->dispatch('toast', message: 'Resposta enviada!', type: 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao responder chamado: ' . $e->getMessage());
            $this->dispatch('toast', message: 'Erro ao enviar resposta.', type: 'error');
        }
    }

    public function render(): View
    {
        $this->ticket?->load(['replies.user', 'replies.file']);

        $messages = collect();

        if ($this->ticket) {
            // Mensagem inicial
            $messages->push([
                'id' => 0,
                'message' => $this->ticket->message,
                'is_admin' => false,
                'sender_name' => 'Você',
                'created_at_time' => $this->ticket->created_at->format('H:i'),
            ]);

            // Respostas
            foreach ($this->ticket->replies as $reply) {
                $messages->push([
                    'id' => $reply->id,
                    'message' => $reply->message,
                    'is_admin' => $reply->is_admin,
                    'sender_name' => $reply->is_admin ? 'Suporte' : 'Você',
                    'created_at_time' => $reply->created_at->format('H:i'),
                    'attachment' => $reply->file ? asset('storage/' . $reply->file->path) : null,
                ]);
            }
        }

        return view('livewire.support.ticket-details', [
            'messages' => $messages,
        ]);
    }
}
