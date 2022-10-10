<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InscricaoController;
use App\Http\Controllers\ArquivoController;
use App\Http\Controllers\EnderecoController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () 
{
    return view('index');
});*/

Route::get('/', [HomeController::class, 'index']);
//Route::get('email', [ArquivoController::class, 'email']);

Route::middleware(['auth','verified'])->group(function () 
{
    Route::get('dashboard',     [InscricaoController::class, 'index']);
    Route::get('modelo',        [HomeController::class, 'modelo']);
    Route::get('email-teste',   [HomeController::class, 'email']);
    //Route::post('teste',    [HomeController::class, 'teste']);
    
    Route::group(['prefix' => 'inscricao'], function()
    { 
        Route::get('{id}',                      [InscricaoController::class, 'create']);
        Route::get('comprovante/{id}',          [InscricaoController::class, 'comprovante'])->name('comprovante');
        Route::get('{id}/endereco',             [InscricaoController::class, 'endereco']);
        Route::get('{id}/documento',            [InscricaoController::class, 'documento']);
        Route::get('visualizar/{id}',           [InscricaoController::class, 'show']);
                
        Route::get('arquivos/novo/{id}',        [ArquivoController::class, 'create'])->name('novo');
        Route::post('arquivos/salvar',          [ArquivoController::class, 'store'])->name('salvar');
        Route::get('arquivos/editar/{id}',      [ArquivoController::class, 'edit'])->name('editar');   
        Route::get('arquivos/comprovante/{id}', [ArquivoController::class, 'comprovante'])->name('comprovante');

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

