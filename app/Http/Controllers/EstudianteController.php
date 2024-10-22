<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\EstudianteRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Http\Resources\EstudianteResource;
use App\Models\Estudiante;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EstudianteController extends Controller
{
    public function index()
    {
        return EstudianteResource::collection(Estudiante::with('representante')->orderBy('name', 'ASC')->paginate(10));
    }

    public function getAllStudents(){
        return EstudianteResource::collection(Estudiante::with('representante')->orderBy('name', 'ASC')->get());
    }


    public function store(EstudianteRequest $request)
    {
        $request->validated();
        $data = $request->all();
        if (isset($data['fecha_nacimiento'])) {
            $data['fecha_nacimiento'] = Carbon::createFromFormat('d-m-Y', $data['fecha_nacimiento'])->format('Y-m-d');
        }

         // Verificar si se está subiendo una nueva imagen
         if ($request->hasFile('image')) {
            // Eliminar la imagen anterior si existe
            if ($request->image) {
                Storage::disk('public')->delete('imagenes/' . $request->image);
            }

            // Almacenar la nueva imagen
            $imagePath = $request->file('image')->store('imagenes', 'public');
            $imageName = basename($imagePath);
            $data['image'] = $imageName;
        }
        $estudiante = Estudiante::create($data);

        return response()->json([
            'message' => 'Estudiante creado correctamente'
        ], 201);
    }


    public function update(UpdateStudentRequest $request, Estudiante $estudiante)
    {
        $data = $request->all();

        try {
            // Convertir la fecha de 'd-m-Y' a 'Y-m-d' si está presente en los datos
            if (isset($data['fecha_nacimiento'])) {
                $data['fecha_nacimiento'] = Carbon::createFromFormat('d-m-Y', $data['fecha_nacimiento'])->format('Y-m-d');
            }

            // Verificar si se está subiendo una nueva imagen
            if ($request->hasFile('image')) {
                // Eliminar la imagen anterior si existe
                if ($estudiante->image) {
                    Storage::disk('public')->delete('imagenes/' . $estudiante->image);
                }

                // Almacenar la nueva imagen
                $imagePath = $request->file('image')->store('imagenes', 'public');
                $imageName = basename($imagePath);
                $data['image'] = $imageName;
            }

            // Actualizar los datos del estudiante con la nueva información
            $estudiante->update($data);

            // Retornar el recurso con un código de éxito
            return response()->json([
                'mensaje' => 'Estudiante actualizado correctamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar el estudiante', 'message' => $e->getMessage()], 500);
        }
    }


    public function destroy(Request $request, Estudiante $estudiante)
    {
        // Verificar si el estudiante tiene inscripciones confirmadas o pendientes
        $inscripcionExistente = $estudiante->inscripciones()
            ->whereIn('estado', ['pendiente', 'confirmada'])
            ->first();
    
        // Si existe una inscripción con estado pendiente o confirmada, no se permite la eliminación
        if ($inscripcionExistente) {
            return response()->json([
                'mensaje' => 'No se puede eliminar el estudiante porque tiene una inscripción pendiente o confirmada.'
            ], 400);
        }
    
        // Ruta de la imagen a eliminar
        $path = "imagenes/{$estudiante->image}";
    
        // Verificar y eliminar la imagen si existe
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    
        // Eliminar el estudiante usando el método delete()
        $estudiante->delete();
    
        // Retornar respuesta JSON indicando que el estudiante fue eliminado
        return response()->json([
            'mensaje' => 'Estudiante eliminado correctamente'
        ], 200);
    }
    
}
