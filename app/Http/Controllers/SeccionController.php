<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\SeccionRequest;
use App\Models\Seccion;
use Carbon\Carbon;
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
                    'ano_escolar' => $seccion->anoEscolar->nombre,
                    'ano_escolar_id' => $seccion->ano_escolar_id,
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

    public function getEstudiantes($id)
    {
        $seccion = Seccion::with('inscripciones')->find($id);

        if (!$seccion) {
            return response()->json(['error' => 'Sección no encontrada.'], 404);
        }

        $inscripciones = $seccion->inscripciones()->where('estado', 'confirmada')->get();
        // Ordenar por cédula
        $inscripciones = $inscripciones->sortBy(function ($inscripcion) {
            return $inscripcion->estudiante->cedula;
        });

        $estudiantes = $inscripciones->map(function ($inscripcion) {
            return [
                'id' => $inscripcion->id,
                'nombre_completo' => $inscripcion->estudiante->name . ' ' . $inscripcion->estudiante->apellido,
                'cedula' => $inscripcion->estudiante->cedula,
                'fecha_nacimiento' => Carbon::parse($inscripcion->estudiante->fecha_nacimiento)->format('d-m-Y'), // Formatear la fecha
                'genero' => $inscripcion->estudiante->genero,
            ];
        })->values(); // Reindexar la colección

        return response()->json($estudiantes);
    }




    // Actualizar una sección
    public function update(SeccionRequest $request, $id)
    {
        $seccion = Seccion::find($id);

        if (!$seccion) {
            return response()->json(['mensaje' => 'Sección no encontrada'], 404);
        }

        // Contar inscripciones activas
        $inscriptions = $seccion->inscripciones()->whereIn('estado', ['pendiente', 'confirmada'])->count();

        // Validar si la capacidad está siendo reducida
        if ($request->capacidad < $seccion->capacidad && $request->capacidad < $inscriptions) {
            return response()->json(['error' => 'No puedes reducir la capacidad porque hay ' . $inscriptions . ' inscripciones activas'], 400);
        }
        $inscripcionesConfirmadas = $seccion->inscripciones()->where('estado', 'confirmada')->count();
        if($inscripcionesConfirmadas > 0){
            return response()->json(['error' => 'No puedes actualizar la sección porque hay ' . $inscriptions . ' inscripciones confirmadas'], 400);
        }

        // Actualizar la sección
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
            return response()->json(['error' => 'No puedes eliminar la sección porque hay ' . $inscriptions . ' inscripciones activas'], 400);
        }

        $seccion->delete();

        return response()->json('Sección eliminada correctamente', 200);
    }
}
