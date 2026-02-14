<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Logout extends Component
{
    public string $class = '';
    public string $label = 'Sair';

    public function logout(): void
    {
        Auth::guard('web')->logout();

        session()->invalidate();
        session()->regenerateToken();

        $this->redirect('/', navigate: true);
    }

    public function render()
    {
        return <<<'HTML'
            <button wire:click="logout" class="{{ $class ?? '' }}">
                {{ $label ?? __('Sair') }}
            </button>
        HTML;
    }
}
