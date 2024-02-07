<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Edital;
use App\Models\Utils;
use App\Models\Inscricao;
use App\Models\DeclaracaoAcumulo;
use Uspdev\Replicado\Posgraduacao;
use Carbon\Carbon;

class DeclaracaoAcumuloController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($codigoInscricao)
    {
        $declaracoes = DeclaracaoAcumulo::where('codigoInscricao', '=', $codigoInscricao);
        $edital      = Edital::obterEditalInscricao($codigoInscricao);

        if (empty(session('level')))
        {
            Utils::setSession(Auth::user()->id);
        }

        return view('acumulo',
        [
            'declaracoes'       => $declaracoes,
            'codigoInscricao'   => $codigoInscricao,
            'codigoEdital'      => $edital->codigoEdital,
            'level'             => session('level'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($codigoInscricao)
    {
        $secoes = Utils::listaSecaoCnae();

        return view('inscricao.acumulo',
        [
            'codigoInscricao'   => $codigoInscricao,
            'secoes'            => $secoes,
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
     * @param  \App\Models\DeclaracaoAcumulo  $declaracaoAcumulo
     * @return \Illuminate\Http\Response
     */
    public function show(DeclaracaoAcumulo $declaracaoAcumulo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DeclaracaoAcumulo  $declaracaoAcumulo
     * @return \Illuminate\Http\Response
     */
    public function edit(DeclaracaoAcumulo $declaracaoAcumulo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DeclaracaoAcumulo  $declaracaoAcumulo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DeclaracaoAcumulo $declaracaoAcumulo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DeclaracaoAcumulo  $declaracaoAcumulo
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeclaracaoAcumulo $declaracaoAcumulo)
    {
        //
    }
}
