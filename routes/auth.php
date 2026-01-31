<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::prefix('cadastro')->group(function () {
        Route::get('/', [RegisteredUserController::class, 'create'])->name('register');
        Route::post('/', [RegisteredUserController::class, 'store']);
    });

    Route::prefix('login')->group(function () {
        Route::get('/', [AuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('/', [AuthenticatedSessionController::class, 'store']);
    });

    Route::prefix('esqueci-minha-senha')->group(function () {
        Route::get('/', [PasswordResetLinkController::class, 'create'])->name('password.request');
        Route::post('/', [PasswordResetLinkController::class, 'store'])->name('password.email');
    });

    Route::prefix('redefinir-senha')->group(function () {
        Route::get('/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
        Route::post('/', [NewPasswordController::class, 'store'])->name('password.store');
    });
});

Route::middleware('auth')->group(function () {
    Route::prefix('verificar-email')->group(function () {
        Route::get('/', EmailVerificationPromptController::class)->name('verification.notice');
        Route::get('/{id}/{hash}', VerifyEmailController::class)
            ->middleware(['signed', 'throttle:6,1'])
            ->name('verification.verify');
    });

    Route::post('email/notificacao-de-verificacao', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::prefix('confirmar-senha')->group(function () {
        Route::get('/', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
        Route::post('/', [ConfirmablePasswordController::class, 'store']);
    });

    Route::put('senha', [PasswordController::class, 'update'])->name('password.update');

    Route::post('sair', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
