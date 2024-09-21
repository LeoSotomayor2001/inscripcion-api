<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RepresentanteController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function(){
    
    Route::middleware('auth:representante')->group(function () {
        Route::post('/logout-representante', [AuthController::class, 'logout']);     
        
    });
})->middleware('auth:sanctum');


Route::apiResource('/representantes', RepresentanteController::class);

Route::post('/register/representante',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login'])->name('login');
Route::apiResource('/users', UserController::class);
