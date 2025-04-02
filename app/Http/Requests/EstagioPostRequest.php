<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EstagioPostRequest extends FormRequest
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
            "cpfEstagio"  => 'required|cpf|unique:estagios,cpfEstagio',
            "nomeEstagio" =>  'required|string',
            "emailEstagio" => 'required|string',
            "telefoneEstagio" => 'required|string',
            "cep" => 'required|string',
            "logradouro" => 'required|string',
            "numero" => 'required|string',
            "bairro" => 'required|string',
            "localidade" => 'required|string',
            "uf" => 'required|string',
            "cursoEstagio" => 'required|string',
            "semestreEstagio" => 'required|string',
            "facebookEstagio" => 'required|string',
            "instagramEstagio" => 'required|string',
            "twitterEstagio" => 'required|string',
            "wordEstagio" => 'required|string',
            "excelEstagio" => 'required|string',
            "powerPointEstagio" => 'required|string',
            "podcastEstagio" => 'required|string',
            "doodleEstagio" => 'required|string',
            "curriculoEstagio" => 'file|mimes:pdf',
            "trabalhoEstagio" => 'file|mimes:pdf',
        ];
    }
}
