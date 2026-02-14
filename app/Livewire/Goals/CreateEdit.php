<?php

namespace App\Livewire\Goals;

use App\Models\Goal;
use App\Models\MicroTask;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class CreateEdit extends Component
{
    public ?Goal $goal = null;
    public bool $isEditing = false;
    public int $step = 1;

    // Form data
    public string $title = '';
    public string $category = 'saude';
    public bool $is_streak_enabled = false;
    public string $deadline = '';
    public array $micro_tasks = [];
    public string $new_task_title = '';

    public function mount(?string $uuid = null): void
    {
        if ($uuid) {
            $this->goal = Goal::query()
                ->with('microTasks')
                ->where('user_id', Auth::id())
                ->where('uuid', $uuid)
                ->firstOrFail();
            $this->isEditing = true;

            $this->title = $this->goal->title;
            $this->category = $this->goal->category;
            $this->is_streak_enabled = $this->goal->is_streak_enabled;
            $this->deadline = $this->goal->deadline ? $this->goal->deadline->format('Y-m-d') : '';
            $this->micro_tasks = $this->goal->microTasks->map(fn($t) => ['title' => $t->title])->toArray();
        }
    }

    protected function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:50',
                Rule::unique('goals')->where(function ($query) {
                    return $query->where('user_id', Auth::id());
                })->ignore($this->goal?->id),
            ],
            'category' => 'required|string',
            'is_streak_enabled' => [
                'boolean',
                function ($attribute, $value, $fail) {
                    if ($value && $this->deadline) {
                        $fail('Você não pode habilitar Ofensiva e Data Final ao mesmo tempo.');
                    }

                    if (!$value && empty($this->micro_tasks)) {
                        $fail('A meta precisa ter pelo menos uma sub-tarefa OU o sistema de ofensivas ativado.');
                    }
                }
            ],
            'deadline' => 'nullable|date|after_or_equal:today',
            'micro_tasks' => [
                'nullable',
                'array',
                function ($attribute, $value, $fail) {
                    if (!empty($value)) {
                        $titles = array_column($value, 'title');
                        $uniqueTitles = array_unique(array_map('mb_strtolower', $titles));
                        if (count($titles) !== count($uniqueTitles)) {
                            $fail('As micro-tarefas não podem ter títulos repetidos.');
                        }
                    }
                }
            ],
            'micro_tasks.*.title' => 'required|string',
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'title' => 'título',
            'category' => 'categoria',
            'is_streak_enabled' => 'habilitar ofensiva',
            'micro_tasks' => 'micro-tarefas',
            'micro_tasks.*.title' => 'título da micro-tarefa',
        ];
    }

    public function nextStep(): void
    {
        if ($this->step === 1) {
            $this->validateOnly('title');
            $this->validateOnly('category');
        }
        $this->step++;
    }

    public function prevStep(): void
    {
        $this->step--;
    }

    public function addMicroTask(): void
    {
        $title = trim($this->new_task_title);
        if (!$title) return;

        $isDuplicate = collect($this->micro_tasks)->some(fn($t) => mb_strtolower($t['title']) === mb_strtolower($title));
        if ($isDuplicate) {
            $this->addError('new_task_title', 'Esta tarefa já foi adicionada!');
            return;
        }

        $this->micro_tasks[] = ['title' => $title];
        $this->new_task_title = '';
        $this->resetErrorBag('micro_tasks');
    }

    public function removeMicroTask(int $index): void
    {
        unset($this->micro_tasks[$index]);
        $this->micro_tasks = array_values($this->micro_tasks);
    }

    public function updatedIsStreakEnabled($value): void
    {
        if ($value) {
            $this->deadline = '';
        }
    }

    public function updatedDeadline($value): void
    {
        if ($value) {
            $this->is_streak_enabled = false;
        }
    }

    public function save(): void
    {
        $this->validate();

        try {
            DB::beginTransaction();

            $data = [
                'user_id' => Auth::id(),
                'title' => $this->title,
                'category' => $this->category,
                'is_streak_enabled' => $this->is_streak_enabled,
                'deadline' => $this->deadline ?: null,
                'status' => $this->isEditing ? $this->goal->status : 'active',
            ];

            if ($this->isEditing) {
                $this->goal->update($data);
                $this->goal->microTasks()->delete();
                if (!empty($this->micro_tasks)) {
                    $this->goal->microTasks()->createMany($this->micro_tasks);
                }
            } else {
                $goal = Goal::query()->create($data);
                if (!empty($this->micro_tasks)) {
                    $goal->microTasks()->createMany($this->micro_tasks);
                }
            }

            DB::commit();

            $message = $this->isEditing ? 'Meta atualizada com sucesso!' : 'Meta criada com sucesso!';
            $this->dispatch('toast', message: $message, type: 'success');

            $this->redirect(route('goals.index'), navigate: true);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao salvar meta: ' . $e->getMessage(), ['exception' => $e]);
            $this->dispatch('toast', message: 'Erro ao salvar meta.', type: 'error');
        }
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.goals.create-edit');
    }
}
