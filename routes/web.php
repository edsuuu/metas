<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\Auth\SocialAuthController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
})->name('home');

// Social Auth
Route::prefix('oauth2/google')->group(function () {
    Route::get('/', [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/callback', [SocialAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
});

// Legal & Support Pages
Route::prefix('terms')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Legal/Terms');
    })->name('terms');

    Route::get('/intro', function () {
        return Inertia::render(component: 'Legal/TermsIntro');
    })->name('terms.intro');

    Route::get('/data-collection', function () {
        return Inertia::render('Legal/DataCollection');
    })->name('terms.data-collection');

    Route::get('/security', function () {
        return Inertia::render('Legal/Security');
    })->name('terms.security');

    Route::get('/responsibilities', function () {
        return Inertia::render('Legal/Responsibilities');
    })->name('terms.responsibilities');
});

Route::get('/plans', function () {
    return Inertia::render('Pricing');
})->name('pricing');

Route::get('/support', function () {
    return Inertia::render('Support/Index');
})->name('support');

Route::post('/support/ticket', [App\Http\Controllers\SupportTicketController::class, 'store'])->name('support.ticket.store');

Route::prefix('support')->name('support.')->group(function () {
    Route::get('/my-tickets', [App\Http\Controllers\SupportTicketController::class, 'myTickets'])->name('my-tickets');
    Route::post('/access-request', [App\Http\Controllers\SupportTicketController::class, 'requestAccess'])->name('access.request');
    Route::get('/verify-access', [App\Http\Controllers\SupportTicketController::class, 'verifyView'])->name('verify.view');
    Route::post('/verify-access', [App\Http\Controllers\SupportTicketController::class, 'verifyCheck'])->name('verify.check');
    Route::get('/ticket/{id}', [App\Http\Controllers\SupportTicketController::class, 'show'])->name('ticket.show');
    Route::post('/ticket/{id}/reply', [App\Http\Controllers\SupportTicketController::class, 'reply'])->name('ticket.reply');
});

Route::middleware('auth')->group(function () {
    Route::middleware('verified')->group(function () {
        Route::get('/dashboard', function () {
            return Inertia::render('Dashboard');
        })->name('dashboard');

        Route::resource('goals', GoalController::class);
    });

    Route::get('/achievements', function () {
        return Inertia::render('Achievements');
    })->name('achievements');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
