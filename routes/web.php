<?php

use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\PushSubscriptionController;
use App\Http\Controllers\Admin\NotificationController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'everest.home')->name('home');


Route::view('/conquistas', 'everest.achievements')->name('achievements');
Route::view('/planos', 'everest.plans')->name('pricing');
Route::view('/privacidade', 'everest.legal.privacy')->name('legal.privacy');
Route::view('/blog', 'everest.blog')->name('blog');

Route::prefix('oauth2/google')->group(function () {
    Route::get('/', [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/callback', [SocialAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
});

Route::prefix('termos')->name('legal.')->group(function () {
    Route::view('/', 'everest.legal.terms')->name('terms.index');
    Route::view('/introducao', 'everest.legal.intro')->name('terms.intro');
    Route::view('/coleta-de-dados', 'everest.legal.privacy')->name('terms.data-collection');
    Route::view('/seguranca', 'everest.legal.security')->name('terms.security');
    Route::view('/responsabilidades', 'everest.legal.responsibilities')->name('terms.responsibilities');
});

Route::get('/files/{uuid}', [FileController::class, 'show'])->name('files.show');

Route::prefix('suporte')->name('support.')->group(function () {
    Route::view('/', 'everest.support.index')->name('index');
    Route::view('/meus-chamados', 'everest.support.my-tickets')->name('my-tickets');
    Route::view('/verificar-acesso', 'everest.support.verify-access')->name('verify.view');

    Route::prefix('chamado')->name('ticket.')->group(function () {
        Route::view('/{protocol}', 'everest.support.ticket-details')->name('show');
    });
});

Route::middleware('auth')->group(function () {
    Route::middleware('verified')->group(function () {
        Route::view('/dashboard', 'everest.dashboard')->name('dashboard');

        // Goals
        Route::view('/metas', 'everest.goals.index')->name('goals.index');
        Route::view('/metas/nova', 'everest.goals.form')->name('goals.create');
        Route::redirect('/metas/create', '/metas/nova');
        Route::get('/metas/{uuid}', function ($uuid) {
            return view('everest.goals.show', ['uuid' => $uuid]);
        })->name('goals.show');
        Route::get('/metas/{uuid}/editar', function ($uuid) {
            return view('everest.goals.form', ['uuid' => $uuid]);
        })->name('goals.edit');

        // Social Routes
        Route::prefix('social')->name('social.')->group(function () {
            Route::view('/', 'everest.social.discovery')->name('index');
            Route::view('/feed', 'everest.social.feed')->name('feed');
            Route::view('/perfil/{identifier?}', 'everest.social.profile')->name('profile');
        });

        // Push Subscriptions (endpoints JSON — mantém controller)
        Route::prefix('push')->name('push.')->group(function () {
            Route::post('/subscribe', [PushSubscriptionController::class, 'store'])->name('subscribe');
            Route::post('/unsubscribe', [PushSubscriptionController::class, 'destroy'])->name('unsubscribe');
        });
    });

    Route::view('/perfil', 'everest.profile.edit')->name('profile.edit');

    // Admin Routes
    Route::prefix('admin')->name('admin.')->middleware(['audit.admin', 'role_or_permission:Administrador|Suporte'])->group(function () {
        Route::view('/', 'everest.admin.dashboard')->name('dashboard');
        Route::view('/usuarios', 'everest.admin.users.index')->name('users.index');
        Route::view('/tickets', 'everest.admin.tickets.index')->name('tickets.index');
        Route::view('/tickets/{protocol}', 'everest.admin.tickets.show')->name('tickets.show');
        Route::view('/denuncias', 'everest.admin.reports.index')->name('reports.index');
        Route::view('/notificacoes', 'everest.admin.notifications.index')->name('notifications.index');
        Route::post('/notificacoes/teste', [NotificationController::class, 'sendTest'])->name('notifications.test');
    });
});

require __DIR__.'/auth.php';
