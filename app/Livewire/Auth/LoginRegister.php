<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use App\Services\FileService;
use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LoginRegister extends Component
{
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $nickname = ''; // Used for registration
    public string $name = ''; // Mapped to nickname
    public bool $remember = false;

    public bool $isLogin = true;
    public ?array $socialUser = null;

    protected $listeners = ['toggleAuthMode' => 'toggleMode'];

    public function mount()
    {
        $this->socialUser = session('social_user');

        if (request()->routeIs('register') || request()->routeIs('register.*')) {
            $this->isLogin = false;
        }

        if ($this->socialUser) {
            $this->isLogin = false;
            $this->email = $this->socialUser['email'] ?? '';
            $this->name = $this->socialUser['name'] ?? '';
            
            // Suggest nickname from name if available
            if (!empty($this->socialUser['name'])) {
                $this->nickname = strtolower(str_replace(' ', '_', $this->socialUser['name']));
            }
        }
    }

    public function toggleMode()
    {
        $this->isLogin = !$this->isLogin;
        $this->resetErrorBag();
    }

    public function login()
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($this->only(['email', 'password']), $this->remember)) {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function register()
    {
        $this->name = $this->nickname; // Sync name with nickname

        $rules = [
            'nickname' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        ];

        if (!$this->socialUser) {
            $rules['password'] = ['required', 'string', 'confirmed', 'min:8'];
        }

        $this->validate($rules);

        DB::beginTransaction();

        try {
            Log::info('Tentativa de registro Livewire', [
                'has_social_user' => (bool) $this->socialUser,
                'email' => $this->email,
                'nickname' => $this->nickname
            ]);

            $userData = [
                'name' => $this->nickname,
                'nickname' => $this->nickname,
                'email' => $this->email,
                'password' => $this->socialUser ? null : Hash::make($this->password),
            ];

            if ($this->socialUser) {
                $userData['google_id'] = $this->socialUser['google_id'];
                $userData['email_verified_at'] = now();
            }

            $user = User::query()->create($userData);

            if ($this->socialUser && !empty($this->socialUser['avatar_url'])) {
                app(FileService::class)->saveFromUrl($this->socialUser['avatar_url'], $user, 'avatar');
            }

            if ($this->socialUser) {
                session()->forget('social_user');
            }

            event(new Registered($user));

            Mail::to($user)->send(new WelcomeEmail($user));

            Auth::login($user);

            DB::commit();

            return redirect(route('dashboard'));
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erro ao registrar usuário Livewire: ' . $e->getMessage(), [
                'email' => $this->email,
                'nickname' => $this->nickname,
                'exception' => $e
            ]);

            $this->addError('message', 'Ocorreu um erro ao realizar o cadastro.');
            return;
        }
    }

    public function render()
    {
        return view('livewire.auth.login-register');
    }
}
