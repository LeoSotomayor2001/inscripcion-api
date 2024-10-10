<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
class UpdateStudentRequest extends FormRequest
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
            'name' => ['sometimes', 'string', 'max:30', 'min:3'],
            'apellido' => ['sometimes', 'string', 'max:30', 'min:3'],
            'cedula' => ['sometimes', 'regex:/^[0-9]{6,9}$/', 'unique:estudiantes,cedula,' . $this->route('estudiante')->id],
            'fecha_nacimiento' => 'sometimes|date_format:d-m-Y|before:today',
            'image' => ['nullable', 'image', 'max:2048', 'mimes:jpeg,png,jpg,gif,svg,webp'],
            'genero' => 'sometimes|string|min:1|in:Masculino,Femenino,Otro',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es requerido',
            'name.min' => 'El nombre debe tener al menos 3 caracteres',
            'name.max' => 'El nombre debe tener un tamaño máximo de 30 caracteres',
            'name.string' => 'El nombre es requerido',
            'apellido.min' => 'El apellido debe tener al menos 3 caracteres',
            'apellido.string' => 'El apellido es requerido',
            'apellido.required' => 'El apellido es requerido',
            'apellido.max' => 'El apellido debe tener un tamaño máximo de 30 caracteres',
            'cedula.required' => 'La cédula es requerida',
            'cedula.regex' => 'La cédula debe tener minimo 6 numeros y maximo 10',
            'cedula.unique' => 'La cédula ya esta registrada',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es requerida',
            'fecha_nacimiento.date' => 'La fecha de nacimiento debe ser una fecha válida',
            'fecha_nacimiento.date_format' => 'La fecha de nacimiento debe tener el formato dd-mm-aaaa',
            'fecha_nacimiento.before' => 'La fecha de nacimiento debe ser anterior a la fecha actual',
            'image.image' => 'La imagen debe ser una imagen',
            'image.max' => 'La imagen debe tener un tamaño máximo de 2 MB',
            'image.mimes' => 'La imagen debe ser una imagen de tipo jpeg,png,jpg,gif,svg,webp',
            'genero.in' => 'El genero debe ser Masculino,Femenino',
            'genero.string' => 'El genero es requerido',
            'genero.min' => 'El genero es requerido',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator, response()->json(['errors' => $validator->errors()], 422));
    }
}
