<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
class InscripcionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'seccion_id' => 'required',
            'year_id' => 'required',
            'ano_escolar_id' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'seccion_id.required' => 'La seccion es requerida',
            'year_id.required' => 'El nivel escolar es requerido',
            'ano_escolar_id' => 'El periodo escolar es requerido'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator, response()->json(['errors' => $validator->errors()], 422));
    }
}
