<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InscricaoController;
use App\Http\Controllers\ArquivoController;
use App\Http\Controllers\EnderecoController;
use App\Http\Controllers\DadosPessoaisController;
use App\Http\Controllers\EmergenciaController;
use App\Http\Controllers\ResumoEscolarController;
use App\Http\Controllers\IdiomaController;
use App\Http\Controllers\ExperienciaController;
use App\Http\Controllers\RecursoFinanceiroController;
use App\Http\Controllers\UtilsController;
use App\Http\Controllers\PAE\PaeController;
use App\Http\Controllers\PAE\DocumentacaoController;
use App\Http\Controllers\PAE\DesempenhoController;

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
        Route::get('{codigoInscricao}',             [InscricaoController::class, 'create']);
        Route::get('{codigoEdital}/store',          [InscricaoController::class, 'store']);
        Route::get('comprovante/{codigoInscricao}', [InscricaoController::class, 'comprovante']);

        /* Dados Pessoais Inscrição */
        Route::get('{codigoInscricao}/pessoal',         [InscricaoController::class, 'pessoal']);
        Route::get('{codigoInscricao}/pessoal/create',  [InscricaoController::class, 'pessoal_create']);
        
        /* Endereço Inscrição */
        Route::get('{codigoInscricao}/endereco/',       [InscricaoController::class, 'endereco']);
        Route::get('{codigoInscricao}/endereco/create', [InscricaoController::class, 'endereco_create']);

        /* Endereço Inscrição */
        Route::get('{codigoInscricao}/emergencia/',       [InscricaoController::class, 'emergencia']);
        Route::get('{codigoInscricao}/emergencia/create', [InscricaoController::class, 'emergencia_create']);

        /* Resumo Escolar Inscrição */
        Route::get('{codigoInscricao}/escolar/',       [InscricaoController::class, 'escolar']);
        Route::get('{codigoInscricao}/escolar/create', [InscricaoController::class, 'escolar_create']);

        /* Idioma Inscrição */
        Route::get('{codigoInscricao}/idioma/',       [InscricaoController::class, 'idioma']);
        Route::get('{codigoInscricao}/idioma/create', [InscricaoController::class, 'idioma_create']);

        /* Experiencia Profissional Inscricao */
        Route::get('{codigoInscricao}/profissional/',       [InscricaoController::class, 'profissional']);
        Route::get('{codigoInscricao}/profissional/create', [InscricaoController::class, 'profissional_create']);

        /* Experiencia Em Ensino Inscricao */
        Route::get('{codigoInscricao}/ensino/',       [InscricaoController::class, 'ensino']);
        Route::get('{codigoInscricao}/ensino/create', [InscricaoController::class, 'ensino_create']);

        /* Recursos Financeiros Inscricao */
        Route::get('{codigoInscricao}/financeiro/',       [InscricaoController::class, 'financeiro']);
        Route::get('{codigoInscricao}/financeiro/create', [InscricaoController::class, 'financeiro_create']);

        /* Expectativas Inscricao */
        Route::get('{codigoInscricao}/expectativas/',       [InscricaoController::class, 'expectativas']);
        Route::get('{codigoInscricao}/expectativas/create', [InscricaoController::class, 'expectativas_create']);
        Route::post('{codigoInscricao}/expectativas/store', [InscricaoController::class, 'expectativas_store']);

        /* Curriculo Inscricao */
        Route::get('{codigoInscricao}/curriculo/',       [InscricaoController::class, 'curriculo']);
        Route::get('{codigoInscricao}/curriculo/create', [InscricaoController::class, 'curriculo_create']);
        Route::post('{codigoInscricao}/curriculo/store', [InscricaoController::class, 'curriculo_store']);

        /* Anexar arquivos já existentes a inscrição */
        Route::post('anexar',   [InscricaoController::class, 'anexar']);

        /* PAE */
        Route::get('{codigoEdital}/pae',                    [PaeController::class, 'index']);
        Route::get('{codigoEdital}/pae/create',             [PaeController::class, 'create']);
        Route::post('{codigoEdital}/pae',                   [PaeController::class, 'store']);
        Route::get('{codigoInscricao}/pae/{codigoEdital}/visualizar',   [PaeController::class, 'visualizar']);
        Route::get('{codigoEdital}/pae/finalizar',                      [PaeController::class, 'finalizar']);
        Route::post('{codigoEdital}/pae/finalizar/store',   [PaeController::class, 'finalizar_store']);
        Route::get('{codigoEdital}/pae/comprovante',        [PaeController::class, 'comprovante']);

        /* PAE - Documentacao */
        Route::get('{codigoEdital}/pae/documentacao/',                              [DocumentacaoController::class, 'index']);
        Route::get('{codigoEdital}/pae/documentacao/create',                        [DocumentacaoController::class, 'create']);
        Route::post('{codigoPae}/pae/documentacao',                                 [DocumentacaoController::class, 'store']);
        Route::get('{codigoPae}/pae/documentacao/{codigoTipoDocumento}/edit',       [DocumentacaoController::class, 'edit']);
        Route::patch('{codigoPae}/pae/{codigoTipoDocumento}/documentacao',          [DocumentacaoController::class, 'update']);
        Route::get('{codigoPae}/pae/documentacao/{codigoTipoDocumento}/destroy',    [DocumentacaoController::class, 'destroy']);
        Route::get('{codigoEdital}/pae/{codigoUsuadio}/documentacao/visualizar',    [DocumentacaoController::class, 'visualizar']);
    });

    /* Dados Pessoais */
    Route::post('pessoal',                          [DadosPessoaisController::class, 'store']);
    Route::patch('pessoal/{codigoPessoal}',         [DadosPessoaisController::class, 'update']);
    Route::get('pessoal/anexo/{codigoInscricao?}',  [DadosPessoaisController::class, 'anexo'])->name('anexo');
    Route::post('pessoal/anexo/salvar',             [DadosPessoaisController::class, 'anexo_salvar']);
    
    /* Endereço */    
    Route::post('endereco/',                    [EnderecoController::class, 'store']);
    Route::patch('endereco/{codigoEndereco}',   [EnderecoController::class, 'update']);

    /* Emergencia */
    Route::post('emergencia/',                      [EmergenciaController::class, 'store']);
    Route::patch('emergencia/{codigoEmergencia}',   [EmergenciaController::class, 'update']);

    /* Resumo Escolar */
    Route::post('escolar/',                         [ResumoEscolarController::class, 'store']);
    Route::patch('escolar/{codigoResumoEscolar}',   [ResumoEscolarController::class, 'update']);

    /* Idiomas */
    Route::post('idioma/',                  [IdiomaController::class, 'store']);
    Route::patch('idioma/{codigoIdioma}',   [IdiomaController::class, 'update']);

    /* Experiencia (Ensino e Profissional) */
    Route::post('experiencia/',                     [ExperienciaController::class, 'store']);
    Route::patch('experiencia/{codigoExperiencia}', [ExperienciaController::class, 'update']);

    /* Recursos Financeiros */
    Route::post('financeiro/',                          [RecursoFinanceiroController::class, 'store']);
    Route::patch('financeiro/{codigoRecursoFianceiro}', [RecursoFinanceiroController::class, 'update']);
    
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
