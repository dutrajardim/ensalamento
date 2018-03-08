<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDisciplinasTurmasRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'alunos_qtd' => 'integer',
            'ano' => 'integer',
            'semestre' => 'in:1,2'
        ];
    }

    public function expectsJson()
    {
        return true;
    }
}
