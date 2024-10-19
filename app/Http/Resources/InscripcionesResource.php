<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InscripcionesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->estudiante->name,
            'apellido' => $this->estudiante->apellido,
            'seccion' => $this->seccion->name,
            'año' => $this->year->year,
            'estado' => $this->estado,
            'ano_escolar' => $this->ano_escolar->nombre,
            'seccion_id' => $this->seccion_id,
            'ano_escolar_id' => $this->ano_escolar_id
        ];
    }
}
