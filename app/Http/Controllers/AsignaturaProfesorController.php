<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AsignaturaProfesorRequest;
use App\Http\Resources\AsignaturaProfesorResource;
use App\Models\Asignatura;
use App\Models\AsignaturaProfesor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AsignaturaProfesorController extends Controller
{
    public function index()
    {
        $asignaturas = AsignaturaProfesor::with('asignatura', 'profesor', 'seccion')->get();
        return response()->json(['asignaturas' => AsignaturaProfesorResource::collection($asignaturas)], 200);
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
        $asignatura=AsignaturaProfesor::where('asignatura_id', $request->asignatura_id)
            ->where('profesor_id', $request->profesor_id)
            ->where('seccion_id', $request->seccion_id) 
            ->delete();
        // Retornar una respuesta de éxito
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
