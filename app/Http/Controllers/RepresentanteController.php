<?php

namespace App\Http\Controllers;

use App\Models\odel;
use App\Http\Controllers\Controller;
use App\Http\Requests\RepresentanteRequest;
use App\Http\Resources\RepresentanteResource;
use App\Models\Representante;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class RepresentanteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return RepresentanteResource::collection(Representante::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RepresentanteRequest $request)
    {
        $request->validated();
        $representante = new Representante;
        $representante->name = $request->name;
        $representante->apellido = $request->apellido;
        $representante->email = $request->email;
        $representante->password = bcrypt($request->password);
        $representante->cedula = $request->cedula;
        $representante->telefono = $request->telefono;
        $representante->ciudad = $request->ciudad;
        $representante->direccion = $request->direccion;
        $representante->save();

        return response()->json($representante, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $representante = Representante::findOrFail($id);
            return new RepresentanteResource($representante);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Representante no encontrado'], 404);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(RepresentanteRequest $request, string $id)
    {
        $request->validated();
        try {
            $representante = Representante::findOrFail($id);
            $representante->update($request->all());
            return new RepresentanteResource($representante);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Representante no encontrado'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $representante = Representante::findOrFail($id);
            $representante->delete();
            return response()->json(['message' => 'Representante eliminado'], 200);
        }catch(ModelNotFoundException $e){
            return response()->json(['error' => 'Representante no encontrado'], 404);
        }
    }
}
