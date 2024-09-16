<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'name' => ['required', 'min:3','string', 'max:20'],
            'apellido' => ['required', 'string', 'max:20','min:3'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'admin' => ['required', 'boolean']
        ];
    
        if ($this->isMethod('patch') || $this->isMethod('put')) {
            $rules = [
                'name' => ['sometimes', 'string', 'max:20', 'min:3'],
                'apellido' => ['sometimes', 'string', 'max:20', 'min:3'],
                'email' => ['sometimes', 'email', 'max:255', 'unique:users,email,' . $this->route('user')], // Verifica que el email sea único en la tabla 'users', excepto para el usuario actual
                'password' => ['sometimes', 'string', 'min:8', 'confirmed'],
                'admin' => ['sometimes', 'boolean']
            ];
        }
    
        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es requerido',
            'name.min' => 'El nombre debe tener al menos 3 caracteres',
            'name.max' => 'El nombre debe tener un tamaño máximo de 20 caracteres',
            'name.string' => 'El nombre es requerido',
            'apellido.min' => 'El apellido debe tener al menos 3 caracteres',
            'apellido.string' => 'El apellido es requerido',
            'apellido.required' => 'El apellido es requerido',
            'apellido.max' => 'El apellido debe tener un tamaño máximo de 20 caracteres',
            'email.required' => 'El correo electrónico es requerido',
            'password.required' => 'La contraseña es requerida',
            'admin.required' => 'El rol es requerido',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'password.confirmed' => 'La confirmación de la contraseña no coincide',
            'email.unique' => 'El correo electrónico ya se encuentra registrado',
            'admin.boolean' => 'El rol debe ser verdadero o falso',
            'email.email' => 'El correo electrónico no es válido',
            'email.max' => 'El correo electrónico debe tener un tamaño máximo de 255 caracteres',

        ];
    }
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new \Illuminate\Validation\ValidationException($validator, response()->json(['errors' => $validator->errors()], 422));
    }
}
