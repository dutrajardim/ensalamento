<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHorariosRequest extends FormRequest
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
            'disciplinas_turmas_id' => 'required|integer',
            'dia' => 'required|string|in:1,2,3,4,5,6,7',
            'horario' => 'required|integer|in:1,2'
        ];
    }

    public function expectsJson()
    {
        return true;
    }
}
