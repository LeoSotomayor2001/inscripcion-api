<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\SeccionRequest;
use App\Http\Resources\SeccionResource;
use App\Models\Seccion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SeccionController extends Controller
{
    // Obtener todas las secciones
    public function index()
    {
        $secciones = Seccion::with('year', 'inscripciones', 'anoEscolar')
            ->orderBy('year_id', 'asc')
            ->orderBy('name', 'asc')
            ->whereHas('anoEscolar', function ($query) {
                $query->where('habilitado', true);
            })
            ->paginate(10);

        $respuesta = [
            'secciones' => SeccionResource::collection($secciones->getCollection()),
            'pagination' => [
                'total' => $secciones->total(),
                'per_page' => $secciones->perPage(),
                'current_page' => $secciones->currentPage(),
                'last_page' => $secciones->lastPage(),
                'from' => $secciones->firstItem(),
                'to' => $secciones->lastItem(),
            ],
        ];
        return response()->json($respuesta, 200);
    }

    public function getAllSecciones()
    {
        $secciones = Seccion::with('year', 'inscripciones', 'anoEscolar')
        ->whereHas('anoEscolar', function ($query) {
            $query->where('habilitado', true);
        })
        ->get();
        return response()->json(['secciones' => SeccionResource::collection($secciones)], 200);
    }


    public function buscarPorYearId(Request $request)
    {
        $yearId = $request->query('year_id');
        if ($yearId) {
            $secciones = Seccion::where('year_id', $yearId)
                ->with('year', 'inscripciones', 'anoEscolar')
                ->get();

            return response()->json(['secciones' => SeccionResource::collection($secciones)], 200);
        }

        return response()->json(['error' => 'Año no especificado'], 400);
    }



    // Crear una sección
    public function store(SeccionRequest $request)
    {
        Gate::authorize('create', Seccion::class);
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
        Gate::authorize('viewStudents', $seccion);
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
        Gate::authorize('update', $seccion);
        if (!$seccion) {
            return response()->json(['mensaje' => 'Sección no encontrada'], 404);
        }

        // Contar inscripciones activas
        $inscriptions = $seccion->inscripciones()->whereIn('estado', ['pendiente', 'confirmada'])->count();

        // Validar si la capacidad está siendo reducida
        if ($request->capacidad < $seccion->capacidad && $request->capacidad < $inscriptions) {
            return response()->json(['error' => 'No puedes reducir la capacidad porque hay ' . $inscriptions . ' inscripciones activas'], 400);
        }

        // Verificar si hay inscripciones confirmadas
        $inscripcionesConfirmadas = $seccion->inscripciones()->where('estado', 'confirmada')->count();
        if ($inscripcionesConfirmadas > 0) {
            // Si hay inscripciones confirmadas, solo permitir actualizar la capacidad
            if ($request->capacidad != $seccion->capacidad) {
                $seccion->capacidad = $request->capacidad;
                $seccion->save();
                return response()->json('Capacidad de la sección actualizada correctamente', 200);
            } else {
                return response()->json(['error' => 'Solo puedes actualizar la capacidad de la sección si hay inscripciones confirmadas'], 400);
            }
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
        Gate::authorize('delete', $seccion);
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
