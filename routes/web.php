<?php

use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\PushSubscriptionController;
use App\Http\Controllers\Admin\NotificationController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'everest.home')->name('home');


//Route::view('/conquistas', 'everest.achievements')->name('achievements');
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

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('/dashboard', 'everest.dashboard')->name('dashboard');

    Route::prefix('metas')->name('goals.')->group(function () {
        Route::view('/', 'everest.goals.index')->name('index');
        Route::view('/show/{uuid}', 'everest.goals.show')->name('show');
        Route::view('manage/{uuid?}', 'everest.goals.form')->name('form');
    });

    Route::prefix('social')->name('social.')->group(function () {
        Route::view('/', 'everest.social.discovery')->name('index');
        Route::view('/feed', 'everest.social.feed')->name('feed');
        Route::view('/perfil/{identifier?}', 'everest.social.profile')->name('profile');
    });

    Route::prefix('push')->name('push.')->group(function () {
        Route::post('/subscribe', [PushSubscriptionController::class, 'store'])->name('subscribe');
        Route::post('/unsubscribe', [PushSubscriptionController::class, 'destroy'])->name('unsubscribe');
    });
});

Route::prefix('perfil')->name('profile.')->group(function () {
    Route::view('/', 'everest.profile.edit')->name('edit');
});

Route::prefix('admin')->name('admin.')->middleware(['audit.admin', 'role_or_permission:Administrador|Suporte'])->group(function () {
    Route::view('/', 'everest.admin.dashboard')->name('dashboard');
    Route::view('/usuarios', 'everest.admin.users.index')->name('users.index');

    Route::prefix('tickets')->name('tickets.')->group(function () {
        Route::view('/', 'everest.admin.tickets.index')->name('index');
        Route::view('/{protocol}', 'everest.admin.tickets.show')->name('show');
    });

    Route::view('/denuncias', 'everest.admin.reports.index')->name('reports.index');

    Route::prefix('notificacoes')->name('notifications.')->group(function () {
        Route::view('/', 'everest.admin.notifications.index')->name('index');
        Route::post('/teste', [NotificationController::class, 'sendTest'])->name('test');
    });
});

require __DIR__ . '/auth.php';
