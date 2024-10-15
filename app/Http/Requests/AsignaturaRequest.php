<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class AsignaturaRequest extends FormRequest
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
        $rules = [
            'nombre' => ['required', 'string', 'max:30', 'min:1'],
            'descripcion' => ['sometimes', 'string', 'max:15', 'min:1'],
            'codigo' => ['required', 'string', 'max:30', 'min:3', 'unique:asignaturas,codigo'],
            'year_id' => ['required', 'integer', 'exists:App\Models\Year,id'],
            'ano_escolar_id' => ['required', 'integer', 'exists:App\Models\Ano_escolar,id'],
        ];
        if ($this->isMethod('patch') || $this->isMethod('put')) {
            $rules = [
                'nombre' => ['sometimes', 'string', 'max:30', 'min:1'],
                'descripcion' => ['sometimes', 'string', 'max:15', 'min:1'],
                'codigo' => ['sometimes', 'string', 'max:30', 'min:1', 'unique:asignaturas,codigo,' . $this->route('asignatura')],
                'year_id' => ['sometimes', 'integer', 'exists:App\Models\Year,id'],
                'ano_escolar_id' => ['sometimes', 'integer', 'exists:App\Models\Ano_escolar,id'],
            ];
        }
        return $rules;
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es requerido',
            'nombre.min' => 'El nombre debe tener al menos 1 caracteres',
            'nombre.max' => 'El nombre debe tener un tamaño máximo de 30 caracteres',
            'descripcion.required' => 'La descripción es requerida',
            'descripcion.min' => 'La descripción debe tener al menos 3 caracteres',
            'descripcion.max' => 'La descripción debe tener un tamaño máximo de 15 caracteres',
            'descripcion.string' => 'La descripción es requerida',
            'codigo.required' => 'El código es requerido',
            'codigo.min' => 'El código debe tener al menos 1 caracteres',
            'codigo.max' => 'El código debe tener un tamaño máximo de 30 caracteres',
            'codigo.unique' => 'El código ya existe',
            'year_id.required' => 'El año es académico es requerido',
            'year_id.exists' => 'Este año no existe',
            'ano_escolar_id.required' => 'El año escolar es requerido',
            'ano_escolar_id.exists' => 'Este año escolar no existe',

        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator, response()->json(['errors' => $validator->errors()], 422));
    }
}
