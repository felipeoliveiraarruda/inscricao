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

class RecursoPaeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($codigoPae)
    {
        $inscricao   = Pae::obterPae($codigoPae);
        $anosemestre = Edital::obterSemestreAno($inscricao->codigoEdital);

        $recurso = RecursoPae::find($codigoPae);

        return view('pae.recurso.index',
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
            request()->session()->flash('alert-success', "Recurso ennviado com sucesso.");
        } 

        return redirect("inscricao/{$request->codigoEdital}/pae/recurso");
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
    public function edit(RecursoPae $recursoPae)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PAE\RecursoPae  $recursoPae
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RecursoPae $recursoPae)
    {
        //
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
