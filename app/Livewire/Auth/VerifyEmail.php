<?php

namespace App\Livewire\Auth;

use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class VerifyEmail extends Component
{
    public function sendVerification()
    {
        if (Auth::user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard'));
        }

        Auth::user()->sendEmailVerificationNotification();

        session()->flash('status', 'verification-link-sent');
    }

    public function logout()
    {
        Auth::guard('web')->logout();

        Session::invalidate();
        Session::regenerateToken();

        return redirect('/');
    }

    public function render()
    {
        return view('livewire.auth.verify-email');
    }
}
