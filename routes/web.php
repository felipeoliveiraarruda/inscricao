<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InscricaoController;
use App\Http\Controllers\ArquivoController;
use App\Http\Controllers\EnderecoController;
use App\Http\Controllers\DadosPessoaisController;
use App\Http\Controllers\UtilsController;

Route::get('/', [HomeController::class, 'index']);
//Route::get('email', [ArquivoController::class, 'email']);

Route::middleware(['auth','verified'])->group(function () 
{
    Route::get('dashboard',     [InscricaoController::class, 'index']);
    Route::get('modelo',        [HomeController::class, 'modelo']);
    Route::get('email-teste',   [HomeController::class, 'email']);
    //Route::post('teste',    [HomeController::class, 'teste']);

    Route::get('endereco/',     [EnderecoController::class, 'index']);
    Route::get('documento/',    [ArquivoController::class, 'index']);

    Route::get('pessoal/novo/{id?}',                [DadosPessoaisController::class, 'create'])->name('novo');
    Route::get('pessoal/anexo/{id?}',               [DadosPessoaisController::class, 'anexo'])->name('anexo');
    Route::get('pessoal/{id}/editar/{inscricao?}',  [DadosPessoaisController::class, 'edit'])->name('editar');
    Route::post('pessoal/salvar',                   [DadosPessoaisController::class, 'store'])->name('salvar');
    Route::post('pessoal/anexo/salvar',             [DadosPessoaisController::class, 'anexo_salvar']);
    
    Route::get('arquivo/{id}/editar/{inscricao?}',  [ArquivoController::class, 'edit'])->name('editar');
    Route::patch('arquivo/{id}',                    [ArquivoController::class, 'update']);

    Route::get('estados/{codpas}',          [UtilsController::class, 'estados']);
    Route::get('cidades/{codpas}/{sglest}', [UtilsController::class, 'cidades']);
    
    Route::group(['prefix' => 'inscricao'], function()
    { 
        Route::get('{id}',                      [InscricaoController::class, 'create']);
        Route::get('comprovante/{id}',          [InscricaoController::class, 'comprovante'])->name('comprovante');
        Route::get('{id}/pessoal',              [InscricaoController::class, 'pessoal']);
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

