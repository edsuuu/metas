<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class RegisterUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $passwordRules = ['required', 'confirmed'];
        
        // Relax rules for social registration as the password is generated internally
        if (!session()->has('social_user')) {
             $passwordRules[] = Rules\Password::defaults();
        } else {
             $passwordRules[] = 'min:8';
        }

        return [
            'nickname' => 'required|string|max:255|unique:'.User::class,
            'email' => 'required|string|lowercase|email:rfc,dns|max:255|unique:'.User::class,
            'password' => $passwordRules,
        ];
    }

    public function attributes(): array
    {
        return [
            'nickname' => 'apelido',
            'email' => 'e-mail',
            'password' => 'senha',
        ];
    }
}
