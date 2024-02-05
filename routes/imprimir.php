<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImpressaoController;

Route::middleware(['auth','verified'])->group(function () 
{
    Route::group(['prefix' => 'imprimir'], function()
    { 
        Route::get('primeira-matricula/{codigoInscricao}',  [ImpressaoController::class, 'primeira_matricula']);
        Route::get('termo-compromisso/{codigoInscricao}',   [ImpressaoController::class, 'termo_compromisso']);
    });
});