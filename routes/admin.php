<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\EditalController;
use App\Http\Controllers\Admin\TipoDocumentoController;
use App\Http\Controllers\Admin\UserController;

Route::middleware(['auth','verified'])->group(function () 
{   
    Route::group(['prefix' => 'admin'], function()
    { 
        Route::get('/',                     [AdminController::class, 'index']);
        Route::get('listar-inscritos/{id}', [AdminController::class, 'listar']);
        Route::get('enviar-email/{id}',     [AdminController::class, 'email']);
        Route::post('enviar-email',         [AdminController::class, 'enviar_email']);

        Route::get('edital',         [EditalController::class, 'index'])->name('edital');
        Route::get('edital/novo',    [EditalController::class, 'create'])->name('novo');
        Route::post('edital/salvar', [EditalController::class, 'store'])->name('salvar');

        Route::get('tipo-documento',         [TipoDocumentoController::class, 'index'])->name('tipo-documento');
        Route::get('tipo-documento/novo',    [TipoDocumentoController::class, 'create'])->name('novo');
        Route::post('tipo-documento/salvar', [TipoDocumentoController::class, 'store'])->name('salvar');

        Route::get('usuario',                   [UserController::class, 'index']);
        Route::get('usuario/recuperacao/{id}',  [UserController::class, 'recuperacao']);
    });
});