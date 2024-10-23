<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ano_escolar extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'inicio',
        'fin',
        'habilitado'
    ];
    protected $table = 'ano_escolar';


    // Relación con Inscripciones: Un año escolar tiene muchas inscripciones
    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class);
    }

    // Relación con Secciones: Un año escolar tiene muchas secciones
    public function secciones()
    {
        return $this->hasMany(Seccion::class);
    }
    public function asignaturas()
    {
        return $this->hasMany(Asignatura::class);
    }

    // Si decides relacionar con estudiantes o profesores
    public function estudiantes()
    {
        return $this->hasMany(Estudiante::class);
    }

    public function profesores()
    {
        return $this->hasMany(User::class);
    }
}
