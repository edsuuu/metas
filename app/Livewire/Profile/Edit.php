<?php

namespace App\Livewire\Profile;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;

class Edit extends Component
{
    public string $name = '';
    public string $password = '';
    public bool $mustVerifyEmail = false;
    public bool $showDeleteModal = false;

    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->mustVerifyEmail = $user instanceof MustVerifyEmail;
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
        ];
    }

    protected function messages(): array
    {
        return [
            'name.required' => 'O nome é obrigatório.',
            'name.max' => 'O nome não pode ter mais de 255 caracteres.',
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'name' => 'nome',
        ];
    }

    public function updateProfile(): void
    {
        $this->validate();

        try {
            DB::beginTransaction();

            $user = Auth::user();
            $user->name = $this->name;
            $user->save();

            DB::commit();

            $this->dispatch('toast', message: 'Perfil atualizado com sucesso!', type: 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao atualizar perfil: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'exception' => $e,
            ]);
            $this->dispatch('toast', message: 'Erro ao atualizar perfil.', type: 'error');
        }
    }

    public function deleteAccount(): void
    {
        $this->validate([
            'password' => ['required', 'current_password'],
        ], [
            'password.required' => 'A senha é obrigatória.',
            'password.current_password' => 'A senha informada está incorreta.',
        ]);

        try {
            DB::beginTransaction();

            $user = Auth::user();

            Auth::logout();
            $user->delete();

            session()->invalidate();
            session()->regenerateToken();

            DB::commit();

            $this->redirect('/', navigate: true);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao excluir conta: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'exception' => $e,
            ]);
            $this->dispatch('toast', message: 'Erro ao excluir sua conta.', type: 'error');
        }
    }

    public function render(): View
    {
        return view('livewire.profile.edit');
    }
}
