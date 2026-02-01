<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use App\Services\ExperienceService;
use App\Services\StreakService;
use App\Models\Goal;

class DashboardController extends Controller
{
    /**
     * Display the user's dashboard.
     *
     * @return Response
     */
    protected $experienceService;
    protected $streakService;

    public function __construct(ExperienceService $experienceService, StreakService $streakService)
    {
        $this->experienceService = $experienceService;
        $this->streakService = $streakService;
    }

    public function index(): Response
    {
        $user = Auth::user();
        $currentXp = $user->current_xp;
        $level = $this->experienceService->calculateLevel($currentXp);
        $nextLevelThreshold = $this->experienceService->getNextLevelXp($level);
        $previousLevelThreshold = $level > 1 ? $this->experienceService->getNextLevelXp($level - 1) : 0;

        $levelXp = $currentXp - $previousLevelThreshold;
        $levelTarget = $nextLevelThreshold - $previousLevelThreshold;
        $progressPercentage = $levelTarget > 0 ? min(100, round(($levelXp / $levelTarget) * 100)) : 0;

        $activeGoals = Goal::query()
            ->with(['microTasks', 'streaks'])
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->latest()
            ->get()
            ->map(function ($goal) {
                return array_merge($goal->toArray(), [
                    'can_complete_streak' => $goal->is_streak_enabled && (!$goal->last_completed_at || $goal->last_completed_at->format('Y-m-d') !== now()->format('Y-m-d'))
                ]);
            });

        return Inertia::render('Dashboard', [
            'activeGoals' => $activeGoals,
            'todayCompletions' => $this->streakService->getTodayCompletions($user),
            'xp' => [
                'current' => $currentXp,
                'level' => $level,
                'next_level_threshold' => $nextLevelThreshold,
                'progress_percentage' => $progressPercentage,
                'current_level_xp' => $levelXp,
                'xp_needed_for_level' => $levelTarget
            ]
        ]);
    }
}
