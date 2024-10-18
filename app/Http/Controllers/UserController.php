<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        return UserResource::collection(User::all());
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $request->validated();
        
        $user = new User;
        $user->name = $request->name;
        $user->apellido = $request->apellido;
        $user->cedula = $request->cedula;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->admin = $request->admin;
        $user->save();

        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $user = User::findOrFail($id);
            return new UserResource($user);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }
    }
    

    public function update(UserRequest $request, string $id)
    {
        $request->validated();
        $user = User::findOrFail($id);
        $usurioAutenticado = Auth::user();
        // Validar si el usuario autenticado es el mismo que se quiere actualizar
        if($usurioAutenticado->id == $id && $request->admin == 0){
            return response()->json(['message' => 'No puedes modificarte los permisos de administrador'], 403);
        }
        $cantidadAdmins=User::where('admin',1)->count();
        if($cantidadAdmins< 2 && $user->admin == 1 && $request->admin == 0){
            return response()->json(['message' => 'No puedes quitarle la autoridad al último administrador'], 403);
        }
        $user->update($request->all());
        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $usurioAutenticado = Auth::user();
        if($usurioAutenticado->id == $id){
            return response()->json(['message' => 'No te puedes eliminar a ti mismo'], 403);
        }
      
        try{
            $user = User::findOrFail($id);
            $cantidadAsignaturas=$user->asignaturas->count();
            if($cantidadAsignaturas > 0){
                return response()->json(['message' => 'No se puede eliminar al profesor porque tiene asignaturas asignadas'], 403);
            }
            $cantidadAdmins=User::where('admin',1)->count();
            if($cantidadAdmins< 2 && $user->admin == 1){
                return response()->json(['message' => 'No se puede eliminar el último administrador'], 403);
            }
            $user->delete();
            return response()->json(['message' => 'Profesor eliminado'], 200);
        }catch(ModelNotFoundException $e){
            return response()->json(['message' => 'Profesor no encontrado'], 404);
        }
    }
}
