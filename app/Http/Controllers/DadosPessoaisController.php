<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DadosPessoais;
use App\Models\Utils;
use App\Models\Inscricao;
use Illuminate\Support\Facades\Auth;

class DadosPessoaisController extends Controller
{
    public function index()
    {
        $pessoais = DadosPessoais::all();
        
        return view('pessoal.index',
        [
            'pessoais' => $pessoais,
            'utils'    => new Utils
        ]);        
    }

    public function create($id = '')
    {
        if (!empty($id))
        {
            $inscricao = Inscricao::where('codigoUsuario', Auth::user()->id)->where('codigoInscricao', $id)->first();
            $voltar = "inscricao/{$inscricao->codigoEdital}";
        }
        else
        {
            $voltar = 'pessoal';
        }

        return view('pessoal.create',
        [
            'codigoInscricao'   => $id,
            'link_voltar'       => $voltar,
            'sexos'             => Utils::obterDadosSysUtils('sexo'),
            'racas'             => Utils::obterDadosSysUtils('raça'),
            'estados_civil'     => Utils::obterDadosSysUtils('civil'),
        ]); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DadosPessoais  $dadosPessoais
     * @return \Illuminate\Http\Response
     */
    public function show(DadosPessoais $dadosPessoais)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DadosPessoais  $dadosPessoais
     * @return \Illuminate\Http\Response
     */
    public function edit(DadosPessoais $dadosPessoais)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DadosPessoais  $dadosPessoais
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DadosPessoais $dadosPessoais)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DadosPessoais  $dadosPessoais
     * @return \Illuminate\Http\Response
     */
    public function destroy(DadosPessoais $dadosPessoais)
    {
        //
    }
}
