<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AsignaturaResource extends JsonResource
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
            'descripcion' => $this->descripcion,
            'year_id' => $this->year_id,
            'codigo' => $this->codigo,
            'year' => $this->year ? $this->year->year : null,
            'ano_escolar' => $this->anoEscolar ? $this->anoEscolar->nombre : null,
            'ano_escolar_id' => $this->ano_escolar_id
        ];
    }
}
