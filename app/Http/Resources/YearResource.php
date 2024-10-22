<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class YearResource extends JsonResource
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
            'year' => $this->year,
            'descripcion'=> $this->descripcion,
            'secciones' => $this->secciones->count(),
            'asignaturas' => $this->asignaturas->count(),
            'inscripciones' => $this->inscripciones->count(),
        ];
    }
}
