<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\targetas\Controlador_targetas;

Route::post('/codigo_targeta', [Controlador_targetas::class, 'obtenerCodigoTargeta']);
Route::get('/codigo_targeta/leer', [Controlador_targetas::class, 'leerCodigoTargeta']);
Route::post('/codigo_targeta/agregar', [Controlador_targetas::class, 'agregarCodigoTargeta']);
//->middleware('auth:sanctum')
