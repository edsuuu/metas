<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\SupportTicketController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
})->name('home');


Route::get('/planos', function () {
    return Inertia::render('Pricing');
})->name('pricing');

Route::get('/privacidade', function () {
    return Inertia::render('Legal/DataCollection');
})->name('privacy');

Route::get('/blog', function () {
    return Inertia::render('Blog/Index');
})->name('blog');

Route::get('/suporte', function () {
    return Inertia::render('Support/Index');
})->name('support');


// Social Auth
Route::prefix('oauth2/google')->group(function () {
    Route::get('/', [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/callback', [SocialAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
});

// Legal & Support Pages
Route::prefix('termos')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Legal/Terms');
    })->name('terms');

    Route::get('/introducao', function () {
        return Inertia::render(component: 'Legal/TermsIntro');
    })->name('terms.intro');

    Route::get('/coleta-de-dados', function () {
        return Inertia::render('Legal/DataCollection');
    })->name('terms.data-collection');

    Route::get('/seguranca', function () {
        return Inertia::render('Legal/Security');
    })->name('terms.security');

    Route::get('/responsabilidades', function () {
        return Inertia::render('Legal/Responsibilities');
    })->name('terms.responsibilities');
});


Route::prefix('suporte')->name('support.')->group(function () {
    Route::get('/meus-chamados', [SupportTicketController::class, 'myTickets'])->name('my-tickets');
    Route::post('/solicitar-acesso', [SupportTicketController::class, 'requestAccess'])->name('access.request');
    Route::get('/verificar-acesso', [SupportTicketController::class, 'verifyView'])->name('verify.view');
    Route::post('/verificar-acesso', [SupportTicketController::class, 'verifyCheck'])->name('verify.check');

    Route::prefix('chamado')->name('ticket.')->group(function () {
        Route::post('/', [SupportTicketController::class, 'store'])->name('store');
        Route::get('/{id}', [SupportTicketController::class, 'show'])->name('show');
        Route::post('/{id}/responder', [SupportTicketController::class, 'reply'])->name('reply');
    });
});

Route::middleware('auth')->group(function () {
    Route::middleware('verified')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/metas/{goal}/streak', [GoalController::class, 'completeStreak'])->name('goals.streak');
        Route::patch('/metas/{goal}/deactivate', [GoalController::class, 'deactivate'])->name('goals.deactivate');
        Route::patch('/micro-tasks/{microTask}/toggle', [GoalController::class, 'toggleMicroTask'])->name('micro-tasks.toggle');
        Route::resource('metas', GoalController::class)->names('goals');
    });

    Route::get('/conquistas', function () {
        return Inertia::render('Achievements');
    })->name('achievements');

    Route::prefix('perfil')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });
});

require __DIR__.'/auth.php';
