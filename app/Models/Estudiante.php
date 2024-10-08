<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    use HasFactory;

    protected $fillable=['name','apellido','cedula','fecha_nacimiento','representante_id','image','genero'];

    public function representante()
    {
        return $this->belongsTo(Representante::class);
    }

    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class);
    }

    public function anoEscolar()
    {
        return $this->belongsTo(Ano_escolar::class);
    }
}
