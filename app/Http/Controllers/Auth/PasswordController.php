<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

use App\Http\Requests\Auth\UpdatePasswordRequest;
use Illuminate\Support\Facades\Log;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(UpdatePasswordRequest $request): RedirectResponse
    {
        try {
            $validated = $request->validated();

            $request->user()->update([
                'password' => Hash::make($validated['password']),
            ]);

            return back();
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar senha: ' . $e->getMessage(), [
                'user_id' => $request->user()->id,
                'exception' => $e
            ]);

            return back()->withErrors(['message' => 'Ocorreu um erro ao atualizar sua senha.']);
        }
    }
}
