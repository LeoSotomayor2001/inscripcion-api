<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RepresentanteRequest;
use App\Http\Resources\RepresentanteResource;
use App\Http\Resources\UserResource;
use App\Models\Representante;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function register(RepresentanteRequest $request)
    {
        $request->validated();
        $representante = new Representante;
        $representante->name = $request->name;
        $representante->apellido = $request->apellido;
        $representante->email = $request->email;
        $representante->password = bcrypt($request->password);
        $representante->cedula = $request->cedula;
        $representante->telefono = $request->telefono;
        $representante->ciudad = $request->ciudad;
        $representante->direccion = $request->direccion;
        $representante->save();

        return response()->json([
            'message' => 'Registrado correctamente',
            'representante' => new RepresentanteResource($representante),
            'token' => $representante->createToken('token')->plainTextToken
        ], 201);
    }
    public function login(LoginRequest $request)
    {
        $data = $request->validated();
    
        if ($data['user_type'] === 'representante') {
            // Intentar encontrar al representante
            $representante = Representante::where('email', $data['email'])->first();
    
            if ($representante && Hash::check($data['password'], $representante->password)) {
                // Si no hay sesión activa, generar un nuevo token
                $token = $representante->createToken('token')->plainTextToken;
    
                return response()->json([
                    'token' => $token,
                    'representante' => new RepresentanteResource($representante)
                ]);
            }
        } elseif ($data['user_type'] === 'administrador') {
            // Intentar encontrar al usuario
            $usuario = User::where('email', $data['email'])->first();
    
            if ($usuario && Hash::check($data['password'], $usuario->password)) {
                // Si no hay sesión activa, generar un nuevo token
                $token = $usuario->createToken('token')->plainTextToken;
    
                return response()->json([
                    'token' => $token,
                    'usuario' => new UserResource($usuario)
                ]);
            }
        }
    
        return response()->json(['fail' => ['Credenciales incorrectas']], 422);
    }


    public function logout(Request $request)
    {
        //borrando el token
        $user = $request->user();
        $user->currentAccessToken()->delete();
        return [
            'mensaje' => 'Cierre de sesión exitoso'
        ];
    }
}
