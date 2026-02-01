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
            'title' => [
                'required',
                'string',
                'max:50',
                \Illuminate\Validation\Rule::unique('goals')->where(function ($query) {
                    return $query->where('user_id', $this->user()->id);
                })->ignore($this->route('goal') ?? $this->route('meta')),
            ],
            'category' => 'required|string',
            'is_streak_enabled' => [
                'boolean',
                function ($attribute, $value, $fail) {
                    $microTasks = $this->input('micro_tasks');
                    $deadline = $this->input('deadline');
                    
                    if ($value && $deadline) {
                        $fail('Você não pode habilitar Ofensiva e Data Final ao mesmo tempo.');
                    }

                    if (!$value && (empty($microTasks) || count($microTasks) === 0)) {
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
                        // Case insensitive check for duplicates
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

    public function attributes(): array
    {
        return [
            'title' => 'título',
            'category' => 'categoria',
            'is_streak_enabled' => 'habilitar ofensiva',
            'micro_tasks' => 'micro-tarefas',
            'micro_tasks.*.title' => 'título da micro-tarefa',
            'micro_tasks.*.deadline' => 'prazo da micro-tarefa',
        ];
    }
}
