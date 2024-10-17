<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asignatura extends Model
{
    use HasFactory;
    protected $table='asignaturas';
    protected $fillable = [
        'nombre',
        'codigo',
        'descripcion',
        'year_id',
        'ano_escolar_id',
    ];

    public function profesores()
    {
        return $this->belongsToMany(User::class, 'asignatura_profesor', 'asignatura_id', 'profesor_id')
                    ->withPivot('seccion_id')
                    ->withTimestamps();
    }
    
    public function year(){
        return $this->belongsTo(Year::class);
    }

    public function anoEscolar(){
        return $this->belongsTo(Ano_escolar::class);
    }
    
}
