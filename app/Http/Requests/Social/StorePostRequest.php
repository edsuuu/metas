<?php

namespace App\Http\Requests\Social;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'content' => ['required', 'string', 'max:1000'],
            'image' => ['nullable', 'image', 'max:10240'], // 10MB
        ];
    }

    public function attributes(): array
    {
        return [
            'content' => 'conteúdo',
            'image' => 'imagem',
        ];
    }
}
