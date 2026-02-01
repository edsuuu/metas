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

class GoalController extends Controller
{
    protected $experienceService;
    protected $streakService;

    public function __construct(ExperienceService $experienceService, StreakService $streakService)
    {
        $this->experienceService = $experienceService;
        $this->streakService = $streakService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $goals = Auth::user()->goals()->with(['microTasks', 'streaks'])->latest()->get();
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
        $goal = Auth::user()->goals()->findOrFail($id);

        if (!$goal->is_streak_enabled) {
            return redirect()->back()->withErrors(['message' => 'Esta meta não possui sistema de ofensivas.']);
        }

        if ($goal->last_completed_at && $goal->last_completed_at->isToday()) {
            return redirect()->back()->withErrors(['message' => 'Você já completou sua meta hoje!']);
        }

        // Record Streak (Append Only)
        // Goal model Accessors will recalculate values automatically next time they are accessed.
        $this->streakService->recordStreak($goal);

        // Refresh goal to get updated streak count from database/accessor
        $goal->refresh();
        $currentStreak = $goal->current_streak;

        // Award XP
        // Formula: Base XP + (Base XP * Streak%)
        // Example: Day 5 -> 100 + (100 * 0.05) = 105 XP
        // Example: Day 100 -> 100 + (100 * 1.00) = 200 XP
        $baseXp = 12;
        $bonusMultiplier = 1 + ($currentStreak * 0.01);
        $xpAmount = (int) round($baseXp * $bonusMultiplier);

        $this->experienceService->award(Auth::user(), $xpAmount, "Ofensiva: {$goal->title} ({$currentStreak} dias)", 'streak', $goal->id);

        return redirect()->back()->with('success', "Ofensiva atualizada! +{$xpAmount} XP");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGoalRequest $request)
    {
        try {
            $validated = $request->validated();

            $goal = Auth::user()->goals()->create([
                'title' => $validated['title'],
                'category' => $validated['category'],
                'is_streak_enabled' => $validated['is_streak_enabled'] ?? false,
                'status' => 'active',
            ]);

            if (!empty($validated['micro_tasks'])) {
                $goal->microTasks()->createMany($validated['micro_tasks']);
            }

            return redirect()->route('goals.index');
        } catch (\Exception $e) {
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
        $goal = Auth::user()->goals()->with(['microTasks', 'streaks'])->findOrFail($id);

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
        $goal = Auth::user()->goals()->with('microTasks')->findOrFail($id);

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
            $goal = Auth::user()->goals()->findOrFail($id);
            $validated = $request->validated();

            $goal->update([
                'title' => $validated['title'],
                'category' => $validated['category'],
                'is_streak_enabled' => $validated['is_streak_enabled'] ?? false,
            ]);

            // Sync micro tasks (simple implementation: delete and recreate)
            $goal->microTasks()->delete();
            if (!empty($validated['micro_tasks'])) {
                $goal->microTasks()->createMany($validated['micro_tasks']);
            }

            return redirect()->route('goals.show', $goal->id)->with('success', 'Meta atualizada com sucesso!');
        } catch (\Exception $e) {
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
        $goal = Auth::user()->goals()->findOrFail($id);
        $goal->delete();

        return redirect()->route('goals.index')->with('success', 'Meta excluída com sucesso!');
    }

    public function deactivate($id)
    {
        $goal = Auth::user()->goals()->findOrFail($id);

        $goal->update([
            'status' => 'archived',
        ]);

        return redirect()->route('goals.index')->with('success', 'Meta desativada com sucesso! Ofensiva resetada.');
    }

    public function toggleMicroTask($id)
    {
        $microTask = MicroTask::whereHas('goal', function ($query) {
            $query->where('user_id', Auth::id());
        })->findOrFail($id);

        $microTask->update([
            'is_completed' => !$microTask->is_completed
        ]);

        return redirect()->back();
    }
}
