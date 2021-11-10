<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ValidateRequest extends FormRequest
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
            'metalName' => [
                'required',
                Rule::in(['GOLD', 'PLATINUM', 'SILVER']),],
            'price' => 'required|numeric',
            'currency' => [
                'required',
                Rule::in(['Dollar', 'Pound', 'Euro','Yen','EGP','SAU','KWT','OMN','UAE','QAT']),],
            'user_deviceToken' => 'required'
        ];
    }
}
