<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Ano_escolar;
use App\Models\User;

class Ano_escolarPolicy
{
    public function create(User $user): Response
    {
        return $user->admin  ? Response::allow()
        : Response::deny('Debes ser administrador.');
    }

    public function update(User $user, Ano_escolar $anoEscolar): Response
    {
        return $user->admin  ? Response::allow()
        : Response::deny('Debes ser administrador.');
    }

   
    public function delete(User $user, Ano_escolar $anoEscolar): Response
    {

        return $user->admin  ? Response::allow()
        : Response::deny('Debes ser administrador.');
    }
}
