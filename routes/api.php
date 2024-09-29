<?php

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


    Route::post('/logout-representante', [AuthController::class, 'logout']);
    Route::post('/inscripciones/{inscripcion}', [InscripcionController::class, 'confirmarInscripcion']);
    Route::post('/estudiantes/{estudiante}', [EstudianteController::class, 'update']);
    Route::post('/representantes/{representante}', [RepresentanteController::class, 'update']);
    Route::get('/representantes/{id}/estudiantes', [RepresentanteController::class, 'getEstudiantes']);
    Route::get('/representantes/{id}/inscripciones', [RepresentanteController::class, 'obtenerEstudiantesPreinscritos']);
    Route::apiResource('/representantes', RepresentanteController::class);
    Route::apiResource('/estudiantes', EstudianteController::class);
    Route::apiResource('/inscripciones', InscripcionController::class);
})->middleware('auth:sanctum');

// Rutas para secciones
Route::get('/secciones', [SeccionController::class, 'index']);
Route::get('/secciones/{id}', [SeccionController::class, 'show']);

// Rutas para años
Route::get('/years', [YearController::class, 'index']);
Route::get('/years/{id}', [YearController::class, 'show']);
Route::get('/imagen/{filename}', [ImageController::class, 'show']);
Route::post('/register/representante', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::apiResource('/users', UserController::class);
