<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsignaturaProfesor extends Model
{
    protected $table = "asignatura_profesor";
    protected $fillable = ['asignatura_id', 'profesor_id', 'seccion_id'];
    use HasFactory;

    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class);
    }

    public function profesor()
    {
        return $this->belongsTo(User::class);
    }

    public function seccion()
    {
        return $this->belongsTo(Seccion::class);
    }
}
