<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EgressoController;


Route::get('/egressos/regular', [EgressoController::class, 'regular'])->name('regular');
Route::get('/egressos/listar',  [EgressoController::class, 'listar'])->name('listar');

Route::resource('egressos', EgressoController::class);

