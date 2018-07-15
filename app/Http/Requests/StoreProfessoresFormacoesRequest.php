<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProfessoresFormacoesRequest extends FormRequest
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
            'formacoes_id' => 'required|integer|distinct',
            'ano' => 'required|integer',
            'grau' => 'required|in:Técnico,Graduação,Pos-graduação,Mestrado,Doutorado'
        ];
    }

    public function expectsJson()
    {
        return true;
    }
}
