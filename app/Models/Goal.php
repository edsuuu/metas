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
        'uuid',
        'title',
        'category',
        'is_streak_enabled',
        'status',
        'deadline',
        'completed_at',
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->uuid = (string) str()->uuid();
        });
    }

    protected $casts = [
        'is_streak_enabled' => 'boolean',
        'deadline' => 'datetime',
        'completed_at' => 'datetime',
    ];

    protected $appends = ['current_streak', 'last_completed_at', 'styles', 'progress'];

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

    public function getStylesAttribute(): array
    {
        return [
            'saude' => [
                'icon' => 'fitness_center',
                'color' => 'text-green-500',
                'bg' => 'bg-green-100',
                'bar' => 'bg-green-500',
            ],
            'financeiro' => [
                'icon' => 'payments',
                'color' => 'text-blue-500',
                'bg' => 'bg-blue-100',
                'bar' => 'bg-blue-500',
            ],
            'carreira' => [
                'icon' => 'rocket_launch',
                'color' => 'text-purple-500',
                'bg' => 'bg-purple-100',
                'bar' => 'bg-purple-500',
            ],
            'pessoal' => [
                'icon' => 'psychology',
                'color' => 'text-orange-500',
                'bg' => 'bg-orange-100',
                'bar' => 'bg-orange-500',
            ],
        ][$this->category] ?? [
            'icon' => 'flag',
            'color' => 'text-primary',
            'bg' => 'bg-primary/10',
            'bar' => 'bg-primary',
        ];
    }

    public function getProgressAttribute(): int
    {
        if (!$this->relationLoaded('microTasks')) {
            $this->loadCount(['microTasks', 'microTasks as completed_tasks_count' => function ($query) {
                $query->where('is_completed', true);
            }]);
            $total = $this->micro_tasks_count;
            $completed = $this->completed_tasks_count;
        } else {
            $total = $this->microTasks->count();
            $completed = $this->microTasks->where('is_completed', true)->count();
        }

        return $total > 0 ? (int) round(($completed / $total) * 100) : 0;
    }

    public function getTotalTasksAttribute(): int
    {
        return $this->relationLoaded('microTasks') ? $this->microTasks->count() : $this->microTasks()->count();
    }
}
