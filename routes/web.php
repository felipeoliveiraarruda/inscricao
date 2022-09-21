<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\AcessoController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth','verified'])->group(function () 
{
    Route::get('/dashboard', function () 
    {        
        if (session('cpf') == 1)
        {    
            return view('admin.dados'); 
        }
        else
        {
            return redirect('/');
        }
    });
});

require __DIR__.'/auth.php';
