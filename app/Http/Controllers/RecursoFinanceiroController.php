<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RecursoFinanceiro;
use App\Models\Utils;
use App\Models\InscricoesRecursosFinanceiros;

class RecursoFinanceiroController extends Controller
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
        $voltar = '/financeiro';
        $inscricaoRecursoFinanceiro = true;
        
        \DB::beginTransaction();

        $financeiro = RecursoFinanceiro::create([
            'codigoUsuario'                 => Auth::user()->id,
            'bolsaRecursoFinanceiro'        => $request->inlineBolsa,
            'solicitarRecursoFinanceiro'    => $request->inlineSolicitar,
            'orgaoRecursoFinanceiro'        => (empty($request->orgaoRecursoFinanceiro) ? NULL : $request->orgaoRecursoFinanceiro),
            'tipoBolsaFinanceiro'           => (empty($request->tipoBolsaFinanceiro) ? NULL : $request->tipoBolsaFinanceiro),
            'inicioRecursoFinanceiro'       => (empty($request->inicioRecursoFinanceiro) ? NULL : $request->inicioRecursoFinanceiro),
            'finalRecursoFinanceiro'        => (empty($request->finalRecursoFinanceiro) ? NULL : $request->finalRecursoFinanceiro),
            'anoTitulacaoRecursoFinanceiro' => (empty($request->anoTitulacaoRecursoFinanceiro) ? NULL : $request->anoTitulacaoRecursoFinanceiro),
            'iesTitulacaoRecursoFinanceiro' => (empty($request->iesTitulacaoRecursoFinanceiro) ? NULL : $request->iesTitulacaoRecursoFinanceiro),
            'agenciaRecursoFinanceiro'      => (empty($request->agenciaRecursoFinanceiro) ? NULL : $request->agenciaRecursoFinanceiro),
            'contaRecursoFinanceiro'        => (empty($request->contaRecursoFinanceiro) ? NULL : $request->contaRecursoFinanceiro),
            'localRecursoFinanceiro'        => (empty($request->localRecursoFinanceiro) ? NULL : $request->localRecursoFinanceiro),
            'codigoPessoaAlteracao'         => Auth::user()->codpes,
        ]);

        if(!empty($request->codigoInscricao))
        {
            $inscricaoRecursoFinanceiro = InscricoesRecursosFinanceiros::create([
                'codigoInscricao'           => $request->codigoInscricao,
                'codigoRecursoFinanceiro'   => $financeiro->codigoRecursoFinanceiro,
                'codigoPessoaAlteracao'     => Auth::user()->codpes,
            ]);

            $voltar = "inscricao/{$request->codigoInscricao}/financeiro";
        }

        if($financeiro && $inscricaoRecursoFinanceiro) 
        {
            \DB::commit();
        } 
        else 
        {
            \DB::rollBack();
        }

        request()->session()->flash('alert-success', 'Recurso Financeiro cadastrado com sucesso.');
        
        return redirect($voltar);  
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RecursoFinanceiro  $recursoFinanceiro
     * @return \Illuminate\Http\Response
     */
    public function show(RecursoFinanceiro $recursoFinanceiro)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RecursoFinanceiro  $recursoFinanceiro
     * @return \Illuminate\Http\Response
     */
    public function edit(RecursoFinanceiro $recursoFinanceiro)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RecursoFinanceiro  $recursoFinanceiro
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RecursoFinanceiro $recursoFinanceiro)
    {
        $temp = '';
        $voltar = '/financeiro';
        $inscricaoFinanceiro = true;
        
        \DB::beginTransaction();

        $financeiro = RecursoFinanceiro::find($request->codigoRecursoFinanceiro);

        $financeiro->codigoUsuario                 = Auth::user()->id;
        $financeiro->bolsaRecursoFinanceiro        = $request->inlineBolsa;
        $financeiro->solicitarRecursoFinanceiro    = $request->inlineSolicitar;
        $financeiro->orgaoRecursoFinanceiro        = (empty($request->orgaoRecursoFinanceiro) ? NULL : $request->orgaoRecursoFinanceiro);
        $financeiro->tipoBolsaFinanceiro           = (empty($request->tipoBolsaFinanceiro) ? NULL : $request->tipoBolsaFinanceiro);
        $financeiro->inicioRecursoFinanceiro       = (empty($request->inicioRecursoFinanceiro) ? NULL : $request->inicioRecursoFinanceiro);
        $financeiro->finalRecursoFinanceiro        = (empty($request->finalRecursoFinanceiro) ? NULL : $request->finalRecursoFinanceiro);
        $financeiro->anoTitulacaoRecursoFinanceiro = (empty($request->anoTitulacaoRecursoFinanceiro) ? NULL : $request->anoTitulacaoRecursoFinanceiro);
        $financeiro->iesTitulacaoRecursoFinanceiro = (empty($request->iesTitulacaoRecursoFinanceiro) ? NULL : $request->iesTitulacaoRecursoFinanceiro);
        $financeiro->agenciaRecursoFinanceiro      = (empty($request->agenciaRecursoFinanceiro) ? NULL : $request->agenciaRecursoFinanceiro);
        $financeiro->contaRecursoFinanceiro        = (empty($request->contaRecursoFinanceiro) ? NULL : $request->contaRecursoFinanceiro);
        $financeiro->localRecursoFinanceiro        = (empty($request->localRecursoFinanceiro) ? NULL : $request->localRecursoFinanceiro);
        $financeiro->codigoPessoaAlteracao         = Auth::user()->codpes;
        $financeiro->save();
        
        if(!empty($request->codigoInscricao))
        {
            if(empty($request->codigoInscricaoRecursoFinanceiro))
            {
                $inscricaoRecursoFinanceiro = InscricoesRecursosFinanceiros::create([
                    'codigoInscricao'           => $request->codigoInscricao,
                    'codigoRecursoFinanceiro'   => $financeiro->codigoRecursoFinanceiro,
                    'codigoPessoaAlteracao'     => Auth::user()->codpes,
                ]);
            }
            else
            {
                $inscricaoFinanceiro = InscricoesRecursosFinanceiros::find($request->codigoInscricaoIdioma);
                $inscricaoFinanceiro->codigoInscricao         = $request->codigoInscricao;
                $inscricaoFinanceiro->codigoRecursoFinanceiro = $request->codigoRecursoFinanceiro;
                $inscricaoFinanceiro->codigoPessoaAlteracao   = Auth::user()->codpes;
                $inscricaoFinanceiro->save();
            }
        }

        $voltar = "inscricao/{$request->codigoInscricao}/financeiro";

        if($financeiro && $inscricaoFinanceiro) 
        {
            \DB::commit();
        } 
        else 
        {
            \DB::rollBack();
        }

        request()->session()->flash('alert-success', 'Recurso Financeiro atualizado com sucesso.');
        
        return redirect($voltar); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RecursoFinanceiro  $recursoFinanceiro
     * @return \Illuminate\Http\Response
     */
    public function destroy(RecursoFinanceiro $recursoFinanceiro)
    {
        //
    }
}
