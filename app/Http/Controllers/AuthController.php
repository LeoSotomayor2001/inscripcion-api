<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RepresentanteRequest;
use App\Http\Resources\RepresentanteResource;
use App\Models\Representante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function login(LoginRequest $request){

        $data= $request->validated();
        if(!Auth::guard('representante')->attempt($data)){
            return response([
                'errors' => ['Credenciales incorrectas']
            ],422);
        }
        $representante = Auth::guard('representante')->user();
        return [
            'token' => $representante->createToken('token')->plainTextToken,
            'representante' => new RepresentanteResource($representante)
        ];

    }
    
}
