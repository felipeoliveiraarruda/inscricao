<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Utils;
use App\Models\User;

class UtilsController extends Controller
{
    public function estados($codpas)
    {     
        $estados = Utils::listarEstado($codpas);
        
        return view('utils.estados',
        [            
            'estados' => $estados,
        ]);
    }

    public function cidades($codpas, $sglest)
    {     
        $cidades = Utils::listarLocalidades($codpas, $sglest);
        
        return view('utils.cidades',
        [            
            'cidades' => $cidades,
        ]);
    }

    public function verificaTelefoneEmergencia($telefoneEmergencia)
    {
        $telefone = User::where('id', Auth::user()->id)->where('telefone', $telefoneEmergencia)->count();
        return json_encode($telefone);
    }
}
