<?php

namespace App\Services;

use App\Models\Goal;
use App\Models\Streak;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class StreakService
{
    /**
     * Record a streak for a goal.
     * Returns the streak (new or existing) or null if already completed today.
     *
     * @param Goal $goal
     * @return Streak|null
     */
    public function recordStreak(Goal $goal): ?Streak
    {
        $today = Carbon::today(config('app.timezone'))->toDateString();

        try {
            // Try to create a streak for today
            // Unique constraint (goal_id, completed_date) will prevent duplicates
            $streak = Streak::create([
                'goal_id' => $goal->id,
                'completed_date' => $today,
            ]);
            
            Log::info("Recorded streak for Goal {$goal->id} on {$today}.");

            return $streak;
        } catch (\Illuminate\Database\QueryException $e) {
            // Unique constraint violation - streak already exists for today
            if ($e->getCode() === '23000') {
                Log::info("Streak already exists for Goal {$goal->id} on {$today}. Skipping.");
                return null;
            }
            throw $e; // Re-throw other exceptions
        }
    }

    /**
     * Check if a streak is broken and reset if necessary.
     * This could be a scheduled task.
     */
    public function checkAndResetStreaks(User $user)
    {
        // ... implementation for background worker ...
    }

    /**
     * Get the user's global streak (consecutive days with at least one completed goal).
     *
     * @param User $user
     * @return int
     */
    public function getGlobalStreak(User $user): int
    {
        // Get all unique dates where user completed a goal
        // Using the new completed_date column for accurate timezone-aware calculations
        $dates = Streak::whereHas('goal', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->select('completed_date')
        ->distinct()
        ->orderByDesc('completed_date')
        ->pluck('completed_date')
        ->map(function ($date) {
            return Carbon::parse($date)->startOfDay();
        });

        if ($dates->isEmpty()) {
            return 0;
        }

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
                break;
            }
        }

        return $streak;
    }

    /**
     * Get number of streaks completed today.
     */
    public function getTodayCompletions(User $user): int
    {
        return Streak::whereHas('goal', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->whereDate('created_at', Carbon::today())
        ->count();
    }
}
