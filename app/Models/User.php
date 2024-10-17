<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'apellido',
        'email',
        'cedula',
        'password',
        'admin'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function anoEscolar()
    {
        return $this->belongsTo(Ano_escolar::class);
    }

    // Relación con Secciones: Un profesor puede tener muchas secciones
    public function secciones()
    {
        return $this->hasMany(Seccion::class);
    }

    public function asignaturas()
    {
        return $this->belongsToMany(Asignatura::class, 'asignatura_profesor', 'profesor_id', 'asignatura_id')
                    ->withPivot('seccion_id')
                    ->withTimestamps();
    }
}
