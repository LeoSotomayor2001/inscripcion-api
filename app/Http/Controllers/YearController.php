<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
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
             return response()->json(['mensaje' => 'Año no encontrado'], 404);
         }
 
         return response()->json($year);
     }

     public function destroy(string $id){
        $year = Year::findOrFail($id);
        if($year->secciones->count() > 0){
            return response()->json(['message' => 'No se puede eliminar el año porque tiene secciones asignadas'], 403);
        }
        if($year->asignaturas->count() > 0){
            return response()->json(['message' => 'No se puede eliminar el año porque tiene asignaturas asignadas'], 403);
        }
        if($year->inscripciones->count() > 0){
            return response()->json(['message' => 'No se puede eliminar el año porque tiene inscripciones asignadas'], 403);
        }
        $year->delete();
        return response()->json(['message' => 'Año eliminado'], 200);
     }
}
