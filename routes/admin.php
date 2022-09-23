<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\EditalController;

Route::middleware(['auth','verified'])->group(function () 
{   
    Route::group(['prefix' => 'admin'], function()
    { 
        Route::get('edital',         [EditalController::class, 'index'])->name('edital');
        Route::get('edital/novo',    [EditalController::class, 'create'])->name('novo');
        Route::post('edital/salvar', [EditalController::class, 'store'])->name('salvar');
    });
    
});