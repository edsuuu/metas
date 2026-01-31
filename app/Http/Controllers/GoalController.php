<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Models\MicroTask;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

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
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'frequency' => 'required|in:unique,recurrent',
            'deadline' => 'required|date',
            'target_value' => 'nullable|numeric',
            'is_streak_enabled' => 'boolean',
            'micro_tasks' => 'nullable|array',
            'micro_tasks.*.title' => 'required|string',
            'micro_tasks.*.deadline' => 'nullable|date',
        ]);

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
