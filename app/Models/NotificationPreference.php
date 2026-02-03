<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationPreference extends Model
{
    protected $fillable = [
        'user_id',
        'streak_email_enabled',
        'streak_push_enabled',
        'morning_reminder_time',
        'evening_reminder_time',
        'timezone',
    ];

    protected $casts = [
        'streak_email_enabled' => 'boolean',
        'streak_push_enabled' => 'boolean',
    ];

    /**
     * Get the user that owns the notification preferences.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the effective timezone for this user.
     */
    public function getEffectiveTimezone(): string
    {
        return $this->timezone ?? config('app.timezone');
    }
}
