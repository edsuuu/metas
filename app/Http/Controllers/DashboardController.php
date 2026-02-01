<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use App\Services\ExperienceService;
use App\Services\StreakService;
use App\Services\SocialService;
use App\Models\Goal;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Display the user's dashboard.
     *
     * @return Response
     */
    protected $experienceService;
    protected $streakService;
    protected $socialService;

    public function __construct(ExperienceService $experienceService, StreakService $streakService, SocialService $socialService)
    {
        $this->experienceService = $experienceService;
        $this->streakService = $streakService;
        $this->socialService = $socialService;
    }

    public function index(): Response
    {
        /** @var User $user */
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
            ->map(function (Goal $goal) {
                $goal->can_complete_streak = $goal->is_streak_enabled && (!$goal->last_completed_at || !$goal->last_completed_at->isToday());
                return $goal;
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
            ],
            'ranking' => $this->socialService->getFriendRanking()
        ]);
    }
}
