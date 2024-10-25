<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AnioEscolarRequest;
use App\Http\Resources\AnioEscolarResource;
use App\Models\Ano_escolar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AnoEscolarController extends Controller
{
    public function index()
    {
        $anosEscolares = AnioEscolarResource::collection(Ano_escolar::all()); // Obtener todos los años escolares activos
        return response()->json($anosEscolares);
    }

    public function store(AnioEscolarRequest $request){

        Gate::authorize('create', Ano_escolar::class);
        Ano_escolar::create($request->all());
        return response()->json(['message' => 'Periodo escolar creado correctamente']);
    }

    public function update(AnioEscolarRequest $request,string $id){

        $anioEscolar=Ano_escolar::findOrFail($id);
        Gate::authorize('update', $anioEscolar);
        $anioExistente=Ano_escolar::where('nombre',$request->nombre)->first();
        if($anioExistente && $request->nombre !== $anioEscolar->nombre){
            return response()->json(['error' => 'Ese nombre ya existe'],403);
        }

        if(!$anioEscolar){
            return response()->json(['error' => 'No existe ese periodo escolar'],403);
        }
        if($request->nombre !== $anioEscolar->nombre){
            if($anioEscolar->inscripciones->count() > 0 || $anioEscolar->secciones->count() > 0 || $anioEscolar->asignaturas->count() > 0){
                return response()->json(['error' => 'No se puede cambiar el nombre del año escolar porque tiene inscripciones, secciones o asignaturas registradas'],403);
            }
            
        }
     
        $anioEscolar->update($request->all());
        return response()->json(['message' => 'Periodo escolar actualizado correctamente']);
    }
    
    public function destroy(string $id){
        $anioEscolar = Ano_escolar::findOrFail($id);
        Gate::authorize('delete', $anioEscolar);
        if($anioEscolar->inscripciones->count() > 0 || $anioEscolar->secciones->count() > 0 || $anioEscolar->asignaturas->count() > 0){
            return response()->json(['error' => 'No se puede eliminar el año escolar porque tiene inscripciones, secciones o asignaturas registradas'],403);
        }
        $anioEscolar->delete();
        return response()->json(['message' => 'Periodo escolar eliminado correctamente']);
    }
}
