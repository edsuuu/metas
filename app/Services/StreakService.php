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
     *
     * @param Goal $goal
     * @return Streak
     */
    public function recordStreak(Goal $goal)
    {
        return DB::transaction(function () use ($goal) {
            // Create streak record (Append Only)
            $streak = Streak::create([
                'goal_id' => $goal->id,
            ]);

            // Update goal's aggregate streak
            // Now fully calculated dynamically in Goal model via Accessor.
            
            Log::info("Recorded streak for Goal {$goal->id}.");

            return $streak;

            return $streak;
        });
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
        // Join streaks with goals to filter by user
        $dates = Streak::whereHas('goal', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->selectRaw('DATE(created_at) as date')
        ->distinct()
        ->orderBy('date', 'desc')
        ->pluck('date')
        ->map(function ($date) {
            return Carbon::parse($date)->startOfDay();
        });

        if ($dates->isEmpty()) {
            return 0;
        }

        $streak = 0;
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        
        // Check if the most recent date is today or yesterday
        // If the most recent completion was before yesterday, streak is broken (0)
        // Unless we want to show the streak "so far" even if broken today? 
        // Standard is: if you missed yesterday, it is 0.
        // But if you did yesterday, and haven't done today, it is X.
        // If you did today, it is X+1.
        
        $firstDate = $dates->first();
        
        if ($firstDate->lt($yesterday)) {
            return 0;
        }

        // Iterate dates to count consecutive days
        // We start checking from Today. 
        // If $firstDate is Today, streak includes today.
        // If $firstDate is Yesterday, streak is valid but doesn't include today (yet). 
        // Actually the loop will just count backwards.
        
        // Normalized expectation: 
        // [Today, Yesterday, DayBefore] -> 3
        // [Yesterday, DayBefore] -> 2
        // [Today, DayBefore] -> 1 (Yesterday missing)
        
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
