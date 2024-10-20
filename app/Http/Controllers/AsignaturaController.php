<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AsignaturaRequest;
use App\Http\Resources\AsignaturaResource;
use App\Models\Asignatura;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class AsignaturaController extends Controller
{

    public function index()
    {
        $asignaturas = Asignatura::with('anoEscolar')
        ->whereHas('anoEscolar', function ($query) {
            $query->where('habilitado', true);
        })
        ->paginate(10);

        $respuesta = [
            'asignaturas' => AsignaturaResource::collection($asignaturas->items()),
            'pagination' => [
                'total' => $asignaturas->total(),
                'per_page' => $asignaturas->perPage(),
                'current_page' => $asignaturas->currentPage(),
                'last_page' => $asignaturas->lastPage(),
                'from' => $asignaturas->firstItem(),
                'to' => $asignaturas->lastItem(),
            ]
        ];

        return response()->json($respuesta, 200);
    }

    public function allAsignaturas()
    {
        $asignaturas = Asignatura::with('anoEscolar')
        ->whereHas('anoEscolar', function ($query) {
            $query->where('habilitado', true);
        })
        -> orderBy('year_id', 'asc')->get();
        return response()->json(AsignaturaResource::collection($asignaturas), 200);
    }

    public function filtrarAsignaturas(Request $request)
    {
        $asignaturas = Asignatura::query()
            ->when($request->nombre, function ($query, $nombre) {
                return $query->where('nombre', 'LIKE', "%{$nombre}%");
            })
            ->when($request->year_id, function ($query, $year_id) {
                return $query->where('year_id', $year_id);
            })
            ->when($request->ano_escolar_id, function ($query, $ano_escolar_id) {
                return $query->where('ano_escolar_id', $ano_escolar_id);
            })
            ->get();
    
        return response()->json(AsignaturaResource::collection($asignaturas), 200);
    }
    

    public function show($id)
    {
        $asignatura = Asignatura::find($id);
        return response()->json($asignatura, 200);
    }

    public function store(AsignaturaRequest $request)
    {
        $asignaturaExistente = Asignatura::where('year_id', $request->year_id)->where('ano_escolar_id', $request->ano_escolar_id)
            ->where('nombre', $request->nombre)->first();

        if ($asignaturaExistente) {
            return response()->json(['error' => 'Asignatura ya registrada en este año'], 409);
        }
        try {

            $asignatura = Asignatura::create($request->all());
            return response()->json('Asignatura creada correctamente', 201);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function update(AsignaturaRequest $request, $id)
    {
        $asignatura = Asignatura::findOrFail($id);
        // Solo verifica si 'year_id' o 'ano_escolar_id' han cambiado
        if ($request->year_id !== $asignatura->year_id || $request->ano_escolar_id !== $asignatura->ano_escolar_id) {
            $asignaturaExistente = Asignatura::where('year_id', $request->year_id)
                ->where('ano_escolar_id', $request->ano_escolar_id)
                ->where('nombre', $request->nombre)
                ->first();

            if ($asignaturaExistente) {
                return response()->json(['error' => 'Asignatura ya registrada en este año'], 409);
            }
        }
        if ($asignatura->profesores()->count() > 0) {
            return response()->json(['error' => 'No se puede actualizar la asignatura porque tiene profesores asignados'], 400);
        }
        try {

            $asignatura->update([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'year_id' => $request->year_id,
                'codigo' => $request->codigo,
                'ano_escolar_id' => $request->ano_escolar_id
            ]);

            return response()->json(['message' => 'Asignatura actualizada correctamente'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Asignatura no encontrada'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function destroy($id)
    {
        $asignatura = Asignatura::find($id);

        if (!$asignatura) {
            return response()->json(['error' => 'Asignatura no encontrada'], 404);
        }

        // Verificar si la asignatura tiene profesores asignados
        if ($asignatura->profesores()->count() > 0) {
            return response()->json(['error' => 'No se puede eliminar la asignatura porque tiene profesores asignados'], 400);
        }

        $asignatura->delete();

        return response()->json(['message' => 'Asignatura eliminada correctamente'], 200);
    }
}
