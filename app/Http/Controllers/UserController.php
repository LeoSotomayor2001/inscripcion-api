<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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
        $user->update($request->all());
        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json(['message' => 'Usuario eliminado'], 200);
        }catch(ModelNotFoundException $e){
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }
    }
}
