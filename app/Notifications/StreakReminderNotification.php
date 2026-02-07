<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StreakReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @param array<int, array{id: int, title: string, current_streak: int}> $goals
     * @param int $globalStreak
     * @param string $period 'morning' ou 'evening'
     */
    public function __construct(
        public array $goals,
        public int $globalStreak,
        public string $period
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $subject = $this->period === 'morning'
            ? '🔥 Bom dia! Sua ofensiva está esperando'
            : '⚠️ Corra! Sua ofensiva está em risco';

        return (new MailMessage)
            ->subject($subject)
            ->view('emails.streak-reminder', [
                'user' => $notifiable,
                'goals' => $this->goals,
                'globalStreak' => $this->globalStreak,
                'period' => $this->period,
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'streak_reminder',
            'period' => $this->period,
            'global_streak' => $this->globalStreak,
            'goals' => $this->goals,
            'message' => $this->period === 'morning'
                ? 'Sua ofensiva está esperando por você!'
                : 'Corra! Sua ofensiva está prestes a expirar!',
        ];
    }
}
