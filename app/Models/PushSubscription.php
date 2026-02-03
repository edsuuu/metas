<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model para assinaturas de Web Push Notifications.
 * Preparado para implementação futura - requer configuração de VAPID no .env
 */
class PushSubscription extends Model
{
    protected $fillable = [
        'user_id',
        'endpoint',
        'endpoint_hash',
        'public_key',
        'auth_token',
        'user_agent',
        'device_name',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * Boot the model.
     */
    protected static function booted(): void
    {
        static::creating(function (PushSubscription $subscription) {
            if (empty($subscription->endpoint_hash) && !empty($subscription->endpoint)) {
                $subscription->endpoint_hash = hash('sha256', $subscription->endpoint);
            }
        });

        static::updating(function (PushSubscription $subscription) {
            if ($subscription->isDirty('endpoint')) {
                $subscription->endpoint_hash = hash('sha256', $subscription->endpoint);
            }
        });
    }

    /**
     * Get the user that owns the push subscription.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if this subscription is expired.
     */
    public function isExpired(): bool
    {
        if (!$this->expires_at) {
            return false;
        }

        return $this->expires_at->isPast();
    }

    /**
     * Get subscription payload for web-push library.
     * 
     * @return array{endpoint: string, keys: array{p256dh: string|null, auth: string|null}}
     */
    public function toWebPushPayload(): array
    {
        return [
            'endpoint' => $this->endpoint,
            'keys' => [
                'p256dh' => $this->public_key,
                'auth' => $this->auth_token,
            ],
        ];
    }
}
