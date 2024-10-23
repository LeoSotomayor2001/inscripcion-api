<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AnioEscolarRequest;
use App\Models\Ano_escolar;
use Illuminate\Http\Request;

class AnoEscolarController extends Controller
{
    public function index()
    {
        $anosEscolares = Ano_escolar::all(); // Obtener todos los años escolares activos
        return response()->json($anosEscolares);
    }

    public function store(AnioEscolarRequest $request){

        Ano_escolar::create($request->all());
        return response()->json(['message' => 'Periodo escolar creado correctamente']);
    }

    public function update(AnioEscolarRequest $request,string $id){

        $anioEscolar=Ano_escolar::findOrFail($id);
        $anioExistente=Ano_escolar::where('nombre',$request->nombre);
        if($anioExistente && $request->nombre !== $anioEscolar->nombre){
            return response()->json(['error' => 'Ese nombre ya existe'],403);
        }
        if(!$anioEscolar){
            return response()->json(['error' => 'No existe ese periodo escolar'],403);
        }
        if($anioEscolar->inscripciones->count() > 0){
            return response()->json(['error' => 'No se puede editar un periodo escolar con inscripciones registradas'],403);
        }
        if($anioEscolar->secciones->count() > 0){
            return response()->json(['error' => 'No se puede editar un periodo escolar con secciones registradas'],403);
        }
        if($anioEscolar->asignaturas->count() > 0){
            return response()->json(['error' => 'No se puede editar un periodo escolar con asignaturas registradas'],403);
        }

        $anioEscolar->update($request->all());
        return response()->json(['message' => 'Periodo escolar actualizado correctamente']);
    }
}
