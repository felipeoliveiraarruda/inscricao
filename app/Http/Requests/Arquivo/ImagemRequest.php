<?php

namespace App\Http\Requests\Arquivo;

use Illuminate\Foundation\Http\FormRequest;

class ImagemRequest extends FormRequest
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
            'arquivo' => 'required|image|max:2048',
        ];
    }
}
