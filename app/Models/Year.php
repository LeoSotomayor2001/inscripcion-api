<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Year extends Model
{
    use HasFactory;

    protected $fillable=['year','descripcion'];

    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class);
    }
}
