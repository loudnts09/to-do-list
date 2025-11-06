<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateTarefaRequest extends FormRequest
{

    public function authorize(): bool
    {
        $tarefa = $this->route('tarefa'); 

        return $tarefa && ($tarefa->user_id === Auth::id());
    }

    protected function failedAuthorization()
    {
        throw new HttpResponseException(response('Not Found', 404));
    }

    /**
     *
     * @return array<string,
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|boolean',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'status' => $this->has('status')
        ]);
    }
}