<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EnsalarRequest extends FormRequest
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
            'ensalamentos.*.salas_id' => 'required|integer',
            'ensalamentos.*.dia' => 'required|string|in:1,2,3,4,5,6,7',
            'ensalamentos.*.horario' => 'required|integer|in:1,2',
            'ensalamentos.*.disciplinas_turmas_id' => 'required|integer'
        ];
        }

        public function expectsJson()
        {
            return true;
        }
}
