<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Representante extends Model
{
    use HasFactory;
    protected $fillable=['name','apellido','email','cedula','password','telefono','ciudad','direccion'];

    public function estudiantes()
    {
        return $this->hasMany(Estudiante::class);
    }
}
