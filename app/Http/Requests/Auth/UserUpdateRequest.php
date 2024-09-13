<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            'cpf'      => ['cpf', 'unique:users'],
            'rg'       => ['string'],
            'telefone' => ['string'],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'cpf' => preg_replace('/[^0-9]/', '', $this->cpf),
        ]);
    }
}
