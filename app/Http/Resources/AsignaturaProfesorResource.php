<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AsignaturaProfesorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nombre' => $this->asignatura->nombre,
            'profesor' => $this->profesor->name . ' ' . $this->profesor->apellido,
            'seccion' => $this->seccion->name,
            'year' => $this->asignatura->year->year,
            'ano_escolar' => $this->asignatura->anoEscolar->nombre,
            'codigo' => $this->asignatura->codigo,
            'profesor_id' => $this->profesor_id,
            'seccion_id' => $this->seccion_id,
            'asignatura_id' => $this->asignatura_id
        ];
    }
}
