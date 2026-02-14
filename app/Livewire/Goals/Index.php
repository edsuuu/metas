<?php

namespace App\Livewire\Goals;

use App\Models\Goal;
use App\Models\MicroTask;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Index extends Component
{
    public string $search = '';

    protected array $queryString = [
        'search' => ['except' => ''],
    ];

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

    public function render(): \Illuminate\View\View
    {
        $goals = Goal::query()
            ->where('user_id', Auth::id())
            ->where(function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('category', 'like', '%' . $this->search . '%');
            })
            ->with(['microTasks'])
            ->latest()
            ->get();

        return view('livewire.goals.index', [
            'goals' => $goals
        ]);
    }
}
