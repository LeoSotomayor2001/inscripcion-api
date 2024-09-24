<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function show($filename)
    {
        $imageURL = "/imagenes/" . $filename ;
        // Verifica si la imagen existe
        if (!Storage::disk('public')->exists($imageURL)) {
            return response()->json(['error' => 'Image not found'], 404);
        }

        // Devuelve la imagen
        return response()->file(Storage::disk('public')->path($imageURL));
    }
}
