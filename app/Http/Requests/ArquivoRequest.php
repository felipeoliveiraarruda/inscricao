<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArquivoRequest extends FormRequest
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
            'arquivo'             => 'file|mimes:jpeg,png,pdf|max:65536',
            'codigoInscricao'     => 'integer|exists:inscricoes,codigoInscricao',
            'codigoTipoDocumento' => 'integer|exists:tipo_documentos,codigoTipoDocumento'
        ];
    }
}
