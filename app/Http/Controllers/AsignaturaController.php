<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AsignaturaRequest;
use App\Models\Asignatura;
use Illuminate\Http\Request;

class AsignaturaController extends Controller
{
    
    public function index()
    {
        $asignaturas = Asignatura::all();
        return response()->json($asignaturas, 200);
    }

    public function show($id)
    {
        $asignatura = Asignatura::find($id);
        return response()->json($asignatura, 200);
    }

    public function store(AsignaturaRequest $request)
    {
        try{

            $asignatura = Asignatura::create($request->all());
            return response()->json('Asignatura creada correctamente', 201);
        }

        catch(\Exception $e){
            return response()->json($e->getMessage(), 500);
        }

    }

    public function update(Request $request, Asignatura $asignatura)
    {
        if (!$asignatura) {
            return response()->json('Asignatura no encontrada', 404);
        }
        try{
            $asignatura->update([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'year_id' => $request->year_id,
                'codigo' => $request->codigo
            ]);
            return response()->json(['Asignatura actualizada correctamente' => $asignatura] , 200);
        }
        catch(\Exception $e){
            return response()->json($e->getMessage(), 500);
        }

    }

    public function destroy($id)    
    {
        $asignatura = Asignatura::find($id);
        if (!$asignatura) {
            return response()->json('Asignatura no encontrada', 404);
        }
        $asignatura->delete();
        return response()->json('Asignatura eliminada correctamente', 200);
    }
}
