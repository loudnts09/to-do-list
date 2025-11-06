<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class CreateUserRequest extends FormRequest{
    public function authorize(): bool{
        return true;
    }

    public function rules(): array{
        return [
            'name' => ['required','max:255'],
            'email' => ['required','email', 'unique:users,email', 'max:255'],
            'password' => ['required', Password::min(8)->mixedCase()->numbers()->symbols(), 'max:255']
        ];
    }

    public function messages(){
        return [
            'required' => 'O campo :attribute é obrigatório',
            'max' => 'O campo nome deve possuir no max 254 caracteres',
            'email.email' => 'O campo email deve ser um email válido',
            'email.unique' => 'Este email já está em uso',
            'password.min' => 'A senha deve ter pelo menos :min caracteres'
        ];
    }
}