<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seccion extends Model
{
    use HasFactory;
    protected $table = 'secciones';
    protected $fillable = ['name', 'capacidad', 'year_id', 'ano_escolar_id'];

    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class);
    }

    public function year()
    {
        return $this->belongsTo(Year::class);
    }

    public function anoEscolar()
    {
        return $this->belongsTo(Ano_escolar::class);
    }

    public function profesores()
    {
        return $this->hasMany(User::class);
    }

  public function asignaturas()
    {
        return $this->belongsToMany(Asignatura::class, 'asignatura_profesor')
            ->withPivot('profesor_id')
            ->withTimestamps();
    }
}
