<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ano_escolar;
use Illuminate\Http\Request;

class AnoEscolarController extends Controller
{
    public function index()
    {
        $anosEscolares = Ano_escolar::where('habilitado', true)->get(); // Obtener todos los años escolares activos
        return response()->json($anosEscolares);
    }
}
