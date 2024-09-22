<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\EstudianteRequest;
use App\Http\Resources\EstudianteResource;
use App\Models\Estudiante;
use Carbon\Carbon;

class EstudianteController extends Controller
{
    public function index()
    {
        return EstudianteResource::collection(Estudiante::with('representante')->get());
    }
    

    public function store(EstudianteRequest $request)
    {
        $request->validated();
    
        // Convertir la fecha de 'd-m-Y' a 'Y-m-d'
        $fecha_nacimiento = Carbon::createFromFormat('d-m-Y', $request->fecha_nacimiento)->format('Y-m-d');
    
        $estudiante = Estudiante::create([
            'name' => $request->name,
            'apellido' => $request->apellido,
            'cedula' => $request->cedula,
            'fecha_nacimiento' => $fecha_nacimiento,
            'representante_id' => $request->representante_id,
        ]);
    
        return response()->json(['estudiante' => new EstudianteResource($estudiante)], 201);
    }
    
}
