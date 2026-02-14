<?php

namespace App\Livewire\Support;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class Index extends Component
{
    public $name = '';
    public $email = '';
    public $subject = 'Dúvida Técnica';
    public $message = '';
    public $sent = false;

    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email',
        'subject' => 'required',
        'message' => 'required|min:10|max:5000',
    ];

    protected $messages = [
        'name.required' => 'O nome é obrigatório.',
        'name.min' => 'O nome deve ter pelo menos 3 caracteres.',
        'email.required' => 'O e-mail é obrigatório.',
        'email.email' => 'Digite um e-mail válido.',
        'subject.required' => 'Selecione um assunto.',
        'message.required' => 'A mensagem é obrigatória.',
        'message.min' => 'A mensagem deve ter pelo menos 10 caracteres.',
        'message.max' => 'A mensagem não pode exceder 5000 caracteres.',
    ];

    public function submit()
    {
        $this->validate();

        // Simulate sending logic or call actual controller/service
        // For now, we'll just set the sent flag to true to show the success message
        // In a real scenario, you might want to call the SupportTicketController logic here
        // or emit an event.
        
        // Example: SupportTicket::create($this->all()); 
        
        // Sleeping to simulate network request if needed, or just proceed.
        
        $this->sent = true;
        $this->reset(['name', 'email', 'subject', 'message']);
    }

    public function sendNew()
    {
        $this->sent = false;
    }

    public function render()
    {
        return view('livewire.support.index');
    }
}
