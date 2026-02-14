<?php

namespace App\Livewire\Goals;

use App\Models\Goal;
use App\Models\MicroTask;
use App\Services\ExperienceService;
use App\Services\SocialService;
use App\Services\StreakService;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Show extends Component
{
    public string $uuid;
    public bool $showDeleteModal = false;
    public bool $showDeactivateModal = false;

    public function mount(string $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function getGoalProperty(): Goal
    {
        return Goal::query()
            ->with(['microTasks', 'streaks'])
            ->where('user_id', Auth::id())
            ->where('uuid', $this->uuid)
            ->firstOrFail();
    }

    public function toggleMicroTask(int $id): void
    {
        try {
            $microTask = MicroTask::query()->whereHas('goal', function ($query) {
                $query->where('user_id', Auth::id());
            })->findOrFail($id);

            $microTask->update([
                'is_completed' => !$microTask->is_completed
            ]);

            $this->dispatch('toast', message: 'Tarefa atualizada!', type: 'success');
        } catch (\Exception $e) {
            Log::error('Erro ao alternar micro tarefa: ' . $e->getMessage(), ['micro_task_id' => $id]);
            $this->dispatch('toast', message: 'Erro ao atualizar tarefa.', type: 'error');
        }
    }

    public function confirmStreak(StreakService $streakService, ExperienceService $experienceService, SocialService $socialService): void
    {
        try {
            DB::beginTransaction();

            $goal = $this->goal;

            if (!$goal->is_streak_enabled) {
                DB::rollBack();
                $this->dispatch('toast', message: 'Esta meta não possui sistema de ofensivas.', type: 'error');
                return;
            }

            if ($goal->last_completed_at && $goal->last_completed_at->isToday()) {
                DB::rollBack();
                $this->dispatch('toast', message: 'Você já completou sua meta hoje!', type: 'error');
                return;
            }

            $streak = $streakService->recordStreak($goal);

            if ($streak === null) {
                DB::rollBack();
                $this->dispatch('toast', message: 'Você já completou sua meta hoje!', type: 'error');
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

            $this->dispatch('toast', message: "Ofensiva atualizada! +{$xpAmount} XP", type: 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao completar ofensiva: ' . $e->getMessage(), ['goal_id' => $this->goalId, 'exception' => $e]);
            $this->dispatch('toast', message: 'Erro ao registrar ofensiva.', type: 'error');
        }
    }

    public function completeGoal(ExperienceService $experienceService, SocialService $socialService): void
    {
        try {
            $goal = $this->goal;

            if ($goal->is_streak_enabled) {
                $this->dispatch('toast', message: 'Metas com ofensiva são completadas diariamente.', type: 'error');
                return;
            }

            if ($goal->status === 'completed') {
                $this->dispatch('toast', message: 'Esta meta já foi concluída!', type: 'error');
                return;
            }

            DB::beginTransaction();

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

            $this->dispatch('toast', message: 'Parabéns! Meta concluída com sucesso! +500 XP', type: 'success');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao concluir meta: ' . $e->getMessage(), ['goal_id' => $this->goalId, 'exception' => $e]);
            $this->dispatch('toast', message: 'Erro ao concluir meta.', type: 'error');
        }
    }

    public function deactivateGoal(): void
    {
        try {
            $goal = $this->goal;

            if ($goal->deadline && $goal->status === 'completed') {
                $this->dispatch('toast', message: 'Metas de prazo concluídas não podem ser desativadas.', type: 'error');
                return;
            }

            $goal->update([
                'status' => 'archived',
            ]);

            $this->showDeactivateModal = false;
            $this->dispatch('toast', message: 'Meta desativada com sucesso! Ofensiva resetada.', type: 'success');
            
            $this->redirect(route('goals.index'), navigate: true);
        } catch (\Exception $e) {
            Log::error('Erro ao desativar meta: ' . $e->getMessage(), ['goal_id' => $this->goalId]);
            $this->dispatch('toast', message: 'Erro ao desativar meta.', type: 'error');
        }
    }

    public function deleteGoal(): void
    {
        try {
            $goal = $this->goal;

            if ($goal->deadline && $goal->status === 'completed') {
                $this->dispatch('toast', message: 'Metas de prazo concluídas não podem ser excluídas para manter seu histórico de conquistas.', type: 'error');
                return;
            }

            $goal->delete();

            $this->showDeleteModal = false;
            $this->dispatch('toast', message: 'Meta excluída com sucesso!', type: 'success');
            
            $this->redirect(route('goals.index'), navigate: true);
        } catch (\Exception $e) {
            Log::error('Erro ao excluir meta: ' . $e->getMessage(), ['goal_id' => $this->goalId]);
            $this->dispatch('toast', message: 'Erro ao excluir meta.', type: 'error');
        }
    }

    public function render(): \Illuminate\View\View
    {
        $goal = $this->goal;
        return view('livewire.goals.show', [
            'goal' => $goal,
            'canCompleteStreak' => $goal->is_streak_enabled && (!$goal->last_completed_at || !$goal->last_completed_at->isToday())
        ]);
    }
}
