<?php

namespace App\Livewire\Support;

use Illuminate\View\View;
use Livewire\Component;

class VerifyAccess extends Component
{
    public string $code = '';
    public string $email = '';

    public function mount(): void
    {
        $this->email = session('support_email', '');

        if (!$this->email) {
            $this->redirect(route('support.my-tickets'), navigate: true);
        }
    }

    public function submit(): void
    {
        $this->validate([
            'code' => 'required|digits:6',
        ], [
            'code.required' => 'O código é obrigatório.',
            'code.digits' => 'O código deve ter 6 dígitos.',
        ]);

        if ($this->code == session('support_access_code')) {
            session(['support_verified' => true]);
            session()->forget('support_access_code');

            $this->redirect(route('support.my-tickets'), navigate: true);
        } else {
            $this->addError('code', 'Código inválido.');
        }
    }

    public function render(): View
    {
        return view('livewire.support.verify-access');
    }
}
