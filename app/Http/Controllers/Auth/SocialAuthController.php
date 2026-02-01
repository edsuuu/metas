<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\DB;

class SocialAuthController extends Controller
{
    public function redirectToGoogle()
    {
        try {
            return Socialite::driver('google')->redirect();
        } catch (\Exception $e) {
            Log::channel('auth')->error('Google Redirect Error: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return redirect()->route('login')->withErrors(['error' => 'Não foi possível redirecionar para o Google.']);
        }
    }

    /**
     * Handle the Google callback.
     */
    public function handleGoogleCallback(Request $request): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Case 1: User is already logged in (Linking Account or Sudo Mode)
            if (Auth::check()) {
                $user = Auth::user();
                
                if ($user->email === $googleUser->getEmail() || ($user->google_id && $user->google_id === $googleUser->getId())) {
                    // Update google_id if missing
                    if (empty($user->google_id)) {
                        $user->update([
                            'google_id' => $googleUser->getId(),
                            'avatar_url' => $user->avatar_url ?: $googleUser->getAvatar(), // Keep existing avatar if present
                        ]);
                    }

                    return redirect()->intended(route('dashboard'));
                }
                
                return redirect()->route('dashboard')->withErrors(['error' => 'A conta Google informada não coincide com seu usuário logado.']);
            }

            // Case 2: Regular Login Flow
            $user = User::query()
                ->where('email', $googleUser->getEmail())
                ->orWhere('google_id', $googleUser->getId())
                ->first();

            if ($user) {
                // Update google_id if not set
                if (empty($user->google_id)) {
                    $user->update([
                        'google_id' => $googleUser->getId(),
                        'avatar_url' => $user->avatar_url ?: $googleUser->getAvatar(),
                    ]);
                }

                Auth::login($user, true);
                return redirect()->intended(route('dashboard'));
            }

            // Case 3: New User / Registration
            // Store social user data in session and redirect to registration to pick a nickname
            session([
                'social_user' => [
                    'google_id' => $googleUser->getId(),
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'avatar_url' => $googleUser->getAvatar(),
                    'provider' => 'google',
                ]
            ]);

            return redirect()->route('register');

        } catch (\Exception $e) {
            Log::channel('auth')->error('Google Auth Error: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
            
            return redirect()->route('login')->withErrors(['error' => 'Falha na autenticação com o Google. Tente novamente.']);
        }
    }
}
