<?php

namespace App\Livewire\Auth;

use App\Http\Requests\Auth\UpdatePasswordRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class UpdatePassword extends Component
{
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    protected function rules(): array
    {
        return (new UpdatePasswordRequest())->rules();
    }

    protected function validationAttributes(): array
    {
        return (new UpdatePasswordRequest())->attributes();
    }

    public function update(): void
    {
        $this->validate();

        try {
            $user = Auth::user();
            
            $user->update([
                'password' => Hash::make($this->password),
            ]);

            $this->reset(['current_password', 'password', 'password_confirmation']);

            session()->flash('status', 'password-updated');
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar senha via Livewire: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'exception' => $e
            ]);

            $this->addError('message', 'Ocorreu um erro ao atualizar sua senha.');
        }
    }

    public function render()
    {
        return view('livewire.auth.update-password');
    }
}
