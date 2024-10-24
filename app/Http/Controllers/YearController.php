<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\YearRequest;
use App\Http\Resources\YearResource;
use App\Models\Year;
use Illuminate\Http\Request;

class YearController extends Controller
{
     // Obtener todos los años
     public function index()
     {
         $years = Year::orderBy('year','asc')->get();
         return response()->json(YearResource::collection($years), 200);
     }
 
     // Obtener un año específico
     public function show($id)
     {
         $year = Year::find($id);
 
         if (!$year) {
             return response()->json(['mensaje' => 'Nivel académico no encontrado'], 404);
         }
 
         return response()->json($year);
     }

     public function store(YearRequest $request){

        Year::create($request->all());
        return response()->json('Nivel académico creado correctamente');
     }

     public function update(YearRequest $request,string $id){
        $year=Year::findOrFail($id);
        $yearExiste=Year::where('year',$request->year)->where('descripcion',$request->descripcion)->first();
        if($yearExiste && $year->descripcion !== $request->descripcion || $year->year !== $request->year ){
            return response()->json(['error' => 'Ese Nivel ya está registrado'], 404);
        }
        if(!$year){
            return response()->json(['error' => 'Nivel académico no encontrado'], 404);
        }

        $year->update($request->all());
        return response()->json('Nivel académico actualizado correctamente');
     }

     public function destroy(string $id){
        $year = Year::findOrFail($id);
        if($year->secciones->count() > 0){
            return response()->json(['message' => 'No se puede eliminar el nivel académico porque tiene secciones asignadas'], 403);
        }
        if($year->asignaturas->count() > 0){
            return response()->json(['message' => 'No se puede eliminar el nivel académico porque tiene asignaturas asignadas'], 403);
        }
        if($year->inscripciones->count() > 0){
            return response()->json(['message' => 'No se puede eliminar el nivel académico porque tiene inscripciones asignadas'], 403);
        }
        $year->delete();
        return response()->json(['message' => 'Nivel académico eliminado'], 200);
     }
}
