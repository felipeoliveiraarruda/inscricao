<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\EditalController;
use App\Http\Controllers\Admin\TipoDocumentoController;
use App\Http\Controllers\Admin\ArquivoController;

Route::middleware(['auth','verified'])->group(function () 
{   
    Route::group(['prefix' => 'admin'], function()
    { 
        Route::get('edital',         [EditalController::class, 'index'])->name('edital');
        Route::get('edital/novo',    [EditalController::class, 'create'])->name('novo');
        Route::post('edital/salvar', [EditalController::class, 'store'])->name('salvar');

        Route::get('tipo-documento',         [TipoDocumentoController::class, 'index'])->name('tipo-documento');
        Route::get('tipo-documento/novo',    [TipoDocumentoController::class, 'create'])->name('novo');
        Route::post('tipo-documento/salvar', [TipoDocumentoController::class, 'store'])->name('salvar');
    });
});