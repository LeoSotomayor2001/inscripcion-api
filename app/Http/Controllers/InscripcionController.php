<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Inscripcion;
use App\Models\Seccion;
use Illuminate\Http\Request;

class InscripcionController extends Controller
{
    public function index(){
        $inscripciones = Inscripcion::with(['estudiante', 'seccion', 'year','ano_escolar'])
            ->whereIn('estado', ['pendiente', 'confirmada'])
            ->get()
            ->map(fn($inscripcion) => [
                'id' => $inscripcion->id,
                'nombre' => $inscripcion->estudiante->name,
                'apellido' => $inscripcion->estudiante->apellido,
                'seccion' => $inscripcion->seccion->name,
                'año' => "{$inscripcion->year->year}",
                'estado' => $inscripcion->estado,
                'ano_escolar' => $inscripcion->ano_escolar->nombre
            ]);;
        return response()->json(['inscripciones' => $inscripciones]);
    }
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

     
        if ($seccion->capacidad <= 0) {
            return response()->json(['mensaje' => 'No hay más cupos disponibles.'], 400);
        }
        // Crear la inscripción con estado 'pendiente'
        $inscripcion = Inscripcion::create([
            'estudiante_id' => $request->estudiante_id,
            'seccion_id' => $request->seccion_id,
            'year_id' => $request->year_id,
            'estado' => 'pendiente',
            'ano_escolar_id' => $request->ano_escolar_id,
        ]);

        // Reducir el cupo disponible en la sección
        $seccion->decrement('capacidad');

        return response()->json(['mensaje' => 'Preinscripción realizada correctamente.']);
    }

   


    // Confirmación de inscripción
    public function confirmarInscripcion(Inscripcion $inscripcion)
    {
        if ($inscripcion->estado !== 'pendiente') {
            return response()->json(['error' => 'La inscripción ya ha sido confirmada.'], 400);
        }
        $inscripcion->estado = 'confirmada';
        $inscripcion->save();

        return response()->json(['mensaje' => 'Inscripción confirmada correctamente.']);
    }
}
