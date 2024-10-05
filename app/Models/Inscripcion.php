<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscripcion extends Model
{
    use HasFactory;
    protected $table='inscripciones';
    protected $fillable=['seccion_id','estudiante_id','year_id','estado','ano_escolar_id'];
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class);
    }

    public function seccion()
    {
        return $this->belongsTo(Seccion::class);
    }

    public function year()
    {
        return $this->belongsTo(Year::class);
    }

    public function ano_escolar(){
        return $this->belongsTo(Ano_escolar::class);
    }
}
