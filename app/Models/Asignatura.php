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
    ];

    public function profesores()
    {
        return $this->belongsToMany(User::class, 'asignatura_profesor')
                    ->withPivot('seccion_id')
                    ->withTimestamps();
    }
    
}
