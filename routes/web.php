<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InscricaoController;
use App\Http\Controllers\ArquivoController;
use App\Http\Controllers\Arquivo\ImagemController;
use App\Http\Controllers\Arquivo\DocumentoController;
use App\Http\Controllers\EnderecoController;
use App\Http\Controllers\DadosPessoaisController;
use App\Http\Controllers\EmergenciaController;
use App\Http\Controllers\ResumoEscolarController;
use App\Http\Controllers\IdiomaController;
use App\Http\Controllers\ExperienciaController;
use App\Http\Controllers\RecursoFinanceiroController;
use App\Http\Controllers\UtilsController;
use App\Http\Controllers\DeferimentoController;
use App\Http\Controllers\PAE\PaeController;
use App\Http\Controllers\PAE\DocumentacaoController;
use App\Http\Controllers\PAE\DesempenhoController;
use App\Http\Controllers\PAE\RecursoPaeController;

Route::get('/', [HomeController::class, 'index']);
//Route::get('email', [ArquivoController::class, 'email']);
Route::get('requerimento/{codigoInscricao}', [InscricaoController::class, 'comprovante']);

Route::middleware(['auth','verified'])->group(function () 
{
    Route::get('dashboard',     [InscricaoController::class, 'index']);
    Route::get('realizadas',    [InscricaoController::class, 'realizadas']);

    /* Consultas a API */
    Route::get('estados/{codpas}',              [UtilsController::class, 'estados']);
    Route::get('cidades/{codpas}/{sglest}',     [UtilsController::class, 'cidades']);
    Route::get('telefone/{telefone}/',          [UtilsController::class, 'verificaTelefoneEmergencia']);

    /* Inscrição */
    Route::group(['prefix' => 'inscricao'], function()
    { 
        /* Inscrição no Edital */
        Route::get('{codigoInscricao}/',                    [InscricaoController::class, 'create']);
        Route::get('{codigoEdital}/store',                  [InscricaoController::class, 'store']);
        Route::get('comprovante/{codigoInscricao}',         [InscricaoController::class, 'comprovante']);
        Route::get('visualizar/{codigoInscricao}/{tipo?}',  [InscricaoController::class, 'show']);
        Route::post('validar/{codigoInscricao}',            [InscricaoController::class, 'validar']);
        Route::get('requerimento/{codigoInscricao}',        [InscricaoController::class, 'comprovante']);
        Route::get('recusar/{codigoInscricao}/',            [InscricaoController::class, 'recusar']);
        Route::get('{codigoInscricao}/matricula/',          [InscricaoController::class, 'matricula_create']);
        Route::post('{codigoInscricao}/matricula/',         [InscricaoController::class, 'matricula_store']);

        /* Processo Seletivo */
        Route::get('{codigoEdital}/processo-seletivo/',     [InscricaoController::class, 'processo_seletivo']);
        
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
        Route::get('{codigoInscricao}/escolar/',                                [InscricaoController::class, 'escolar']);
        Route::get('{codigoInscricao}/escolar/create/{codigoResumoEscolar?}',   [InscricaoController::class, 'escolar_create']);

        /* Idioma Inscrição */
        Route::get('{codigoInscricao}/idioma/',                       [InscricaoController::class, 'idioma']);
        Route::get('{codigoInscricao}/idioma/create/{codigoIdioma?}', [InscricaoController::class, 'idioma_create']);

        /* Experiencia Profissional Inscricao */
        Route::get('{codigoInscricao}/profissional/',                               [InscricaoController::class, 'profissional']);
        Route::get('{codigoInscricao}/profissional/create/{codigoExperiencia?}',    [InscricaoController::class, 'profissional_create']);

        /* Experiencia Em Ensino Inscricao */
        Route::get('{codigoInscricao}/ensino/',                             [InscricaoController::class, 'ensino']);
        Route::get('{codigoInscricao}/ensino/create/{codigoExperiencia?}',  [InscricaoController::class, 'ensino_create']);

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

        /* Pre-projeto Inscricao */
        Route::get('{codigoInscricao}/pre-projeto/',       [InscricaoController::class, 'projeto']);
        Route::get('{codigoInscricao}/pre-projeto/create', [InscricaoController::class, 'projeto_create']);
        Route::post('{codigoInscricao}/pre-projeto/store', [InscricaoController::class, 'projeto_store']);

        /* Bolsista Inscricao */
        Route::get('{codigoInscricao}/bolsista/',       [InscricaoController::class, 'bolsista']);
        Route::get('{codigoInscricao}/bolsista/create', [InscricaoController::class, 'bolsista_create']);
        Route::post('{codigoInscricao}/bolsista/store', [InscricaoController::class, 'bolsista_store']);

         /* Documentos Obrigatorios Inscricao */
         Route::get('{codigoInscricao}/obrigatorio/',   [InscricaoController::class, 'obrigatorio']);

        /* Anexar arquivos já existentes a inscrição */
        //Route::post('anexar',   [InscricaoController::class, 'anexar']);

        /* Requerimento de Inscriçao */
        Route::get('{codigoInscricao}/requerimento/',       [InscricaoController::class, 'requerimento']);
        Route::post('{codigoInscricao}/requerimento/store', [InscricaoController::class, 'requerimento_store']);

        /* Disciplina Inscricao */
        Route::get('{codigoInscricao}/disciplina/',       [InscricaoController::class, 'disciplina']);
        Route::get('{codigoInscricao}/disciplina/create', [InscricaoController::class, 'disciplina_create']);
        Route::post('{codigoInscricao}/disciplina/store', [InscricaoController::class, 'disciplina_store']);

        /* Deferimento */
        Route::get('deferimento/{codigoEdital}',                        [DeferimentoController::class, 'index']);
        Route::post('deferimento/',                                     [DeferimentoController::class, 'store']);
        Route::get('deferimento/destroy/{codigoInscricaoDisciplina}',   [DeferimentoController::class, 'destroy']);
        Route::get('deferimento/{codigoEdital}/primeira-matricula',     [DeferimentoController::class, 'primeira_matricula']);

        /* PAE */
        Route::get('{codigoEdital}/pae',                                [PaeController::class, 'index']);
        Route::get('{codigoEdital}/pae/create',                         [PaeController::class, 'create']);
        Route::post('{codigoEdital}/pae',                               [PaeController::class, 'store']);
        Route::get('{codigoInscricao}/pae/{codigoEdital}/visualizar',   [PaeController::class, 'visualizar']);
        Route::get('{codigoEdital}/pae/finalizar',                      [PaeController::class, 'finalizar']);
        Route::post('{codigoEdital}/pae/finalizar/store',               [PaeController::class, 'finalizar_store']);
        Route::get('{codigoEdital}/pae/comprovante',                    [PaeController::class, 'comprovante']);
        Route::get('{codigoEdital}/pae/reenviar',                       [PaeController::class, 'reenviar']);
        Route::get('{codigoEdital}/pae/resultado',                      [PaeController::class, 'resultado']);
        
        /* PAE - Documentacao */
        Route::get('{codigoEdital}/pae/documentacao/',                              [DocumentacaoController::class, 'index']);
        Route::get('{codigoEdital}/pae/documentacao/create',                        [DocumentacaoController::class, 'create']);
        Route::post('{codigoPae}/pae/documentacao',                                 [DocumentacaoController::class, 'store']);
        Route::get('{codigoPae}/pae/documentacao/{codigoTipoDocumento}/edit',       [DocumentacaoController::class, 'edit']);
        Route::patch('{codigoPae}/pae/{codigoTipoDocumento}/documentacao',          [DocumentacaoController::class, 'update']);
        Route::get('{codigoPae}/pae/documentacao/{codigoTipoDocumento}/destroy',    [DocumentacaoController::class, 'destroy']);
        Route::get('{codigoEdital}/pae/{codigoUsuadio}/documentacao/visualizar',    [DocumentacaoController::class, 'visualizar']);

        /* PAE - Recurso */
        Route::get('{codigoPae}/pae/recurso/',       [RecursoPaeController::class, 'index']);
        Route::get('{codigoPae}/pae/recurso/create', [RecursoPaeController::class, 'create']);
        Route::post('{codigoPae}/pae/recurso',       [RecursoPaeController::class, 'store']);
    });

    /* Dados Pessoais */
    Route::post('pessoal',                                                  [DadosPessoaisController::class, 'store']);
    Route::patch('pessoal/{codigoPessoal}',                                 [DadosPessoaisController::class, 'update']);
    Route::get('pessoal/anexo/{codigoInscricao?}',                          [DadosPessoaisController::class, 'anexo'])->name('anexo');
    Route::post('pessoal/anexo/salvar',                                     [DadosPessoaisController::class, 'anexo_salvar']);
    
    /* Endereço */    
    Route::post('endereco/',                                        [EnderecoController::class, 'store']);
    Route::get('endereco/{codigoEndereco}/edit/{codigoInscricao?}', [EnderecoController::class, 'edit']);
    Route::patch('endereco/{codigoEndereco}/{codigoInscricao?}',    [EnderecoController::class, 'update']);

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
    Route::post('financeiro/',                           [RecursoFinanceiroController::class, 'store']);
    Route::patch('financeiro/{codigoRecursoFinanceiro}', [RecursoFinanceiroController::class, 'update']);

    /* Arquivos 
    Route::get('arquivo/{codigoArquivo}/edit/{codigoInscricao?}',       [ArquivoController::class, 'edit']);
    Route::patch('arquivo/{codigoArquivo}',                             [ArquivoController::class, 'update']);
    Route::get('arquivo/{codigoArquivo}/destroy/{codigoInscricao?}',    [ArquivoController::class, 'destroy']);
    Route::get('arquivo/{codigoArquivo}/anexar/{codigoInscricao?}',     [ArquivoController::class, 'anexar']);*/
    
    /* Arquivos Imagem */ 
    Route::get('imagem/{codigoInscricao}/{codigoTipoDocumento}',    [ImagemController::class, 'create']);
    Route::post('imagem/{codigoInscricao}',                         [ImagemController::class, 'store']);
    Route::get('imagem/{codigoArquivo}/edit/{codigoInscricao}',     [ImagemController::class, 'edit']);
    Route::patch('imagem/{codigoArquivo}',                          [ImagemController::class, 'update']);
    Route::get('imagem/{codigoArquivo}/destroy/{codigoInscricao}',  [ImagemController::class, 'destroy']);
    Route::get('imagem/{codigoArquivo}/anexar/{codigoInscricao}',   [ImagemController::class, 'anexar']);

    /* Arquivos Documento */ 
    Route::get('documento/{codigoInscricao}/{codigoTipoDocumento}',     [DocumentoController::class, 'create']);
    Route::post('documento/{codigoInscricao}',                          [DocumentoController::class, 'store']);
    Route::get('documento/{codigoArquivo}/edit/{codigoInscricao}',      [DocumentoController::class, 'edit']);
    Route::patch('documento/{codigoArquivo}',                           [DocumentoController::class, 'update']);
    Route::get('documento/{codigoArquivo}/destroy/{codigoInscricao}',   [DocumentoController::class, 'destroy']);
    Route::get('documento/{codigoArquivo}/anexar/{codigoInscricao}',    [DocumentoController::class, 'anexar']);

    /* PAE - Recurso*/
    Route::get('recurso/{codigoRecurso}/edit',      [RecursoPaeController::class, 'edit']);
    Route::patch('recurso/{codigoRecurso}',         [RecursoPaeController::class, 'update']);

    /* Deferimento */
    Route::get('deferimento',   [DeferimentoController::class, 'index']);
    
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
require __DIR__.'/gcub.php';
require __DIR__.'/imprimir.php';
