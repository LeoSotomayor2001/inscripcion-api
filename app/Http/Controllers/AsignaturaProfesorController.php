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
        $asignaturas = Asignatura::with('profesores.secciones')->get()
            ->flatMap(function ($asignatura) {
                return $asignatura->profesores->map(function ($profesor) use ($asignatura) {
                    return $profesor->secciones->map(function ($seccion) use ($asignatura, $profesor) {
                        return [
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
                    });
                })->collapse();
            });

        return response()->json(['asignaturas' => $asignaturas], 200 );
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
