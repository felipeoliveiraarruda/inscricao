<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\EditalController;
use App\Http\Controllers\Admin\TipoDocumentoController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DeferimentoController;
use App\Http\Controllers\PAE\PaeController;
use App\Http\Controllers\PAE\DesempenhoController;
use App\Http\Controllers\PAE\AnaliseCurriculoController;
use App\Http\Controllers\PAE\AvaliacaoController;

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
        Route::get('confirmados/{id}',                  [AdminController::class, 'confirmados']);
        Route::get('lista-presenca/{id}',               [AdminController::class, 'presenca']);
        Route::get('lista-disciplina/{id}',             [AdminController::class, 'lista_disciplina']);

        Route::get('edital',         [EditalController::class, 'index'])->name('edital');
        Route::get('edital/novo',    [EditalController::class, 'create'])->name('novo');
        Route::post('edital/salvar', [EditalController::class, 'store'])->name('salvar');

        Route::get('tipo-documento',         [TipoDocumentoController::class, 'index'])->name('tipo-documento');
        Route::get('tipo-documento/novo',    [TipoDocumentoController::class, 'create'])->name('novo');
        Route::post('tipo-documento/salvar', [TipoDocumentoController::class, 'store'])->name('salvar');

        Route::get('usuario',                   [UserController::class, 'index']);
        Route::get('usuario/novo',              [UserController::class, 'create'])->name('novo');
        Route::get('usuario/recuperacao/{id}',  [UserController::class, 'recuperacao']);

        /* Deferimento */
        Route::get('deferimento/{codigoEdital}',    [DeferimentoController::class, 'listar']);

        /* PAE - Desempenho Academico */
        Route::get('{codigoPae}/pae/desempenho/',  [DesempenhoController::class, 'index']);
        Route::post('{codigoPae}/pae/desempenho',  [DesempenhoController::class, 'store']);
        Route::patch('{codigoPae}/pae/desempenho', [DesempenhoController::class, 'update']);

        /* PAE - Analise */
        Route::get('{codigoPae}/pae/analise',                                   [AnaliseCurriculoController::class, 'index']);
        Route::post('{codigoPae}/pae/analise',                                  [AnaliseCurriculoController::class, 'store']);
        Route::patch('{codigoPae}/pae/analise',                                 [AnaliseCurriculoController::class, 'update']);        
        Route::get('{codigoPae}/pae/analise/{codigoTipoDocumento}/visualizar',  [AnaliseCurriculoController::class, 'visualizar']);
        Route::get('{codigoPae}/pae/analise/{codigoTipoDocumento}',             [AnaliseCurriculoController::class, 'analisar']);
        Route::get('{codigoPae}/pae/analise/{codigoTipoDocumento}/edit',        [AnaliseCurriculoController::class, 'analisar_edit']);
        Route::patch('{codigoPae}/pae/analise/{codigoTipoDocumento}',           [AnaliseCurriculoController::class, 'analisar_update']);

        /* PAE - Avaliacao */
        Route::get('{codigoEdital}/pae/avaliacao',              [AvaliacaoController::class, 'index']);
        Route::get('{codigoEdital}/pae/distribuicao',           [AvaliacaoController::class, 'distribuicao']);
        Route::post('{codigoEdital}/pae/distribuicao/store',    [AvaliacaoController::class, 'distribuicao_store']);
                
        /* PAE - Classificacao */
        Route::get('{codigoEdital}/pae/classificacao',  [PaeController::class, 'classificacao']);
        Route::get('{codigoEdital}/pae/planilha',       [PaeController::class, 'planilha']);

        /* PAE - Recurso*/
        Route::get('{codigoEdital}/pae/recurso',        [PaeController::class, 'recurso']);
        Route::get('{codigoRecurso}/recurso/edit',      [RecursoPaeController::class, 'edit']);
        Route::patch('recurso/{codigoRecurso}',         [RecursoPaeController::class, 'update']);

        /* PAE - Desempenho Academico
        Route::get('{codigoEdital}/pae/desempenho/create',               [PaeController::class, 'desempenho']);
        Route::post('{codigoPae}/pae/desempenho',                        [PaeController::class, 'desempenho_store']);
        Route::get('{codigoEdital}/pae/desempenho/{codigoPae}/edit',     [PaeController::class, 'desempenho_edit']);
        Route::patch('{codigoPae}/pae/desempenho',                       [PaeController::class, 'desempenho_update']);
        Route::get('pae/desempenho/{codigoDesempenhoAcademico}/destroy', [PaeController::class, 'desempenho_destroy']); */

        /* PAE - Analise 
        Route::get('{codigoEdital}/pae/analise/create',            [PaeController::class, 'analise']);
        Route::post('{codigoPae}/pae/analise',                     [PaeController::class, 'analise_store']);
        Route::get('{codigoEdital}/pae/analise/{codigoPae}/edit',  [PaeController::class, 'analise_edit']);
        Route::patch('{codigoPae}/pae/analise',                    [PaeController::class, 'analise_update']);
        Route::get('pae/analise/{codigoAnaliseCurriculo}/destroy', [PaeController::class, 'analise_destroy']);*/
        
    });
});