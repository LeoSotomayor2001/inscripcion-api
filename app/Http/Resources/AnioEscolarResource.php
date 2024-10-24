<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnioEscolarResource extends JsonResource
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
            'nombre' => $this->nombre,
            'inicio' => $this->inicio,
            'fin' => $this->fin,
            'habilitado' => $this->habilitado,
            'inscripciones' => $this->inscripciones()->count(),
            'secciones' => $this->secciones()->count(),
            'asignaturas' => $this->asignaturas()->count(),

        ];
    }
}
