<?php

namespace App\Services;

use App\Models\PushSubscription;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Minishlink\WebPush\MessageSentReport;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;

class WebPushService
{
    private ?WebPush $webPush = null;

    /**
     * Check if Web Push is configured.
     */
    public function isConfigured(): bool
    {
        return !empty(config('webpush.vapid.public_key')) 
            && !empty(config('webpush.vapid.private_key'));
    }

    /**
     * Get the WebPush instance.
     */
    private function getWebPush(): WebPush
    {
        if ($this->webPush === null) {
            $auth = [
                'VAPID' => [
                    'subject' => config('webpush.vapid.subject'),
                    'publicKey' => config('webpush.vapid.public_key'),
                    'privateKey' => config('webpush.vapid.private_key'),
                ],
            ];

            $this->webPush = new WebPush($auth);
            $this->webPush->setDefaultOptions([
                'TTL' => config('webpush.defaults.ttl', 2419200),
                'urgency' => config('webpush.defaults.urgency', 'normal'),
            ]);
        }

        return $this->webPush;
    }

    /**
     * Send a push notification to a user.
     * 
     * @param User $user
     * @param array{title: string, body: string, url?: string, tag?: string} $payload
     * @return int Number of successful notifications sent
     */
    public function sendToUser(User $user, array $payload): int
    {
        if (!$this->isConfigured()) {
            Log::debug('Web Push not configured, skipping.');
            return 0;
        }

        $subscriptions = $user->pushSubscriptions()
            ->whereNull('expires_at')
            ->orWhere('expires_at', '>', now())
            ->get();

        if ($subscriptions->isEmpty()) {
            Log::debug("User {$user->id} has no push subscriptions.");
            return 0;
        }

        $webPush = $this->getWebPush();
        $sent = 0;
        $toDelete = [];

        foreach ($subscriptions as $pushSubscription) {
            $subscription = Subscription::create([
                'endpoint' => $pushSubscription->endpoint,
                'keys' => [
                    'p256dh' => $pushSubscription->public_key,
                    'auth' => $pushSubscription->auth_token,
                ],
            ]);

            $webPush->queueNotification(
                $subscription,
                json_encode($payload)
            );
        }

        /** @var MessageSentReport $report */
        foreach ($webPush->flush() as $report) {
            if ($report->isSuccess()) {
                $sent++;
                Log::debug("Push sent successfully to endpoint: " . substr($report->getEndpoint(), 0, 50));
            } else {
                $reason = $report->getReason();
                Log::warning("Push failed: {$reason}");

                // If subscription is expired or invalid, mark for deletion
                if ($report->isSubscriptionExpired()) {
                    $toDelete[] = $report->getEndpoint();
                }
            }
        }

        // Clean up expired subscriptions
        if (!empty($toDelete)) {
            PushSubscription::query()
                ->where('user_id', $user->id)
                ->whereIn('endpoint', $toDelete)
                ->delete();

            Log::info("Deleted " . count($toDelete) . " expired push subscriptions for user {$user->id}");
        }

        return $sent;
    }

    /**
     * Send streak reminder push notification.
     */
    public function sendStreakReminder(User $user, array $goals, int $globalStreak, string $period): int
    {
        $title = $period === 'morning'
            ? '🔥 Bom dia! Sua ofensiva espera'
            : '⚠️ Sua ofensiva está em risco!';

        $goalsCount = count($goals);
        $body = $goalsCount > 1
            ? "{$goalsCount} metas precisam de você hoje! Não perca sua sequência de {$globalStreak} dias."
            : "Complete '{$goals[0]['title']}' para manter sua ofensiva de {$globalStreak} dias!";

        return $this->sendToUser($user, [
            'title' => $title,
            'body' => $body,
            'url' => route('dashboard'),
            'tag' => 'streak-reminder',
        ]);
    }
}
