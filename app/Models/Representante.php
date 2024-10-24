<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Representante extends Authenticatable
{
    use HasFactory,HasApiTokens,Notifiable;
    protected $table='representantes';
    protected $fillable=['name','apellido','email','cedula','password','telefono','ciudad','direccion','image'];


    public function estudiantes()
    {
        return $this->hasMany(Estudiante::class);
    }
}
