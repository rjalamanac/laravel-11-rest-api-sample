<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ActividadController;
use App\Http\Controllers\AuthController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:api')->group(function () {
    Route::get('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    
    Route::apiResource('/categorias',CategoriaController::class);
    Route::get('/categorias/{categoriaId}/actividades', 
    [CategoriaController::class, 'getActividades']);
    Route::post('/categorias/{categoriaId}/actividades/{actividadId}', 
    [CategoriaController::class, 'associateActividad']);
    Route::delete('/categorias/{categoriaId}/actividades/{actividadId}', 
    [CategoriaController::class, 'disassociateActividad']);

    Route::apiResource('/actividades',ActividadController::class);
    Route::post('/actividades/{actividadId}/categorias/{categoriaId}', 
    [ActividadController::class, 'associateCategoria']);
    Route::delete('/actividades/{actividadId}/categorias/{categoriaId}', 
    [ActividadController::class, 'disassociateCategoria']);
    Route::get('/actividades/{actividadId}/categorias', 
    [ActividadController::class, 'getCategorias']);
    Route::get('/actividades/{actividadId}/alumnos', 
    [ActividadController::class, 'getAlumnos']);

    Route::apiResource('/alumnos',AlumnoController::class);
    Route::post('/alumnos/{alumnoId}/actividades/{actividadId}', 
    [AlumnoController::class, 'associateActividad']);
    Route::delete('/alumnos/{alumnoId}/actividades/{actividadId}', 
    [AlumnoController::class, 'disassociateActividad']);
    Route::get('/alumnos/{alumnoId}/actividades', 
    [AlumnoController::class, 'getActividades']);
});

