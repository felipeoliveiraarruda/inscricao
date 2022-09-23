<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Edital;
use App\Models\Utils;

class InscricaoController extends Controller
{
    public function index()
    {
        if (session('cpf') == 1)
        {    
            return redirect('admin/dados'); 
        }

        $editais = Edital::all();

        return view('dashboard',
        [
            'editais' => $editais,
            'utils'   => new Utils
        ]);
    }
}