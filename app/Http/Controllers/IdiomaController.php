<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Idioma;
use App\Models\Utils;
use App\Models\InscricoesIdiomas;

class IdiomaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $temp = '';
        $voltar = '/endereco';
        $inscricaoIdioma = true;
        
        \DB::beginTransaction();

        $idioma = Idioma::create([
            'codigoUsuario'         => Auth::user()->id,
            'descricaoIdioma'       => $request->descricaoIdioma,
            'leituraIdioma'         => $request->leituraIdioma,
            'redacaoIdioma'         => $request->redacaoIdioma,
            'conversacaoIdioma'     => $request->conversacaoIdioma,
            'codigoPessoaAlteracao' => Auth::user()->codpes,
        ]);
        
        if(!empty($request->codigoInscricao))
        {
            $inscricaoIdioma = InscricoesIdiomas::create([
                'codigoInscricao'       => $request->codigoInscricao,
                'codigoIdioma'          => $idioma->codigoIdioma,
                'codigoPessoaAlteracao' => Auth::user()->codpes,
            ]);

            $voltar = "inscricao/{$request->codigoInscricao}/idioma";
        }

        if($idioma && $inscricaoIdioma) 
        {
            \DB::commit();
        } 
        else 
        {
            \DB::rollBack();
        }

        request()->session()->flash('alert-success', 'Conhecimento de Idioma cadastrado com sucesso.');
        
        return redirect($voltar);        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Idioma  $idioma
     * @return \Illuminate\Http\Response
     */
    public function show(Idioma $idioma)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Idioma  $idioma
     * @return \Illuminate\Http\Response
     */
    public function edit(Idioma $idioma)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Idioma  $idioma
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Idioma $idioma)
    {
        $temp = '';
        $voltar = '/idioma';
        $inscricaoIdioma = true;
        
        \DB::beginTransaction();

        $idioma = Idioma::find($request->codigoIdioma);
        $idioma->codigoUsuario         = Auth::user()->id;
        $idioma->descricaoIdioma       = $request->descricaoIdioma;
        $idioma->leituraIdioma         = $request->leituraIdioma;
        $idioma->redacaoIdioma         = $request->redacaoIdioma;
        $idioma->conversacaoIdioma     = $request->conversacaoIdioma;
        $idioma->codigoPessoaAlteracao = Auth::user()->codpes;
        $idioma->save();
        
        if(!empty($request->codigoInscricao))
        {
            if(empty($request->codigoInscricaoIdioma))
            {
                $inscricaoIdioma = InscricoesIdiomas::create([
                    'codigoInscricao'       => $request->codigoInscricao,
                    'codigoIdioma'          => $idioma->codigoIdioma,
                    'codigoPessoaAlteracao' => Auth::user()->codpes,
                ]);
            }
            else
            {
                $inscricaoIdioma = InscricoesIdiomas::find($request->codigoInscricaoIdioma);
                $inscricaoIdioma->codigoInscricao       = $request->codigoInscricao;
                $inscricaoIdioma->codigoIdioma          = $request->codigoIdioma;
                $inscricaoIdioma->codigoPessoaAlteracao = Auth::user()->codpes;
                $inscricaoIdioma->save();
            }

            $voltar = "inscricao/{$request->codigoInscricao}/idioma";
        }

        if($idioma && $inscricaoIdioma) 
        {
            \DB::commit();
        } 
        else 
        {
            \DB::rollBack();
        }

        request()->session()->flash('alert-success', 'Conhecimento de Idioma cadastrado com sucesso.');
        
        return redirect($voltar);    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Idioma  $idioma
     * @return \Illuminate\Http\Response
     */
    public function destroy(Idioma $idioma)
    {
        //
    }
}
