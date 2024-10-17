<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
class AsignaturaProfesorRequest extends FormRequest
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
            'asignatura_id' => 'required|exists:asignaturas,id',
            'profesor_id' => 'required|exists:users,id',
            'seccion_id' => 'required|exists:secciones,id',
        ];
    }
    public function messages()
    {
        return [
            'asignatura_id.required' => 'El campo Asignatura es obligatorio',
            'asignatura_id.exists' => 'Asignatura no encontrada',
            'profesor_id.required' => 'El campo Profesor es obligatorio',
            'profesor_id.exists' => 'Profesor no encontrado',
            'seccion_id.required' => 'El campo Sección es obligatorio',
            'seccion_id.exists' => 'Sección no encontrada',
        ];
    }

    
    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator, response()->json(['errors' => $validator->errors()], 422));
    }
}
