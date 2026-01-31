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
            $user = User::query()->create([
                'name' => $request->nickname, // Use nickname as name for now
                'nickname' => $request->nickname,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'google_id' => session('social_user.google_id'),
                'avatar_url' => session('social_user.avatar_url'),
            ]);

            if (session('social_user')) {
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
