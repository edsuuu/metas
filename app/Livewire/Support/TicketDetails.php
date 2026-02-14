<?php

namespace App\Livewire\Support;

use Livewire\Component;

class TicketDetails extends Component
{
    public $ticketId; // Protocol or ID
    public $ticket = [];
    public $messages = [];
    public $message = '';

    public function mount($ticket)
    {
        $this->ticketId = $ticket;
        // Fetch ticket logic
        // Mocking for now
        $this->ticket = [
            'id' => 1,
            'protocol' => $ticket,
            'subject' => 'Exemplo de Chamado',
            'created_at_formatted' => '10/02/2024',
            'status' => 'pending',
            'status_label' => 'Pendente',
            'status_color' => 'bg-yellow-100 text-yellow-800',
        ];

        $this->loadMessages();
    }

    public function loadMessages()
    {
        // Fetch messages logic
        // Mocking
        $this->messages = [
            [
                'id' => 1,
                'message' => 'Olá, preciso de ajuda.',
                'is_admin' => false,
                'sender_name' => 'Você',
                'created_at_time' => '10:00',
            ],
            [
                'id' => 2,
                'message' => 'Olá! Como podemos ajudar?',
                'is_admin' => true,
                'sender_name' => 'Suporte',
                'created_at_time' => '10:05',
            ],
        ];
    }

    public function submit()
    {
        $this->validate([
            'message' => 'required|min:3'
        ]);

        // Logic to send reply
        
        $this->message = '';
        $this->loadMessages(); // Refresh messages
    }

    public function render()
    {
        return view('livewire.support.ticket-details');
    }
}
