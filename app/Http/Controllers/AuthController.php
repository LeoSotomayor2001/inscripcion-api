<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RepresentanteRequest;
use App\Http\Resources\RepresentanteResource;
use App\Models\Representante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    
    public function register(RepresentanteRequest $request){
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
            'message'=> 'Registrado correctamente',
            'representante' => new RepresentanteResource($representante),
            'token' => $representante->createToken('token')->plainTextToken ], 201);
    }
    public function login(LoginRequest $request)
    {
        $data = $request->validated();
    
        // Intentar encontrar al representante
        $representante = Representante::where('email', $data['email'])->first();
    
        if (!$representante || !Hash::check($data['password'], $representante->password)) {
            return response()->json(['fail' => ['Credenciales incorrectas']], 422);
        }
    
        // Si las credenciales son válidas, generar un token
        return response()->json([
            'token' => $representante->createToken('token')->plainTextToken,
            'representante' => new RepresentanteResource($representante)
        ]);
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
