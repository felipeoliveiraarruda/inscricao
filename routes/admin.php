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
        Route::get('enviar-email-eliminados/{id}',      [AdminController::class, 'enviar_email_eliminados']);
        Route::get('enviar-email-ausentes/{id}',        [AdminController::class, 'enviar_email_ausentes']);
        Route::get('enviar-email-apresentacao/{id}',    [AdminController::class, 'enviar_email_apresentacao']);

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