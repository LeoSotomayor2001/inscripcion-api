<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class LoginRequest extends FormRequest
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
            'email' => [
                'required',
                'email',
                function ($attribute, $value, $fail) {
                    $userExists = DB::table('users')->where('email', $value)->exists();
                    $representanteExists = DB::table('representantes')->where('email', $value)->exists();

                    if (!$userExists && !$representanteExists) {
                        $fail('El correo no existe.');
                    }
                },
            ],
            'password' => 'required',
            'user_type' => 'required|in:profesor,representante,administrador'
        ];
    }

    /**
     * Get the custom messages for validation errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.required' => 'El email es requerido',
            'email.email' => 'El email no es válido',
            'password.required' => 'La contraseña es requerida',
            'user_type.required' => 'El tipo de usuario es requerido',
            'user_type.in' => 'El tipo de usuario no es válido'
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator, response()->json(['errors' => $validator->errors()], 422));
    }
}
