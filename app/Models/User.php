<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPasswordNotification;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements Auditable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, AuditableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'nickname',
        'email',
        'password',
        'google_id',
        // 'avatar_url', // Removed in favor of polymorphic relationship
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['current_xp', 'avatar_url'];

    public function experiences()
    {
        return $this->hasMany(Experience::class);
    }

    public function goals()
    {
        return $this->hasMany(Goal::class);
    }

    public function notificationPreference()
    {
        return $this->hasOne(NotificationPreference::class);
    }

    public function pushSubscriptions()
    {
        return $this->hasMany(PushSubscription::class);
    }

    public function avatar()
    {
        return $this->morphOne(File::class, 'fileable')->latest();
    }

    public function getAvatarUrlAttribute()
    {
        // Check if there is an avatar file linked
        $avatar = $this->avatar;
        if ($avatar) {
            return route('files.show', $avatar->uuid);
        }

        // Check if there is a google_avatar stored in session or we can fallback to UI Avatars
        // For now, return null or a default generator URL if needed by frontend
        // But frontend handles null with UI Avatars, so returning null is fine.
        return null; 
    }

    public function getCurrentXpAttribute()
    {
        return (int)$this->experiences()->sum('amount');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
