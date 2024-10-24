<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class YearRequest extends FormRequest
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
        $rules= [
            'year' => 'required|integer|min:1|max:255|unique:Years,year',
            'descripcion' => 'required|string|min:3|max:255'
        ];

        if ($this->isMethod('patch') || $this->isMethod('put')) {
            $rules= [
                'year' => ['sometimes','integer','min:1','max:255'],
                'descripcion' => 'sometimes|string|min:3|max:255'
            ];
        }

        return $rules;
    }
    public function messages(): array
    {
        return [
            'year.required' => 'El nivel académico es obligatorio',
            'year.unique' => 'El nivel académico ya está registrado',
            'year.integer' => 'El nivel académico debe ser un número',
            'year.min' => 'El nivel académico debe tener al menos un caracter',
            'year.max' => 'El nivel académico no puede pasar de 255 caracteres',
            'descripcion.required' => 'La descripción es obligatoria',
            'descripcion.string' => 'La descripción es obligatoria',
            'descripcion.min' => 'La descripción debe tener al menos 3 caracteres',
            'descripcion.max' => 'La descripción no puede pasar de 255 caracteres',
            
        ];
    }
}
