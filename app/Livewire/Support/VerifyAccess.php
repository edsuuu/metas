<?php

namespace App\Livewire\Support;

use Livewire\Component;

class VerifyAccess extends Component
{
    public $code = '';
    public $email = '';

    public function mount()
    {
        $this->email = session('support_email', '');
        
        if (!$this->email) {
             return redirect()->route('support.my-tickets');
        }
    }

    public function submit()
    {
        $this->validate([
            'code' => 'required|digits:6'
        ]);

        // Logic to verify code
        // SupportTicketController::verifyCode($this->email, $this->code);
        
        session(['support_verified' => true]);
        
        return redirect()->route('support.my-tickets');
    }

    public function render()
    {
        return view('livewire.support.verify-access');
    }
}
