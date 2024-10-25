<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Year;
use App\Models\User;

class YearPolicy
{
    
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return $user->admin  ? Response::allow()
        : Response::deny('Debes ser administrador.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Year $year): Response
    {
        return $user->admin  ? Response::allow()
        : Response::deny('Debes ser administrador.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Year $year): Response
    {
        return $user->admin  ? Response::allow()
        : Response::deny('Debes ser administrador.');
    }

}
