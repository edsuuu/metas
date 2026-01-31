<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            if ($request->user()->hasVerifiedEmail()) {
                return redirect()->intended(route('dashboard', absolute: false));
            }

            $request->user()->sendEmailVerificationNotification();

            return back()->with('status', 'verification-link-sent');
        } catch (\Exception $e) {
            Log::error('Erro ao enviar notificação de verificação de e-mail: ' . $e->getMessage(), [
                'user_id' => $request->user()->id,
                'exception' => $e
            ]);

            return back()->withErrors(['message' => 'Ocorreu um erro ao enviar o e-mail de verificação.']);
        }
    }
}
