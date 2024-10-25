<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Seccion;
use App\Models\User;

class SeccionPolicy
{
    public function create(User $user): Response
    {
        return $user->admin  ? Response::allow()
        : Response::deny('Debes ser administrador.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Seccion $Seccion): Response
    {
        return $user->admin  ? Response::allow()
        : Response::deny('Debes ser administrador.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Seccion $Seccion): Response
    {
        return $user->admin  ? Response::allow()
        : Response::deny('Debes ser administrador.');
    }
    public function viewStudents(User $user, Seccion $Seccion): Response
    {
        return $user->admin  ? Response::allow()
        : Response::deny('Debes ser administrador para poder ver los estudiantes.');
    }
}
