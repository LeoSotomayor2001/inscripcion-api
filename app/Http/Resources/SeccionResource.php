<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SeccionResource extends JsonResource
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
            'año' => $this->year->year,
            'nombre' => $this->name,
            'estudiantes_preinscritos' => $this->inscripciones->where('estado', 'pendiente')->count(),
            'estudiantes_inscritos' => $this->inscripciones->where('estado', 'confirmada')->count(),
            'capacidad' => $this->capacidad,
            'ano_escolar' => $this->anoEscolar->nombre,
            'ano_escolar_id' => $this->ano_escolar_id,
        ];
    }
}
