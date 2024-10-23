<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnioEscolarRequest extends FormRequest
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
            'nombre' => 'required|string|min:3|max:20|unique:ano_escolar,nombre',
            'inicio' => 'required|date|after_or_equal:today',
            'fin' => 'required|date|after_or_equal:today',
            'habilitado' => 'required|boolean',
        ];
        if ($this->isMethod('patch') || $this->isMethod('put')) {
            $rules = [
                'nombre' => 'sometimes|string|min:3|max:20|',
                'inicio' => 'sometimes|date',
                'fin' => 'sometimes|date',
                'habilitado' => 'sometimes|boolean'
            ];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del periodo escolar es requerido',
            'nombre.min' => 'El nombre del periodo escolar debe tener mas de 3 caracteres',
            'nombre.max' => 'El nombre del periodo escolar debe tener menos de 20 caracteres',
            'nombre.unique' => 'Ese nombre ya está registrado',
            'nombre.string' => 'El nombre del periodo escolar debe ser una cadena de texto',
            'inicio.required' => 'La fecha de inicio es requerida',
            'inicio.date' => 'La fecha de inicio debe ser una fecha válida',
            'inicio.after_or_equal' => 'La fecha de inicio no puede ser anterior al día de hoy',
            'fin.after_or_equal' => 'La fecha de fin no puede ser anterior al día de hoy',
            'fin.required' => 'La fecha de fin es requerida',
            'fin.date' => 'La fecha de fin debe ser una fecha válida',
            'habilitado.required' => 'El estado de habilitado es requerido',
            'habilitado.boolean' => 'El estado de habilitado debe ser un valor booleano',
        ];
    }
    protected function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->inicio && $this->fin && strtotime($this->fin) < strtotime($this->inicio)) {
                $validator->errors()->add('fin', 'La fecha de fin no puede ser antes de la fecha de inicio.');
            }
        });
    }
}
