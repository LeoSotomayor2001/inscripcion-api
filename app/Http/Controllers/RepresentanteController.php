<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ActualizarRepresentanteRequest;

use App\Http\Resources\RepresentanteResource;
use App\Models\Estudiante;
use App\Models\Inscripcion;
use App\Models\Representante;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class RepresentanteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return RepresentanteResource::collection(Representante::all());
    }

    public function show(string $id)
    {
        try {
            $representante = Representante::findOrFail($id);
            return new RepresentanteResource($representante);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Representante no encontrado'], 404);
        }
    }

    public function update(ActualizarRepresentanteRequest $request, Representante $representante)
    {

       
        $data = $request->all();
        //Verificar si el representante autenticado puede actualizar sus datos
         if ($representante->id !== Auth::user()->id) {
                return response()->json(['error' => 'No puedes actualizar estos datos'], 403);
            }
            

        try {
            // Verificar si se está subiendo una nueva imagen
            if ($request->hasFile('image')) {
                // Eliminar la imagen anterior si existe
                if ($representante->image) {
                    Storage::disk('public')->delete('imagenes/' . $representante->image);
                }

                // Almacenar la nueva imagen
                $imagePath = $request->file('image')->store('imagenes', 'public');
                $imageName = basename($imagePath);
                $data['image'] = $imageName;
            }

            // Verificar si se proporcionó una nueva contraseña
            if (!empty($request->input('password'))) {
                // Encriptar la nueva contraseña
                $data['password'] = bcrypt($request->input('password'));
            } else {
                // Si no se proporciona una nueva contraseña, eliminar el campo del array de datos
                unset($data['password']);
            }

            // Actualizar los datos del representante
            $representante->update($data);

            // Retornar el recurso con un código de éxito
            return response()->json(['mensaje' => 'Datos actualizados correctamente'], 200);
        } catch (\Exception $e) {
            // Manejo genérico de excepciones
            return response()->json(['error' => 'Error al actualizar los datos'], 500);
        }
    }

    public function getEstudiantes(string $id)
    {
        try {

            $representante = Representante::findOrFail($id);
            $estudiantes = $representante->estudiantes;
            return response()->json(['estudiantes' => $estudiantes], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Representante no encontrado'], 404);
        }
    }

    public function obtenerEstudiantesPreinscritos($representanteId)
    {
        $inscripciones = Inscripcion::with(['estudiante', 'seccion', 'year','ano_escolar'])
            ->whereIn('estado', ['pendiente', 'confirmada'])
            ->whereHas('estudiante', fn($query) => $query->where('representante_id', $representanteId))
            ->get()
            ->map(fn($inscripcion) => [
                'nombre' => $inscripcion->estudiante->name,
                'apellido' => $inscripcion->estudiante->apellido,
                'seccion' => $inscripcion->seccion->name,
                'año' => "{$inscripcion->year->year}",
                'estado' => $inscripcion->estado,
                'ano_escolar' => $inscripcion->ano_escolar->nombre
            ]);

        return response()->json(['inscripciones' => $inscripciones]);
    }

    public function destroy(string $id)
    {
        try {
            $representante = Representante::findOrFail($id);
            $representante->delete();
            return response()->json(['message' => 'Representante eliminado'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Representante no encontrado'], 404);
        }
    }
}
