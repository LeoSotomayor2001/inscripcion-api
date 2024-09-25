<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Inscripcion;
use App\Models\Seccion;
use Illuminate\Http\Request;

class InscripcionController extends Controller
{
    public function store(Request $request)
    {
        $seccion = Seccion::where('id', $request->seccion_id)
            ->where('year_id', $request->year_id)
            ->first();

        // Verificar si el estudiante ya está preinscrito en cualquier sección en el mismo año
        $inscripcionExistente = Inscripcion::where('estudiante_id', $request->estudiante_id)
            ->whereIn('estado', ['pendiente', 'confirmada'])
            ->first();

        if ($inscripcionExistente) {
            return response()->json(['mensaje' => 'El estudiante ya está registrado.'], 400);
        }

        // Verificar el número de inscripciones confirmadas en la sección seleccionada
        $inscritosConfirmados = $seccion->inscripciones()->where('estado', 'confirmada')->count();
        $estudiantesPreinscritos = $seccion->inscripciones()->where('estado', 'pendiente')->count();
        if ($inscritosConfirmados >= $seccion->capacidad || $estudiantesPreinscritos >= $seccion->capacidad) {
            return response()->json(['mensaje' => 'No hay más cupos disponibles.'], 400);
        }


        // Crear la inscripción con estado 'pendiente'
        $inscripcion = Inscripcion::create([
            'estudiante_id' => $request->estudiante_id,
            'seccion_id' => $request->seccion_id,
            'year_id' => $request->year_id,
            'estado' => 'pendiente',
        ]);

        // Reducir el cupo disponible en la sección
        $seccion->decrement('capacidad');

        return response()->json(['mensaje' => 'Preinscripción realizada correctamente.']);
    }



    // Confirmación de inscripción
    public function confirmarInscripcion(Inscripcion $inscripcion)
    {
        $inscripcion->estado = 'confirmada';
        $inscripcion->save();

        return response()->json(['mensaje' => 'Inscripción confirmada.']);
    }
}
