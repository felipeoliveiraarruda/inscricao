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
        Route::get('/',                                 [AdminController::class, 'index']);
        Route::get('listar-inscritos/{id}',             [AdminController::class, 'listar']);
        Route::get('classificados/{id}',                [AdminController::class, 'classificados']);
        Route::get('enviar-email/{id}',                 [AdminController::class, 'email']);
        Route::post('enviar-email',                     [AdminController::class, 'enviar_email']);
        Route::get('enviar-email-classificados/{id}',   [AdminController::class, 'enviar_email_classificados']);
        Route::post('final',                            [AdminController::class, 'final']);

        Route::get('edital',                        [EditalController::class, 'index']);
        Route::get('edital/create',                 [EditalController::class, 'create']);
        Route::post('edital/store',                 [EditalController::class, 'store']);
        Route::get('edital/{codigoEdital}/edit',    [EditalController::class, 'edit']);
        Route::patch('edital/update',               [EditalController::class, 'update']);

        /*Route::get('tipo-documento',         [TipoDocumentoController::class, 'index'])->name('tipo-documento');
        Route::get('tipo-documento/novo',    [TipoDocumentoController::class, 'create'])->name('novo');
        Route::post('tipo-documento/salvar', [TipoDocumentoController::class, 'store'])->name('salvar');

        Route::get('usuario',                   [UserController::class, 'index']);
        Route::get('usuario/novo',              [UserController::class, 'create'])->name('novo');
        Route::get('usuario/recuperacao/{id}',  [UserController::class, 'recuperacao']);*/
    });
});