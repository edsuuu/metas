<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use Carbon\Carbon;

class Goal extends Model implements Auditable
{
    use AuditableTrait;

    protected $fillable = [
        'user_id',
        'title',
        'category',
        'is_streak_enabled',
        'status',
        'deadline',
        'completed_at',
    ];

    protected $casts = [
        'is_streak_enabled' => 'boolean',
        'deadline' => 'datetime',
        'completed_at' => 'datetime',
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
        // Return Carbon object (as date) or null using the new completed_date column
        if ($this->relationLoaded('streaks')) {
            $latest = $this->streaks->sortByDesc('completed_date')->first();
            return $latest?->completed_date ? Carbon::parse($latest->completed_date) : null;
        }
        $latest = $this->streaks()->orderByDesc('completed_date')->first();
        return $latest?->completed_date ? Carbon::parse($latest->completed_date) : null;
    }

    public function getCurrentStreakAttribute()
    {
        // Get streaks using completed_date for proper timezone handling
        $streaks = $this->relationLoaded('streaks') 
            ? $this->streaks->sortByDesc('completed_date') 
            : $this->streaks()->orderByDesc('completed_date')->get();

        if ($streaks->isEmpty()) {
            return 0;
        }

        // Get unique dates (completed_date is already a DATE, no timezone issues)
        $dates = $streaks->pluck('completed_date')
            ->map(fn($d) => Carbon::parse($d)->startOfDay())
            ->unique()
            ->values();

        $today = Carbon::today(config('app.timezone'))->startOfDay();
        $yesterday = Carbon::yesterday(config('app.timezone'))->startOfDay();
        
        $firstDate = $dates->first();

        // If most recent streak is before yesterday, streak is broken
        if ($firstDate->lt($yesterday)) {
            return 0;
        }

        // Count consecutive days starting from the most recent date
        $streak = 0;
        $expectedDate = $firstDate->copy();

        foreach ($dates as $date) {
            if ($date->eq($expectedDate)) {
                $streak++;
                $expectedDate->subDay();
            } else {
                break; // Gap found, stop counting
            }
        }

        return $streak;
    }
}
