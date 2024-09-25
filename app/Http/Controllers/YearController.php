<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Year;
use Illuminate\Http\Request;

class YearController extends Controller
{
     // Obtener todos los años
     public function index()
     {
         $years = Year::all();
         return response()->json($years);
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
}
