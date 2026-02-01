<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;

use App\Http\Requests\Auth\RegisterUserRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Services\FileService;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        $socialUser = session('social_user');

        return Inertia::render('Auth/LoginRegister', [
            'type' => 'register',
            'socialUser' => $socialUser,
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterUserRequest $request): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $socialUser = session('social_user');
            
            Log::info('Tentativa de registro', [
                'has_social_user' => (bool) $socialUser,
                'email' => $request->email,
                'nickname' => $request->nickname
            ]);

            $userData = [
                'name' => $request->nickname, // Use nickname as name for now
                'nickname' => $request->nickname,
                'email' => $request->email,
                'password' => $request->password ? Hash::make($request->password) : null,
            ];

            if ($socialUser) {
                $userData['google_id'] = $socialUser['google_id'];
                $userData['email_verified_at'] = now(); // Google emails are verified
            }

            $user = User::query()->create($userData);

            // Handle Avatar
            if ($socialUser && !empty($socialUser['avatar_url'])) {
                app(FileService::class)->saveFromUrl($socialUser['avatar_url'], $user, 'avatar');
            }

            if ($socialUser) {
                session()->forget('social_user');
            }

            event(new Registered($user));

            Mail::to($user)->send(new WelcomeEmail($user));

            Auth::login($user);

            DB::commit();

            return redirect(route('dashboard', absolute: false));
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erro ao registrar usuário: ' . $e->getMessage(), [
                'request' => $request->except(['password', 'password_confirmation']),
                'exception' => $e
            ]);

            return redirect()->back()->withErrors(['message' => 'Ocorreu um erro ao realizar o cadastro.']);
        }
    }
}
