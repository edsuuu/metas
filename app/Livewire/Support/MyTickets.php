<?php

namespace App\Livewire\Support;

use Livewire\Component;
use Livewire\Attributes\Layout;

class MyTickets extends Component
{
    public $email = '';
    public $tickets = [];
    public $is_verified = false;
    public $search = '';

    public function mount()
    {
        // Mock data initialization logic or fetch from session/database
        // In a real implementation, you would check if the user is verified via session
        // and fetch their tickets.
        $this->is_verified = session('support_verified', false);
        $this->email = session('support_email', '');
        
        if ($this->is_verified) {
             // Fetch tickets logic
        }
    }

    public function submit()
    {
        $this->validate([
            'email' => 'required|email'
        ]);

        // Logic to request access token
        // SupportTicketController::requestAccess($this->email);
        
        session(['support_email' => $this->email]);
        
        return redirect()->route('support.verify.view');
    }

    public function handleSearch()
    {
        // Search logic
    }

    public function render()
    {
        return view('livewire.support.my-tickets');
    }
}
