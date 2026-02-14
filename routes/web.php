<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\SupportTicketController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\TicketController as AdminTicketController;
use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Middleware\AuditAdminAccess;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\PushSubscriptionController;
use App\Http\Controllers\Admin\NotificationController;

Route::view('/', 'everest.home')->name('home');
Route::view('/conquistas', 'everest.achievements')->name('achievements');
Route::view('/planos', 'everest.plans')->name('pricing');

Route::view('/privacidade', 'everest.legal.privacy')->name('legal.privacy');

Route::view('/blog', 'everest.blog')->name('blog');


// Social Auth
Route::prefix('oauth2/google')->group(function () {
    Route::get('/', [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/callback', [SocialAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
});

// Legal & Support Pages
Route::prefix('termos')->name('legal.')->group(function () {
    Route::view('/', 'everest.legal.terms')->name('terms.index');
    Route::view('/introducao', 'everest.legal.intro')->name('terms.intro');
    Route::view('/coleta-de-dados', 'everest.legal.privacy')->name('terms.data-collection');
    Route::view('/seguranca', 'everest.legal.security')->name('terms.security');
    Route::view('/responsabilidades', 'everest.legal.responsibilities')->name('terms.responsibilities');
});

Route::get('/files/{uuid}', [FileController::class, 'show'])->name('files.show');

// Support Routes
Route::prefix('suporte')->name('support.')->group(function () {
    Route::view('/', 'everest.support.index')->name('index');
    Route::view('/meus-chamados', 'everest.support.my-tickets')->name('my-tickets');
    Route::view('/verificar-acesso', 'everest.support.verify-access')->name('verify.view');

    Route::prefix('chamado')->name('ticket.')->group(function () {
        Route::view('/{ticket}', 'everest.support.ticket-details')->name('show');
    });
});

Route::middleware('auth')->group(function () {
    Route::middleware('verified')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/metas/{goal}/streak', [GoalController::class, 'completeStreak'])->name('goals.streak');
        Route::post('/metas/{goal}/complete', [GoalController::class, 'complete'])->name('goals.complete');
        Route::patch('/metas/{goal}/deactivate', [GoalController::class, 'deactivate'])->name('goals.deactivate');
        Route::patch('/micro-tasks/{microTask}/toggle', [GoalController::class, 'toggleMicroTask'])->name('micro-tasks.toggle');
        Route::resource('metas', GoalController::class)->names('goals');

        // Social Routes
        Route::prefix('social')->name('social.')->group(function () {
            Route::get('/', [SocialController::class, 'index'])->name('index');
            Route::get('/feed', [SocialController::class, 'feed'])->name('feed');
            Route::post('/feed', [SocialController::class, 'storePost'])->name('post.store');
            Route::get('/perfil/{identifier?}', [SocialController::class, 'profile'])->name('profile');
            Route::post('/perfil/avatar', [SocialController::class, 'updateAvatar'])->name('profile.avatar');
            Route::post('/request/{userId}', [SocialController::class, 'sendRequest'])->name('request.send');
            Route::post('/unfollow/{userId}', [SocialController::class, 'unfollow'])->name('unfollow');
            Route::post('/request/{friendshipId}/accept', [SocialController::class, 'acceptRequest'])->name('request.accept');
            Route::post('/request/{friendshipId}/decline', [SocialController::class, 'declineRequest'])->name('request.decline');
            Route::post('/post/{postId}/like', [SocialController::class, 'toggleLike'])->name('post.like');
            Route::post('/post/{postId}/comment', [SocialController::class, 'storeComment'])->name('post.comment');
            Route::post('/post/{postId}/report', [SocialController::class, 'reportPost'])->name('post.report');
            Route::post('/post/{postId}/hide', [SocialController::class, 'hidePost'])->name('post.hide');
            Route::get('/post/{postId}', [SocialController::class, 'showPost'])->name('post.show');
            Route::delete('/post/{postId}', [SocialController::class, 'deletePost'])->name('post.delete');
            Route::patch('/post/{postId}', [SocialController::class, 'updatePost'])->name('post.update');
            Route::get('/status', [SocialController::class, 'getSocialStatus'])->name('status');
        });

        // Push Subscriptions
        Route::prefix('push')->name('push.')->group(function () {
            Route::post('/subscribe', [PushSubscriptionController::class, 'store'])->name('subscribe');
            Route::post('/unsubscribe', [PushSubscriptionController::class, 'destroy'])->name('unsubscribe');
        });
    });


    Route::prefix('perfil')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    // Admin Routes
    Route::prefix('admin')->name('admin.')->middleware(['audit.admin', 'role_or_permission:Administrador|Suporte'])->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::prefix('usuarios')->name('users.')->group(function () {
            Route::get('/', [AdminUserController::class, 'index'])->name('index');
        });

        Route::prefix('tickets')->name('tickets.')->group(function () {
            Route::get('/', [AdminTicketController::class, 'index'])->name('index');
            Route::get('/{ticket}', [AdminTicketController::class, 'show'])->name('show');
            Route::post('/{ticket}/responder', [AdminTicketController::class, 'reply'])->name('reply');
            Route::post('/{ticket}/fechar', [AdminTicketController::class, 'close'])->name('close');
        });

        Route::prefix('denuncias')->name('reports.')->group(function () {
            Route::get('/', [AdminReportController::class, 'index'])->name('index');
            Route::post('/{report}/resolver', [AdminReportController::class, 'resolve'])->name('resolve');
        });

        // Notifications Test
        Route::prefix('notificacoes')->name('notifications.')->group(function () {
            Route::get('/', [NotificationController::class, 'index'])->name('index');
            Route::post('/teste', [NotificationController::class, 'sendTest'])->name('test');
        });
    });
});

require __DIR__.'/auth.php';
