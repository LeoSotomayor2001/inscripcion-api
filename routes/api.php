<?php

use App\Http\Controllers\AnoEscolarController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\InscripcionController;
use App\Http\Controllers\RepresentanteController;
use App\Http\Controllers\SeccionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\YearController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // Rutas para inscripciones
    Route::post('/inscripciones/{inscripcion}/confirmar', [InscripcionController::class, 'confirmarInscripcion']);
    Route::put('/inscripciones/{inscripcion}', [InscripcionController::class, 'update']);
    Route::delete('/inscripciones/{inscripcion}', [InscripcionController::class, 'destroy']);
    Route::apiResource('/inscripciones', InscripcionController::class);
    
    // Rutas para estudiantes
    Route::post('/estudiantes/{estudiante}', [EstudianteController::class, 'update']);
    Route::apiResource('/estudiantes', EstudianteController::class);
    
    // Rutas para representantes
    Route::get('/representantes/{id}/inscripciones', [RepresentanteController::class, 'obtenerEstudiantesPreinscritos']);
    Route::get('/representantes/{id}/estudiantes', [RepresentanteController::class, 'getEstudiantes']);
    Route::post('/representantes/{representante}', [RepresentanteController::class, 'update']);
    Route::apiResource('/representantes', RepresentanteController::class);
    
    // Rutas para secciones
    Route::get('/secciones/{id}/estudiantes', [SeccionController::class, 'getEstudiantes']);
    Route::get('/secciones', [SeccionController::class, 'index']);
    Route::get('/secciones/{id}', [SeccionController::class, 'show']);
    Route::post('/secciones', [SeccionController::class, 'store']);
    Route::put('/secciones/{id}', [SeccionController::class, 'update']);
    Route::delete('/secciones/{id}', [SeccionController::class, 'destroy']);

    // Rutas para usuarios
    Route::apiResource('/users', UserController::class);

    // Rutas para años escolares
    Route::get('/anos-escolares', [AnoEscolarController::class, 'index']);

});



// Rutas para años
Route::get('/years', [YearController::class, 'index']);
Route::get('/years/{id}', [YearController::class, 'show']);
Route::get('/imagen/{filename}', [ImageController::class, 'show']);
Route::post('/register/representante', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
