<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seccion extends Model
{
    use HasFactory;
    protected $table='secciones';
    protected $fillable=['name','capacidad'];

    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class);
    }
}
