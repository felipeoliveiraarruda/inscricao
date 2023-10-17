<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InscricaoController;
use App\Http\Controllers\ArquivoController;
use App\Http\Controllers\EnderecoController;
use App\Http\Controllers\DadosPessoaisController;
use App\Http\Controllers\UtilsController;
use App\Http\Controllers\PAE\PaeController;
use App\Http\Controllers\PAE\DocumentacaoController;

Route::get('/', [HomeController::class, 'index']);
//Route::get('email', [ArquivoController::class, 'email']);

Route::middleware(['auth','verified'])->group(function () 
{
    Route::get('dashboard', [InscricaoController::class, 'index']);

    /* Consultas a API */
    Route::get('estados/{codpas}',          [UtilsController::class, 'estados']);
    Route::get('cidades/{codpas}/{sglest}', [UtilsController::class, 'cidades']);

    /* Inscrição */
    Route::group(['prefix' => 'inscricao'], function()
    { 
        /* Inscrição no Edital */
        Route::get('{codigoInscricao}',     [InscricaoController::class, 'create']);
        Route::get('{codigoEdital}/store',  [InscricaoController::class, 'store']);

        /* Dados Pessoais Inscrição */
        Route::get('{codigoInscricao}/pessoal',         [InscricaoController::class, 'pessoal']);
        Route::get('{codigoInscricao}/pessoal/create',  [InscricaoController::class, 'pessoal_create']);
        
        /* Endereço Inscrição */
        Route::get('{codigoInscricao}/endereco/',   [InscricaoController::class, 'endereco']);

        /* Anexar arquivos já existentes a inscrição */
        Route::post('anexar',   [InscricaoController::class, 'anexar']);

        /* PAE */
        Route::get('{codigoEdital}/pae',            [PaeController::class, 'index']);
        Route::get('{codigoEdital}/pae/create',     [PaeController::class, 'create']);
        Route::post('{codigoEdital}/pae',           [PaeController::class, 'store']);
        Route::post('{codigoEdital}/pae/finalizar', [PaeController::class, 'finalizar']);

        /* PAE - Documentacao */
        Route::get('{codigoEdital}/pae/documentacao/',                              [DocumentacaoController::class, 'index']);
        Route::get('{codigoEdital}/pae/documentacao/create',                        [DocumentacaoController::class, 'create']);
        Route::post('{codigoPae}/pae/documentacao',                                 [DocumentacaoController::class, 'store']);
        Route::get('{codigoPae}/pae/documentacao/{codigoTipoDocumento}/edit',       [DocumentacaoController::class, 'edit']);
        Route::patch('{codigoPae}/pae/{codigoTipoDocumento}/documentacao',          [DocumentacaoController::class, 'update']);
        Route::get('{codigoPae}/pae/documentacao/{codigoTipoDocumento}/destroy',    [DocumentacaoController::class, 'destroy']);

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

    /* Dados Pessoais */
    Route::post('pessoal',                          [DadosPessoaisController::class, 'store']);
    Route::patch('pessoal/{codigoPessoal}',         [DadosPessoaisController::class, 'update']);
    Route::get('pessoal/anexo/{codigoInscricao?}',  [DadosPessoaisController::class, 'anexo'])->name('anexo');
    Route::post('pessoal/anexo/salvar',             [DadosPessoaisController::class, 'anexo_salvar']);
    
    /* Endereço */
    Route::get('endereco/', [EnderecoController::class, 'index']);
    
    /*Route::get('modelo',        [HomeController::class, 'modelo']);
    Route::get('email-teste',   [HomeController::class, 'email']);
    Route::post('teste',    [HomeController::class, 'teste']);

    Route::get('endereco/',     [EnderecoController::class, 'index']);
    Route::get('documento/',    [ArquivoController::class, 'index']);

    Route::get('pessoal/novo/{id?}',                [DadosPessoaisController::class, 'create'])->name('novo');
    Route::get('pessoal/anexo/{id?}',               [DadosPessoaisController::class, 'anexo'])->name('anexo');
    Route::get('pessoal/{id}/editar/{inscricao?}',  [DadosPessoaisController::class, 'edit'])->name('editar');
    Route::post('pessoal/salvar',                   [DadosPessoaisController::class, 'store'])->name('salvar');
    Route::post('pessoal/anexo/salvar',             [DadosPessoaisController::class, 'anexo_salvar']);
    Route::post('pessoal/inscricao/{id}',           [DadosPessoaisController::class, 'inscricao']);
    
    Route::get('arquivo/{id}/editar/{inscricao?}',  [ArquivoController::class, 'edit'])->name('editar');
    Route::patch('arquivo/{id}',                    [ArquivoController::class, 'update']);

    Route::get('estados/{codpas}',          [UtilsController::class, 'estados']);
    Route::get('cidades/{codpas}/{sglest}', [UtilsController::class, 'cidades']);
    
    Route::group(['prefix' => 'inscricao'], function()
    { 
        Route::get('{id}',                      [InscricaoController::class, 'create']);
        Route::get('comprovante/{id}',          [InscricaoController::class, 'comprovante'])->name('comprovante');
        Route::get('{id}/pessoal',              [InscricaoController::class, 'pessoal']);
        Route::get('{id}/pessoal/create',       [InscricaoController::class, 'pessoal']);
        Route::get('{id}/endereco',             [InscricaoController::class, 'endereco']);
        Route::get('{id}/documento',            [InscricaoController::class, 'documento']);
        Route::get('visualizar/{id}',           [InscricaoController::class, 'show']);
        Route::get('validar/{id}',              [InscricaoController::class, 'validar']);
                
        Route::get('arquivos/novo/{id}',                                    [ArquivoController::class, 'create'])->name('novo');
        Route::post('arquivos/salvar',                                      [ArquivoController::class, 'store'])->name('salvar');
        //Route::get('arquivos/editar/{id}',                                  [ArquivoController::class, 'edit'])->name('editar');
        Route::get('arquivos/remover/{codigoInscricao}/{codigoArquivo}',    [ArquivoController::class, 'remover'])->name('remover');
        Route::get('arquivos/comprovante/{id}',                             [ArquivoController::class, 'comprovante'])->name('comprovante');
        
        Route::get('endereco/novo/{id}',        [EnderecoController::class, 'create'])->name('novo');
        Route::post('endereco/salvar',          [EnderecoController::class, 'store'])->name('salvar');
    });
    
   /* Route::get('endereco', [EnderecoController::class, 'index']);

    Route::group(['prefix' => 'endereco'], function()
    { 
        
        Route::post('salvar',     [EnderecoController::class, 'store'])->name('salvar');
        Route::get('editar/{id}', [EnderecoController::class, 'edit'])->name('editar');           
    });*/

});

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
