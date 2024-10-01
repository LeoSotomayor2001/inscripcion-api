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
            $secciones = Seccion::with('year', 'inscripciones')
                ->get()
                ->map(fn($seccion) => [
                    'id' => $seccion->id,
                    'año' => $seccion->year->year,
                    'nombre' => $seccion->name,
                    'estudiantes_preinscritos' => $seccion->inscripciones()->where('estado', 'pendiente')->count(),
                    'estudiantes_inscritos' => $seccion->inscripciones()->where('estado', 'confirmada')->count(),
                    'capacidad' => $seccion->capacidad,
                ]);
        }

        return response()->json($secciones);
    }


    // Obtener una sección específica
    public function show($id)
    {
        $seccion = Seccion::with('year', 'inscripciones')->find($id);

        if (!$seccion) {
            return response()->json(['mensaje' => 'Sección no encontrada'], 404);
        }

        $resultado = [
            'id' => $seccion->id,
            'año' => $seccion->year->descripcion,
            'Nombre' => $seccion->name,
            'estudiantes_preinscritos' => $seccion->inscripciones()->where('estado', 'pendiente')->count(),
            'estudiantes_inscritos' => $seccion->inscripciones()->where('estado', 'confirmada')->count(),
            'capacidad' => $seccion->capacidad,
        ];

        return response()->json($resultado);
    }
}
