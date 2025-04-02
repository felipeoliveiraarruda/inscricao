<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EstagioVerificacao extends FormRequest
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
            'cpfPessoal' => 'required|cpf|unique:estagios,cpfEstagio'
        ];
    }

    public function messages()
    {
        return [
            'cpfPessoal.cpf'        => "CPF inválido",
            'cpfPessoal.unique'    => "CPF já inscrito no processo seletivo",
        ];
    }
}
