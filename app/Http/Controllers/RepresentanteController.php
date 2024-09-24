<?php

namespace App\Http\Controllers;

use App\Models\odel;
use App\Http\Controllers\Controller;
use App\Http\Requests\ActualizarRepresentanteRequest;
use App\Http\Requests\RepresentanteRequest;
use App\Http\Resources\RepresentanteResource;
use App\Models\Representante;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
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
    
            // Actualizar los datos del representante
            $representante->update($data);
    
            // Retornar el recurso con un código de éxito
            return response()->json(['representante' => new RepresentanteResource($representante)], 200);
        } catch (\Exception $e) {
            // Manejo genérico de excepciones
            return response()->json(['error' => 'Error al actualizar el representante'], 500);
        }
        
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
