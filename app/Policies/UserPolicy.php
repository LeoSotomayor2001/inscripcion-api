<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\User;

class UserPolicy
{
   
    public function view(User $user): Response
    {
        return $user->admin  ? Response::allow()
        : Response::deny('Debes ser administrador.');
    }

    public function create(User $user): Response
    {
        return $user->admin  ? Response::allow()
        : Response::deny('Debes ser administrador.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): Response
    {
        return $user->admin  ? Response::allow()
        : Response::deny('Debes ser administrador.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): Response
    {
        return $user->admin  ? Response::allow()
        : Response::deny('Debes ser administrador.');
    }




}
