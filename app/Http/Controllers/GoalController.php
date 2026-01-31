<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Models\MicroTask;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests\StoreGoalRequest;
use Illuminate\Support\Facades\Log;

class GoalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Goals/Create');
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
                'frequency' => $validated['frequency'],
                'deadline' => $validated['deadline'],
                'target_value' => $validated['target_value'] ?? null,
                'is_streak_enabled' => $validated['is_streak_enabled'] ?? false,
                'status' => 'active',
            ]);

            if (!empty($validated['micro_tasks'])) {
                $goal->microTasks()->createMany($validated['micro_tasks']);
            }

            return redirect()->route('dashboard');
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
