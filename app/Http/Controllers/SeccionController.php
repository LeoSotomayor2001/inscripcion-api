<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\SeccionRequest;
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
            $secciones = Seccion::with('year', 'inscripciones', 'anoEscolar')
            ->orderBy('year_id', 'asc')
                ->get()
                ->map(fn($seccion) => [
                    'id' => $seccion->id,
                    'año' => $seccion->year->year,
                    'nombre' => $seccion->name,
                    'estudiantes_preinscritos' => $seccion->inscripciones()->where('estado', 'pendiente')->count(),
                    'estudiantes_inscritos' => $seccion->inscripciones()->where('estado', 'confirmada')->count(),
                    'capacidad' => $seccion->capacidad,
                    'ano_escolar' => $seccion->anoEscolar->nombre
                ]);
        }

        return response()->json($secciones);
    }


    // Crear una sección
    public function store(SeccionRequest $request)
    {
        // Crear la sección
        $seccion = Seccion::create([
            'name' => $request->name,
            'year_id' => $request->year_id,
            'capacidad' => $request->capacidad,
            'ano_escolar_id' => $request->ano_escolar_id,
        ]);


        return response()->json('Sección creada correctamente', 201);
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


    // Actualizar una sección
    public function update(SeccionRequest $request, $id)
    {
        $seccion = Seccion::find($id);
        $inscriptions = $seccion->inscripciones->whereIn('estado', ['pendiente', 'confirmada'])->count();
        if (!$seccion) {
            return response()->json(['mensaje' => 'Sección no encontrada'], 404);
        }

        // Verificar que no se pueda reducir la capacidad
        if($request->capacidad < $inscriptions){
            return response()->json(['error' => 'No puedes reducir la capacidad porque hay '.$inscriptions.' inscripciones activas'], 400);
        }

        $seccion->update([
            'name' => $request->name,
            'year_id' => $request->year_id,
            'capacidad' => $request->capacidad,
            'ano_escolar_id' => $request->ano_escolar_id,
        ]);

        return response()->json('Sección actualizada correctamente', 200);
    }


    // Eliminar una sección
    public function destroy($id)
    {
        $seccion = Seccion::find($id);

        if (!$seccion) {
            return response()->json(['mensaje' => 'Sección no encontrada'], 404);
        }

        $inscriptions = $seccion->inscripciones->whereIn('estado', ['pendiente', 'confirmada'])->count();

        if ($inscriptions > 0) {
            return response()->json(['error' => 'No puedes eliminar la sección porque hay '.$inscriptions.' inscripciones activas'], 400);
        }

        $seccion->delete();

        return response()->json('Sección eliminada correctamente', 200);
    }
}