<?php

namespace App\Policies;


use App\Models\Representante;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class RepresentantePolicy
{
    use HandlesAuthorization;
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }


    public function update(Representante $representante)
    {
        $user = Auth::user();
        
        return $user->id === $representante->id
            ? Response::allow()
            : Response::deny('No puedes actualizar este representante.');
    }
}
