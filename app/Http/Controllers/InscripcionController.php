<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\InscripcionRequest;
use App\Http\Resources\InscripcionesResource;
use App\Models\Inscripcion;
use App\Models\Seccion;
use App\Models\User;
use App\Notifications\InscripcionCreada;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;

class InscripcionController extends Controller
{
    public function index()
    {
        $inscripciones = Inscripcion::with(['estudiante', 'seccion', 'year', 'ano_escolar'])
            ->whereIn('estado', ['pendiente', 'confirmada'])
            ->orderBy('created_at', 'desc')
            ->whereHas('ano_escolar', function ($query) {
                $query->where('habilitado', true);
            })
            ->paginate(10);

        $respuesta=[
            'inscripciones' => InscripcionesResource::collection($inscripciones->getCollection()),
            'pagination' => [
                'total' => $inscripciones->total(),
                'per_page' => $inscripciones->perPage(),
                'current_page' => $inscripciones->currentPage(),
                'last_page' => $inscripciones->lastPage(),
                'from' => $inscripciones->firstItem(),
                'to' => $inscripciones->lastItem(),
            ]
        ];
            
        return response()->json($respuesta, 200);
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

        $admins = User::where('admin', true)->get();

        // Enviar notificación a cada administrador
        Notification::send($admins, new InscripcionCreada($inscripcion));

        return response()->json(['mensaje' => 'Preinscripción realizada correctamente.']);
    }

    public function update(InscripcionRequest $request, Inscripcion $inscripcion)
    {
        Gate::authorize('update', $inscripcion);
        // Obtener la sección actual de la inscripción

        $seccionActual = Seccion::findOrFail($inscripcion->seccion_id);

        if ($inscripcion->estado === 'confirmada') {
            return response()->json(['mensaje' => 'No puedes actualizar una inscripción confirmada.'], 400);
        }
        // Obtener la nueva sección
        $nuevaSeccion = Seccion::where('id', $request->seccion_id)
        ->where('year_id', $request->year_id)
        ->first();
        
        if ($nuevaSeccion->capacidad <= 0) {
            return response()->json(['mensaje' => 'No hay más cupos disponibles.'], 400);
        }
        
        // Actualizar la inscripción con la nueva sección
        $inscripcion->update([
            'seccion_id' => $request->seccion_id,
            'year_id' => $request->year_id,
            'ano_escolar_id' => $request->ano_escolar_id,
        ]);
        
        // Incrementar la capacidad de la sección actual
        $seccionActual->increment('capacidad');
        // Reducir el cupo disponible en la nueva sección
        $nuevaSeccion->decrement('capacidad');

        return response()->json(['mensaje' => 'Inscripción actualizada correctamente.']);
    }


    public function destroy(Inscripcion $inscripcion)
    {
        Gate::authorize('delete', $inscripcion);

        if ($inscripcion->estado === 'confirmada') {
            return response()->json(['error' => 'No puede eliminarse una inscripción confirmada.'], 400);
        }
    
        try {
            $inscripcion->delete();
            return response()->json(['mensaje' => 'Inscripción eliminada correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar la inscripción: ' . $e->getMessage()], 500);
        }
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
