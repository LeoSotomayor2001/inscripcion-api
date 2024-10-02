<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SeccionRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:30', 'min:1'],
            'capacidad' => ['required', 'integer', 'min:1'],
            'year_id' => ['required', 'integer', 'exists:App\Models\Year,id'],
        ];
    }


    public function messages()
    {
        return [
            'name.required' => 'El nombre es obligatorio',
            'capacidad.required' => 'La capacidad es obligatoria',
            'year_id.required' => 'El año es obligatorio',
            'capacidad.min' => 'La capacidad debe ser mayor o igual a 1',
            'year_id.exists' => 'El año no existe',
            'name.min' => 'El nombre debe tener al menos 1 caracter',
            'name.max' => 'El nombre debe tener como maximo 30 caracteres',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
