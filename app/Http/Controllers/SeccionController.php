<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Seccion;
use Illuminate\Http\Request;

class SeccionController extends Controller
{
    // Obtener todas las secciones
    public function index(Request $request)
    {
        // Obtener el 'year_id' si existe en la solicitud
        $yearId = $request->query('year_id');
    
        // Si 'year_id' está presente, filtrar las secciones por ese año
        if ($yearId) {
            $secciones = Seccion::where('year_id', $yearId)->get();
        } else {
            // Si no hay 'year_id', devolver todas las secciones
            $secciones = Seccion::all();
        }
    
        return response()->json($secciones);
    }
    

    // Obtener una sección específica
    public function show($id)
    {
        $seccion = Seccion::find($id);

        if (!$seccion) {
            return response()->json(['mensaje' => 'Sección no encontrada'], 404);
        }

        return response()->json($seccion);
    }
}
