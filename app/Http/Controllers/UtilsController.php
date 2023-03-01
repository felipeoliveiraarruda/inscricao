<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Utils;

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
}
