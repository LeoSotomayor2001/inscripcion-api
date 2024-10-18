<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AsignaturaProfesorRequest;
use App\Models\Asignatura;
use App\Models\AsignaturaProfesor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AsignaturaProfesorController extends Controller
{
    public function index()
    {
        // Cargar asignaturas con sus profesores y las secciones asociadas
        $asignaturas = Asignatura::with('profesores.secciones')->get()
            // Aplanar la colección resultante para evitar anidamientos innecesarios
            ->flatMap(function ($asignatura) {
                $result = [];
                foreach ($asignatura->profesores as $profesor) {
                    foreach ($profesor->secciones as $seccion) {
                        $result[] = [
                            'id' => $asignatura->id,
                            'nombre' => $asignatura->nombre,
                            'codigo' => $asignatura->codigo,
                            'profesor' => $profesor->name . ' ' . $profesor->apellido,
                            'seccion' => $seccion->name,
                            'year' => $asignatura->year->year,
                            'ano_escolar' => $asignatura->anoEscolar->nombre,
                            'seccion_id' => $seccion->id,
                            'profesor_id' => $profesor->id,
                        ];
                    }
                }
                return $result; // Devolver el array de resultados para esta asignatura
            });

        // Eliminar duplicados
        $uniqueAsignaturas = $asignaturas->unique(function ($item) {
            return $item['id'] . $item['profesor_id'] . $item['seccion_id']; // Combinar valores para identificar duplicados
        })->values(); // Reindexar la colección para eliminar los huecos

        // Devolver la respuesta en formato JSON con las asignaturas únicas
        return response()->json(['asignaturas' => $uniqueAsignaturas], 200);
    }

    public function store(AsignaturaProfesorRequest $request)
    {
        // Verificar si ya existe una asignación de la asignatura a un profesor en la sección
        $existe = AsignaturaProfesor::where('asignatura_id', $request->asignatura_id)
            ->where('seccion_id', $request->seccion_id)
            ->exists();

        if ($existe) {
            return response()->json('Esta asignatura ya tiene un profesor asignado para esta sección.', 400);
        }

        // Asignar la asignatura al profesor
        $asignatura = Asignatura::find($request->asignatura_id);
        $asignatura->profesores()->attach($request->profesor_id, ['seccion_id' => $request->seccion_id]);

        return response()->json('Profesor asignado correctamente a la asignatura', 201);
    }


    public function destroy(AsignaturaProfesorRequest $request)
    {

        // Realizar la eliminación específica
        AsignaturaProfesor::where('asignatura_id', $request->asignatura_id)
            ->where('profesor_id', $request->profesor_id)
            ->where('seccion_id', $request->seccion_id)
            ->delete();

        return response()->json('Profesor desasignado correctamente de la asignatura', 200);
    }
    public function getAsignaturasDeProfesor($profesor_id)
    {
        $profesor = User::with('asignaturas')->find($profesor_id);

        if (!$profesor) {
            return response()->json('Profesor no encontrado', 404);
        }

        return response()->json($profesor->asignaturas);
    }
}
