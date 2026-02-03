<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Models\MicroTask;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests\StoreGoalRequest;
use Illuminate\Support\Facades\Log;

use App\Services\ExperienceService;
use App\Services\StreakService;
use App\Services\SocialService;
use Illuminate\Support\Facades\DB;

class GoalController extends Controller
{
    protected $experienceService;
    protected $streakService;
    protected $socialService;

    public function __construct(
        ExperienceService $experienceService, 
        StreakService $streakService,
        SocialService $socialService
    ) {
        $this->experienceService = $experienceService;
        $this->streakService = $streakService;
        $this->socialService = $socialService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $goals = Goal::query()
            ->where('user_id', Auth::id())
            ->with(['microTasks', 'streaks'])
            ->latest()
            ->get();
        return Inertia::render('Goals/Index', [
            'goals' => $goals
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Goals/Create');
    }

    public function completeStreak($id)
    {
        try {
            DB::beginTransaction();

            /** @var \App\Models\Goal $goal */
            $goal = Goal::query()
                ->where('user_id', Auth::id())
                ->lockForUpdate()
                ->findOrFail($id);

            if (!$goal->is_streak_enabled) {
                DB::rollBack();
                return redirect()->back()->withErrors(['message' => 'Esta meta não possui sistema de ofensivas.']);
            }

            // Re-check after lock to prevent race condition
            if ($goal->last_completed_at && $goal->last_completed_at->isToday()) {
                DB::rollBack();
                return redirect()->back()->withErrors(['message' => 'Você já completou sua meta hoje!']);
            }

            // Record Streak (Append Only) - returns null if already completed today
            $streak = $this->streakService->recordStreak($goal);

            if ($streak === null) {
                DB::rollBack();
                return redirect()->back()->withErrors(['message' => 'Você já completou sua meta hoje!']);
            }

            // Refresh goal to get updated streak count
            $goal->refresh();
            $currentStreak = $goal->current_streak;

            // Award XP
            $baseXp = 12;
            $bonusMultiplier = 1 + ($currentStreak * 0.01);
            $xpAmount = (int) round($baseXp * $bonusMultiplier);

            $this->experienceService->award(Auth::user(), $xpAmount, "Ofensiva: {$goal->title} ({$currentStreak} dias)", 'streak', $goal->id);

            $this->socialService->createPost(
                "Acabou de bater sua ofensiva de {$currentStreak} dias na meta: {$goal->title}! 🔥",
                'goal_completed',
                ['goal_id' => $goal->id, 'streak' => $currentStreak]
            );

            DB::commit();

            return redirect()->back()->with('success', "Ofensiva atualizada! +{$xpAmount} XP");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao completar ofensiva: ' . $e->getMessage(), ['goal_id' => $id, 'exception' => $e]);
            return redirect()->back()->withErrors(['message' => 'Erro ao registrar ofensiva.']);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGoalRequest $request)
    {
        try {
            DB::beginTransaction();
            $validated = $request->validated();

            $goal = Goal::query()->create([
                'user_id' => Auth::id(),
                'title' => $validated['title'],
                'category' => $validated['category'],
                'is_streak_enabled' => $validated['is_streak_enabled'] ?? false,
                'deadline' => $validated['deadline'] ?? null,
                'status' => 'active',
            ]);

            if (!empty($validated['micro_tasks'])) {
                $goal->microTasks()->createMany($validated['micro_tasks']);
            }

            DB::commit();
            return redirect()->route('goals.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao criar meta: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'request' => $request->all(),
                'exception' => $e
            ]);

            return redirect()->back()->withErrors(['message' => 'Ocorreu um erro ao criar a meta.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $goal = Goal::query()
            ->with(['microTasks', 'streaks'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return Inertia::render('Goals/Show', [
            'goal' => $goal,
            'can_complete_streak' => $goal->is_streak_enabled && (!$goal->last_completed_at || !$goal->last_completed_at->isToday())
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $goal = Goal::query()
            ->with('microTasks')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return Inertia::render('Goals/Create', [
            'goal' => $goal
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreGoalRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $goal = Goal::query()
                ->where('user_id', Auth::id())
                ->findOrFail($id);
            $validated = $request->validated();

            $goal->update([
                'title' => $validated['title'],
                'category' => $validated['category'],
                'is_streak_enabled' => $validated['is_streak_enabled'] ?? false,
                'deadline' => $validated['deadline'] ?? null,
            ]);

            // Sync micro tasks (simple implementation: delete and recreate)
            // Ideally we would update existing ones to preserve IDs/history if needed, but for now full replace.
            $goal->microTasks()->delete();
            if (!empty($validated['micro_tasks'])) {
                $goal->microTasks()->createMany($validated['micro_tasks']);
            }

            DB::commit();
            return redirect()->route('goals.show', $goal->id)->with('success', 'Meta atualizada com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao atualizar meta: ' . $e->getMessage(), [
                'goal_id' => $id,
                'exception' => $e
            ]);
            return redirect()->back()->withErrors(['message' => 'Erro ao atualizar meta.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $goal = Goal::query()
                ->where('user_id', Auth::id())
                ->findOrFail($id);

            if ($goal->deadline && $goal->status === 'completed') {
                return redirect()->back()->withErrors(['message' => 'Metas de prazo concluídas não podem ser excluídas para manter seu histórico de conquistas.']);
            }

            $goal->delete();

            return redirect()->route('goals.index')->with('success', 'Meta excluída com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao excluir meta: ' . $e->getMessage(), ['goal_id' => $id]);
            return redirect()->back()->withErrors(['message' => 'Erro ao excluir meta.']);
        }
    }

    public function deactivate($id)
    {
        try {
            $goal = Goal::query()
                ->where('user_id', Auth::id())
                ->findOrFail($id);

            if ($goal->deadline && $goal->status === 'completed') {
                return redirect()->back()->withErrors(['message' => 'Metas de prazo concluídas não podem ser desativadas.']);
            }

            $goal->update([
                'status' => 'archived',
            ]);

            return redirect()->route('goals.index')->with('success', 'Meta desativada com sucesso! Ofensiva resetada.');
        } catch (\Exception $e) {
            Log::error('Erro ao desativar meta: ' . $e->getMessage(), ['goal_id' => $id]);
            return redirect()->back()->withErrors(['message' => 'Erro ao desativar meta.']);
        }
    }

    public function complete($id)
    {
        try {
            /** @var \App\Models\Goal $goal */
            $goal = Goal::query()
                ->where('user_id', Auth::id())
                ->findOrFail($id);

            if ($goal->is_streak_enabled) {
                return redirect()->back()->withErrors(['message' => 'Metas com ofensiva são completadas diariamente.']);
            }

            if ($goal->status === 'completed') {
                return redirect()->back()->withErrors(['message' => 'Esta meta já foi concluída!']);
            }

            DB::beginTransaction();

            $goal->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            // Award XP for completion
            $this->experienceService->award(Auth::user(), 500, "Meta Concluída: {$goal->title}", 'goal_completion', $goal->id);

            $this->socialService->createPost(
                "Acabou de concluir a meta: {$goal->title}! 🎉 🚀",
                'goal_completed',
                ['goal_id' => $goal->id]
            );

            DB::commit();

            return redirect()->back()->with('success', 'Parabéns! Meta concluída com sucesso! +500 XP');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao concluir meta: ' . $e->getMessage(), ['goal_id' => $id, 'exception' => $e]);
            return redirect()->back()->withErrors(['message' => 'Erro ao concluir meta.']);
        }
    }

    public function toggleMicroTask($id)
    {
        try {
            $microTask = MicroTask::query()->whereHas('goal', function ($query) {
                $query->where('user_id', Auth::id());
            })->findOrFail($id);

            $microTask->update([
                'is_completed' => !$microTask->is_completed
            ]);

            return redirect()->back();
        } catch (\Exception $e) {
            Log::error('Erro ao alternar micro tarefa: ' . $e->getMessage(), ['micro_task_id' => $id]);
            return redirect()->back()->withErrors(['message' => 'Erro ao atualizar tarefa.']);
        }
    }
}
