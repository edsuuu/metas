<?php

namespace App\Services;

use App\Models\Goal;
use App\Models\User;
use App\Notifications\StreakReminderNotification;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    public function __construct(
        private StreakService $streakService,
        private WebPushService $webPushService
    ) {}

    /**
     * Envia lembretes de ofensiva para todos os usuários elegíveis.
     * 
     * @param string $period 'morning' ou 'evening'
     */
    public function sendStreakReminders(string $period): int
    {
        $users = $this->getUsersWithStreaksAtRisk($period);
        $sent = 0;

        foreach ($users as $user) {
            $goalsAtRisk = $this->getGoalsAtRisk($user);
            
            if ($goalsAtRisk->isEmpty()) {
                continue;
            }

            $this->notifyStreakAtRisk($user, $goalsAtRisk, $period);
            $sent++;
        }

        Log::info("Streak reminders sent", [
            'period' => $period,
            'users_notified' => $sent,
        ]);

        return $sent;
    }

    /**
     * Notifica um usuário específico sobre ofensivas em risco.
     */
    public function notifyStreakAtRisk(User $user, Collection $goals, string $period): void
    {
        $preferences = $user->notificationPreference;
        
        // Se não tem preferências, usa padrão (email habilitado, push desabilitado)
        $emailEnabled = $preferences?->streak_email_enabled ?? true;
        $pushEnabled = $preferences?->streak_push_enabled ?? false;

        // Prepara dados das metas em risco
        $goalsData = $goals->map(function (Goal $goal) {
            return [
                'id' => $goal->id,
                'title' => $goal->title,
                'current_streak' => $goal->current_streak,
            ];
        })->toArray();

        // Calcula ofensiva global
        $globalStreak = $this->streakService->getGlobalStreak($user);

        // Envia email se habilitado
        if ($emailEnabled) {
            $user->notify(new StreakReminderNotification(
                goals: $goalsData,
                globalStreak: $globalStreak,
                period: $period
            ));
        }

        // Envia push notification se habilitado
        if ($pushEnabled) {
            $pushSent = $this->webPushService->sendStreakReminder(
                $user,
                $goalsData,
                $globalStreak,
                $period
            );
            
            Log::debug("Push notifications sent to user {$user->id}: {$pushSent}");
        }

        Log::info("Streak reminder sent to user", [
            'user_id' => $user->id,
            'period' => $period,
            'goals_count' => count($goalsData),
            'global_streak' => $globalStreak,
            'email' => $emailEnabled,
            'push' => $pushEnabled,
        ]);
    }

    /**
     * Obtém usuários que têm ofensivas em risco no período especificado.
     */
    public function getUsersWithStreaksAtRisk(string $period): Collection
    {
        $now = Carbon::now(config('app.timezone'));
        
        // Busca usuários que:
        // 1. Têm metas com ofensiva habilitada
        // 2. Não fizeram check-in hoje
        // 3. Têm notificações de email habilitadas (ou padrão)
        return User::query()
            ->whereHas('goals', function ($query) {
                $query->where('is_streak_enabled', true);
            })
            ->with(['notificationPreference', 'goals' => function ($query) {
                $query->where('is_streak_enabled', true)->with('streaks');
            }])
            ->get()
            ->filter(function (User $user) use ($period) {
                $preferences = $user->notificationPreference;
                
                // Verifica se notificações de email estão habilitadas
                if ($preferences && !$preferences->streak_email_enabled) {
                    return false;
                }
                
                // Verifica se tem alguma meta em risco
                return $this->getGoalsAtRisk($user)->isNotEmpty();
            });
    }

    /**
     * Obtém as metas do usuário que estão em risco de perder a ofensiva.
     * Uma meta está em risco se não foi completada hoje e tem uma ofensiva ativa.
     */
    public function getGoalsAtRisk(User $user): Collection
    {
        $today = Carbon::today(config('app.timezone'))->toDateString();
        
        return $user->goals
            ->filter(function (Goal $goal) use ($today) {
                // Só considera metas com ofensiva habilitada
                if (!$goal->is_streak_enabled) {
                    return false;
                }

                // Verifica se já completou hoje
                $completedToday = $goal->streaks
                    ->contains(fn ($streak) => $streak->completed_date === $today);

                if ($completedToday) {
                    return false;
                }

                // Está em risco se tem ofensiva ativa (maior que 0)
                // ou se completou ontem (começando nova sequência)
                $yesterday = Carbon::yesterday(config('app.timezone'))->toDateString();
                $completedYesterday = $goal->streaks
                    ->contains(fn ($streak) => $streak->completed_date === $yesterday);

                return $goal->current_streak > 0 || $completedYesterday;
            });
    }
}
