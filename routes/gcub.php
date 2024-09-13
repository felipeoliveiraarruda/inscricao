<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GcubController;

//Route::resource('gcub', GcubController::class);

Route::get('gcub/{tipo}',                           [GcubController::class, 'index']);
Route::post('gcub/store',                           [GcubController::class, 'store']);
Route::get('gcub/{tipo}/{codigoGcub}/show',         [GcubController::class, 'show']);
Route::get('gcub/{codigoGcub}/matricula',           [GcubController::class, 'matricula']);
Route::get('gcub/{codigoGcub}/bolsista',            [GcubController::class, 'bolsista']);
Route::get('gcub/{tipo}/{codigoGcub}/documento',    [GcubController::class, 'documento']);
Route::post('gcub/documento/store',                 [GcubController::class, 'documento_store']);