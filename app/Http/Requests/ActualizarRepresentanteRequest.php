<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class ActualizarRepresentanteRequest extends FormRequest
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
            'name' => ['sometimes', 'string', 'max:20', 'min:3'],
            'apellido' => ['sometimes', 'string', 'max:20', 'min:3'],
            'email' => ['sometimes', 'email', 'max:255', 'unique:representantes,email,' . $this->route('representante')->id],
            'cedula' => ['sometimes', 'regex:/^[0-9]{6,9}$/', 'unique:representantes,cedula,' . $this->route('representante')->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'telefono' => 'sometimes|regex:/^[0-9]{11}$/',
            'ciudad' => ['sometimes', 'string', 'max:35', 'min:3'],
            'direccion' => ['sometimes', 'string', 'max:255', 'min:3'],
            'image' => ['nullable', 'image', 'max:2048', 'mimes:jpeg,png,jpg,gif,svg'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es requerido',
            'name.min' => 'El nombre debe tener al menos 3 caracteres',
            'name.max' => 'El nombre debe tener un tamaño máximo de 20 caracteres',
            'name.string' => 'El nombre es requerido',
            'apellido.min' => 'El apellido debe tener al menos 3 caracteres',
            'apellido.string' => 'El apellido es requerido',
            'apellido.required' => 'El apellido es requerido',
            'apellido.max' => 'El apellido debe tener un tamaño máximo de 20 caracteres',
            'email.required' => 'El correo electrónico es requerido',
            'email.unique' => 'El correo electrónico ya está registrado',
            'email.email' => 'El correo electrónico debe ser un correo válido',
            'password.required' => 'La contraseña es requerida',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'password.confirmed' => 'La confirmación de la contraseña no coincide',
            'cedula.required' => 'La cédula es requerida',
            'cedula.regex' => 'La cédula debe tener mínimo 6 números y máximo 9',
            'cedula.unique' => 'La cédula ya está registrada',
            'telefono.required' => 'El teléfono es requerido',
            'telefono.regex' => 'El teléfono debe ser un valor numérico válido de 11 dígitos',
            'ciudad.required' => 'La ciudad es requerida',
            'ciudad.min' => 'La ciudad debe tener al menos 3 caracteres',
            'ciudad.max' => 'La ciudad debe tener un tamaño máximo de 35 caracteres',
            'ciudad.string' => 'La ciudad es requerida',
            'direccion.required' => 'La dirección es requerida',
            'direccion.min' => 'La dirección debe tener al menos 3 caracteres',
            'direccion.max' => 'La dirección debe tener un tamaño máximo de 255 caracteres',
            'direccion.string' => 'La dirección es requerida',
            'image.image' => 'El archivo debe ser una imagen',
            'image.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif o svg',
            'image.max' => 'La imagen no debe exceder los 2048 KB',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     * @throws ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator, response()->json(['errors' => $validator->errors()], 422));
    }
}
