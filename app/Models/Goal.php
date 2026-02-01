<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'category',
        'is_streak_enabled',
        'status',
    ];

    protected $casts = [
        'is_streak_enabled' => 'boolean',
    ];

    protected $appends = ['current_streak', 'last_completed_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function microTasks()
    {
        return $this->hasMany(MicroTask::class);
    }

    public function streaks()
    {
        return $this->hasMany(Streak::class);
    }

    public function getLastCompletedAtAttribute()
    {
        // Return Carbon object or null
        return $this->streaks()->latest()->first()?->created_at;
    }

    public function getCurrentStreakAttribute()
    {
        // Calculate streak dynamically
        // Use eager loaded streaks if available to avoid N+1 inside loops, 
        // but if we call streaks(), it returns a query. 
        // We should check if relation is loaded.
        
        $streaks = $this->relationLoaded('streaks') 
            ? $this->streaks->sortByDesc('created_at') 
            : $this->streaks()->orderBy('created_at', 'desc')->get();

        if ($streaks->isEmpty()) {
            return 0;
        }

        $streak = 0;
        $today = now()->startOfDay();
        $yesterday = now()->subDay()->startOfDay();
        
        // Use mapping to just get dates for simpler comparison
        $dates = $streaks->map(fn($s) => $s->created_at->startOfDay())->unique();
        
        $firstDate = $dates->first();

        if ($firstDate->lt($yesterday)) {
            return 0;
        }

        $expectedDate = $firstDate->isToday() ? $today : $yesterday;

        foreach ($dates as $date) {
            if ($date->eq($expectedDate)) {
                $streak++;
                $expectedDate->subDay();
            } else {
                break;
            }
        }

        return $streak;
    }
}
