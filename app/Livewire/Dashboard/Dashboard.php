<?php

namespace App\Livewire\Dashboard;

use App\Models\Goal;
use App\Models\MicroTask;
use App\Models\User;
use App\Services\ExperienceService;
use App\Services\SocialService;
use App\Services\StreakService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Dashboard extends Component
{
    public string $search = '';
    
    // XP Data
    public int $currentXp = 0;
    public int $level = 1;
    public int $nextLevelThreshold = 1000;
    public int $progressPercentage = 0;
    public int $levelXp = 0;
    public int $levelTarget = 1000;
    
    // Other data
    public int $todayCompletions = 0;

    public function mount(ExperienceService $experienceService, StreakService $streakService)
    {
        $this->updateUserStats($experienceService, $streakService);
    }

    public function updateUserStats(ExperienceService $experienceService, StreakService $streakService): void
    {
        /** @var User $user */
        $user = Auth::user();
        
        $this->currentXp = $user->current_xp;
        $this->level = $experienceService->calculateLevel($this->currentXp);
        $this->nextLevelThreshold = $experienceService->getNextLevelXp($this->level);
        $previousLevelThreshold = $this->level > 1 ? $experienceService->getNextLevelXp($this->level - 1) : 0;

        $this->levelXp = $this->currentXp - $previousLevelThreshold;
        $this->levelTarget = $this->nextLevelThreshold - $previousLevelThreshold;
        $this->progressPercentage = $this->levelTarget > 0 ? (int) min(100, round(($this->levelXp / $this->levelTarget) * 100)) : 0;
        
        $this->todayCompletions = $streakService->getTodayCompletions($user);
    }

    public function toggleMicroTask(int $taskId, ExperienceService $experienceService, StreakService $streakService): void
    {
        $microTask = MicroTask::query()
            ->whereHas('goal', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->findOrFail($taskId);

        $microTask->update([
            'is_completed' => !$microTask->is_completed,
            'completed_at' => !$microTask->is_completed ? now() : null,
        ]);

        $this->updateUserStats($experienceService, $streakService);
        $this->dispatch('microTaskToggled');
    }

    public function confirmStreak(int $goalId, ExperienceService $experienceService, StreakService $streakService, SocialService $socialService): void
    {
        try {
            DB::beginTransaction();

            /** @var Goal $goal */
            $goal = Goal::query()
                ->where('user_id', Auth::id())
                ->lockForUpdate()
                ->findOrFail($goalId);

            if (!$goal->is_streak_enabled) {
                DB::rollBack();
                $this->dispatch('error', ['message' => 'Esta meta não possui sistema de ofensivas.']);
                return;
            }

            if ($goal->last_completed_at && $goal->last_completed_at->isToday()) {
                DB::rollBack();
                $this->dispatch('error', ['message' => 'Você já completou sua meta hoje!']);
                return;
            }

            $streak = $streakService->recordStreak($goal);

            if ($streak === null) {
                DB::rollBack();
                $this->dispatch('error', ['message' => 'Você já completou sua meta hoje!']);
                return;
            }

            $goal->refresh();
            $currentStreak = $goal->current_streak;

            $baseXp = 12;
            $bonusMultiplier = 1 + ($currentStreak * 0.01);
            $xpAmount = (int) round($baseXp * $bonusMultiplier);

            $experienceService->award(Auth::user(), $xpAmount, "Ofensiva: {$goal->title} ({$currentStreak} dias)", 'streak', $goal->id);

            $socialService->createPost(
                "Acabou de bater sua ofensiva de {$currentStreak} dias na meta: {$goal->title}! 🔥",
                'goal_completed',
                ['goal_id' => $goal->id, 'streak' => $currentStreak]
            );

            DB::commit();
            $this->updateUserStats($experienceService, $streakService);
            $this->dispatch('success', ['message' => "Ofensiva atualizada! +{$xpAmount} XP"]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao completar ofensiva: ' . $e->getMessage(), ['goal_id' => $goalId, 'exception' => $e]);
            $this->dispatch('error', ['message' => 'Erro ao registrar ofensiva.']);
        }
    }

    public function completeGoal(int $goalId, ExperienceService $experienceService, StreakService $streakService, SocialService $socialService): void
    {
        try {
            DB::beginTransaction();

            /** @var Goal $goal */
            $goal = Goal::query()
                ->where('user_id', Auth::id())
                ->findOrFail($goalId);

            if ($goal->is_streak_enabled) {
                DB::rollBack();
                $this->dispatch('error', ['message' => 'Metas com ofensiva são completadas diariamente.']);
                return;
            }

            if ($goal->status === 'completed') {
                DB::rollBack();
                $this->dispatch('error', ['message' => 'Esta meta já foi concluída!']);
                return;
            }

            $goal->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            $experienceService->award(Auth::user(), 500, "Meta Concluída: {$goal->title}", 'goal_completion', $goal->id);

            $socialService->createPost(
                "Acabou de concluir a meta: {$goal->title}! 🎉 🚀",
                'goal_completed',
                ['goal_id' => $goal->id]
            );

            DB::commit();
            $this->updateUserStats($experienceService, $streakService);
            $this->dispatch('success', ['message' => 'Parabéns! Meta concluída com sucesso! +500 XP']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao concluir meta: ' . $e->getMessage(), ['goal_id' => $goalId, 'exception' => $e]);
            $this->dispatch('error', ['message' => 'Erro ao concluir meta.']);
        }
    }

    public function render(SocialService $socialService)
    {
        /** @var User $user */
        $user = Auth::user();

        $activeGoals = Goal::query()
            ->with(['microTasks', 'streaks'])
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->get()
            ->map(function (Goal $goal) {
                $goal->can_complete_streak = $goal->is_streak_enabled && (!$goal->last_completed_at || !$goal->last_completed_at->isToday());
                return $goal;
            });

        return view('livewire.dashboard.dashboard', [
            'activeGoals' => $activeGoals,
            'ranking' => $socialService->getFriendRanking()
        ]);
    }
}
