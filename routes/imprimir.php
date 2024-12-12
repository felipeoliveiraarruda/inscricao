<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImpressaoController;

Route::middleware(['auth','verified'])->group(function () 
{
    Route::group(['prefix' => 'imprimir'], function()
    { 
        Route::get('primeira-matricula/{codigoInscricao}',      [ImpressaoController::class, 'primeira_matricula']);
        Route::get('termo-compromisso/{codigoInscricao}',       [ImpressaoController::class, 'termo_compromisso']);
        Route::get('cadastamento-bolsista/{codigoInscricao}',   [ImpressaoController::class, 'cadastamento_bolsista']);
        Route::get('declaracao-acumulo/{codigoInscricao}',      [ImpressaoController::class, 'declaracao_acumulos']);
        Route::get('certificado-proficiencia/{codigoEdital}',   [ImpressaoController::class, 'certificado_proficiencia']);
        Route::get('regulamentacao/{codigoRegulamentacao}',     [ImpressaoController::class, 'regulamentacao']);
    });
});