<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Inscripcion;
use App\Models\User;

class InscripcionPolicy
{
   

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Inscripcion $inscripcion): bool
    {
        
        return $user->admin;
    }

    public function confirmar(User $user, Inscripcion $inscripcion): Response
    {
        return $user->admin  ? Response::allow()
        : Response::deny('Debes ser administrador.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Inscripcion $inscripcion): bool
    {
        return $user->admin;
    }

}
