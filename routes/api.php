<?php

use App\Http\Controllers\AnoEscolarController;
use App\Http\Controllers\AsignaturaController;
use App\Http\Controllers\AsignaturaProfesorController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\InscripcionController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RepresentanteController;
use App\Http\Controllers\SeccionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\YearController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // Rutas para inscripciones
    Route::get('/inscripciones-all', [InscripcionController::class, 'getAllInscripciones']);
    Route::post('/inscripciones/{inscripcion}/confirmar', [InscripcionController::class, 'confirmarInscripcion']);
    Route::put('/inscripciones/{inscripcion}', [InscripcionController::class, 'update']);
    Route::delete('/inscripciones/{inscripcion}', [InscripcionController::class, 'destroy']);
    Route::apiResource('/inscripciones', InscripcionController::class);
    
    // Rutas para estudiantes
    Route::post('/estudiantes/{estudiante}', [EstudianteController::class, 'update']);
    Route::get('/estudiantes-all',[EstudianteController::class, 'getAllStudents']);
    Route::apiResource('/estudiantes', EstudianteController::class);

    //Rutas para asignatura
    Route::get('/asignaturas-buscar', [AsignaturaController::class, 'filtrarAsignaturas']);
    Route::get('/asignaturas-all', [AsignaturaController::class, 'allAsignaturas']);
    Route::apiResource('/asignaturas', AsignaturaController::class);

    // Rutas para asignatura-profesor
    Route::get('/asignatura-profesor/{id}/asignaturas', [AsignaturaProfesorController::class, 'getAsignaturasDeProfesor']);
    Route::get('/asignatura-profesor/buscar', [AsignaturaProfesorController::class, 'filtrarAsignaturas']);
    Route::delete('/asignatura-profesor', [AsignaturaProfesorController::class, 'destroy']);
    Route::apiResource('/asignatura-profesor', AsignaturaProfesorController::class);
    // Rutas para representantes
    Route::get('/representantes/{id}/inscripciones', [RepresentanteController::class, 'obtenerEstudiantesPreinscritos']);
    Route::get('/representantes/{id}/estudiantes', [RepresentanteController::class, 'getEstudiantes']);
    Route::post('/representantes/{representante}', [RepresentanteController::class, 'update']);
    Route::apiResource('/representantes', RepresentanteController::class);
    
    // Rutas para secciones
   Route::get('/secciones/buscar', [SeccionController::class, 'buscarPorYearId']);
    Route::get('/secciones-all',[SeccionController::class, 'getAllSecciones']);
    Route::get('/secciones/{id}/estudiantes', [SeccionController::class, 'getEstudiantes']);
    Route::get('/secciones', [SeccionController::class, 'index']);
    Route::get('/secciones/{id}', [SeccionController::class, 'show']);
    Route::post('/secciones', [SeccionController::class, 'store']);
    Route::put('/secciones/{id}', [SeccionController::class, 'update']);
    Route::delete('/secciones/{id}', [SeccionController::class, 'destroy']);

    //Rutas para las notificaciones
    Route::get('/notificaciones', [NotificationController::class, 'index']);
    Route::get('/notificaciones/unread', [NotificationController::class, 'unReadNotifications']);
    Route::post('/notificaciones/mark-as-read/{id}', [NotificationController::class, 'markAsRead']);
    Route::post('/notificaciones/mark-all-as-read', [NotificationController::class, 'markAllAsRead']);

    // Rutas para Profesores
    Route::get('/users-all', [UserController::class, 'getAllProfesores']);
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
