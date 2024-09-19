<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RepresentanteController;
use App\Http\Controllers\UserController;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:representante')->group(function () {

});

Route::apiResource('/representantes', RepresentanteController::class);

Route::post('/register/representante',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);
Route::apiResource('/users', UserController::class);
