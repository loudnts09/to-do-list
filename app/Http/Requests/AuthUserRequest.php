<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class AuthUserRequest extends FormRequest{
    public function authorize(): bool{
        return true;
    }

    public function rules(): array{
        return [
            'email' => ['required','email', 'max:255'],
            'password' => ['required', 'string', 'max:255']
        ];
    }

    public function messages(){
        return [
            'required' => 'O campo e-mail e senha é obrigatório',
            'email.email' => 'O Email deve ser válido',
            'password.min' => 'A senha deve ter pelo menos 8 caracteres'
        ];
    }
}