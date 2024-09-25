<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\InscripcionController;
use App\Http\Controllers\RepresentanteController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function(){
    
    Route::middleware('auth:representante')->group(function () {
        Route::post('/logout-representante', [AuthController::class, 'logout']);     
        
    });

    Route::post('/inscripciones/{inscripcion}', [InscripcionController::class, 'confirmarInscripcion']);
    Route::post('/estudiantes/{estudiante}', [EstudianteController::class, 'update']);
    Route::post('/representantes/{representante}', [RepresentanteController::class, 'update']);
    Route::apiResource('/inscripciones', InscripcionController::class);
    Route::apiResource('/estudiantes', EstudianteController::class);
    Route::apiResource('/representantes', RepresentanteController::class);

})->middleware('auth:sanctum');


Route::get('/imagen/{filename}', [ImageController::class, 'show']);
Route::post('/register/representante',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login'])->name('login');
Route::apiResource('/users', UserController::class);
