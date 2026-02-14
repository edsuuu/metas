<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::middleware('guest')->group(function () {
    Route::view('/login', 'auth.login')->name('login');
    Route::view('/register', 'auth.register')->name('register');
    Route::view('/cadastro', 'auth.register')->name('cadastro');
    
    Route::view('/esqueci-minha-senha', 'auth.forgot-password')->name('password.request');
    Route::view('/redefinir-senha/{token}', 'auth.reset-password')->name('password.reset');
});

Route::middleware('auth')->group(function () {
    Route::view('/verificar-email', 'auth.verify-email')->name('verification.notice');
    
    Route::get('/verificar-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::view('/confirmar-senha', 'auth.confirm-password')->name('password.confirm');
    
    Route::view('/senha', 'auth.update-password')->name('password.update');

    Route::post('/logout', function () {
        Auth::guard('web')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});
