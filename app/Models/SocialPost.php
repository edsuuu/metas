<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SocialPost extends Model
{
    protected $fillable = [
        'user_id',
        'content',
        'type',
        'is_edited',
        'original_content',
        'original_file_uuid',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'json',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function files(): MorphMany
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(SocialPostLike::class, 'social_post_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(SocialPostComment::class, 'social_post_id');
    }

    public function reports(): HasMany
    {
        return $this->hasMany(SocialPostReport::class, 'post_id');
    }

    public function hides(): HasMany
    {
        return $this->hasMany(SocialPostHide::class, 'post_id');
    }
}
