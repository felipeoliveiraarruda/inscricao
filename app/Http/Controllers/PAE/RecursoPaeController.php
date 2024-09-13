<?php

namespace App\Http\Controllers\PAE;

use App\Http\Controllers\Controller;
use App\Models\PAE\RecursoPae;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Utils;
use App\Models\Edital;
use App\Models\PAE\Pae;
use Uspdev\Replicado\Pessoa;
use Uspdev\Replicado\Posgraduacao;
use Uspdev\Replicado\Estrutura;
use Illuminate\Routing\UrlGenerator;
use Mail;
use App\Mail\PAE\RecursoMail;

class RecursoPaeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $anosemestre = Edital::obterSemestreAno($inscricao->codigoEdital);

        $recursos = RecursoPae::join('pae', 'recurso_pae.codigoPae', '=', 'pae.codigoPae')->get();

        return view('admin.pae.recurso',
        [
            'utils'        => new Utils,
            'recurso'      => $recurso,
            'codigoPae'    => $codigoPae,
            'codigoEdital' => $inscricao->codigoEdital,
        ]);
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
        $recurso = RecursoPae::create([
            'codigoPae'             => $request->codigoPae,
            'justificativaRecurso'  => $request->justificativaRecurso,
            'codigoPessoaAlteracao' => Auth::user()->codpes,
        ]); 

        Mail::to('pae@eel.usp.br')->send(new RecursoMail($request->codigoEdital, Auth::user()->id));
        
        if (Mail::failures()) 
        {
            request()->session()->flash('alert-danger', "Ocorreu um erro no envio do Recurso.");
        }    
        else
        {
            request()->session()->flash('alert-success', "Recurso enviado com sucesso.");
        } 

        return redirect("inscricao/{$request->codigoEdital}/pae/resultado");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PAE\RecursoPae  $recursoPae
     * @return \Illuminate\Http\Response
     */
    public function show(RecursoPae $recursoPae)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PAE\RecursoPae  $recursoPae
     * @return \Illuminate\Http\Response
     */
    public function edit($codigoRecurso)
    {
        $recursos = RecursoPae::join('pae', 'recurso_pae.codigoPae', '=', 'pae.codigoPae')
                              ->join('inscricoes', 'pae.codigoInscricao', '=', 'inscricoes.codigoInscricao')
                              ->join('users', 'inscricoes.codigoUsuario', '=', 'users.id')
                              ->where('recurso_pae.codigoRecurso', $codigoRecurso)
                              ->first();

        $vinculo = Posgraduacao::obterVinculoAtivo($recursos->codpes);

        return view('admin.pae.recurso.edit',
        [
            'utils'        => new Utils,
            'recurso'      => $recursos,
            'codigoPae'    => $recursos->codigoPae,
            'codigoEdital' => $recursos->codigoEdital,
            'vinculo'      => $vinculo,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PAE\RecursoPae  $recursoPae
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $recurso = RecursoPae::find($request->codigoRecurso);
        $pae = Pae::obterPae($recurso->codigoPae);
       
        $recurso->statusRecurso         = $request->statusRecurso[0];
        $recurso->analiseRecurso        = $request->analiseRecurso; 
        $recurso->codigoPessoaAlteracao = Auth::user()->codpes;      
        $recurso->save();
        
       /* Mail::to('pae@eel.usp.br')->send(new RecursoMail($request->codigoEdital, Auth::user()->id));
        
        if (Mail::failures()) 
        {
            request()->session()->flash('alert-danger', "Ocorreu um erro no envio da avaliação do Recurso.");
        }    
        else
        {
            request()->session()->flash('alert-success', "Recurso avaliado com sucesso.");
        }*/

        request()->session()->flash('alert-success', "Recurso avaliado com sucesso.");

        return redirect("admin/{$request->codigoRecurso}/pae/recurso");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PAE\RecursoPae  $recursoPae
     * @return \Illuminate\Http\Response
     */
    public function destroy(RecursoPae $recursoPae)
    {
        //
    }
}
