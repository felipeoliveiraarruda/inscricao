<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EstagioController;

Route::get('estagios/{tipo?}',              [EstagioController::class, 'index']);
Route::get('estagios/{tipo}/create',        [EstagioController::class, 'create']);
Route::post('estagios/verificacao',         [EstagioController::class, 'verificacao']);
Route::post('estagios/{tipo}/store',        [EstagioController::class, 'store']);
Route::get('estagios/{tipo}/listar',        [EstagioController::class, 'listar']);
Route::get('estagios/{tipo}/{codigo}/show', [EstagioController::class, 'show']);

Route::post('idiomas/store',    [EstagioController::class, 'idioma_store']);