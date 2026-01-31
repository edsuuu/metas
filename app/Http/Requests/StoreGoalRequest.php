<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGoalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'frequency' => 'required|in:unique,recurrent',
            'deadline' => 'required|date',
            'target_value' => 'nullable|numeric',
            'is_streak_enabled' => 'boolean',
            'micro_tasks' => 'nullable|array',
            'micro_tasks.*.title' => 'required|string',
            'micro_tasks.*.deadline' => 'nullable|date',
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'título',
            'category' => 'categoria',
            'frequency' => 'frequência',
            'deadline' => 'prazo',
            'target_value' => 'valor alvo',
            'is_streak_enabled' => 'habilitar ofensiva',
            'micro_tasks' => 'micro-tarefas',
            'micro_tasks.*.title' => 'título da micro-tarefa',
            'micro_tasks.*.deadline' => 'prazo da micro-tarefa',
        ];
    }
}
