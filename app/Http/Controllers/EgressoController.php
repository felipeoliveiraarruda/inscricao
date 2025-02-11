<?php

namespace App\Http\Controllers;

use App\Models\Egresso;
use App\Models\Utils;
use Illuminate\Http\Request;

class EgressoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('egressos.create');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $egresso = Egresso::create([
            'egressoNome'       => Utils::tratarNome($request->egressoNome),
            'egressoEmail'      => $request->egressoEmail,
            'egressoRegular'    => $request->egressoRegular,
            'egressoNivel'      => $request->egressoNivel,
            'egressoLocal'      => $request->egressoLocal,
            'egressoAtividade'  => $request->egressoAtividade,
        ]);
        
                
        if ($request->egressoRegular === 'S')
        {
            request()->session()->flash('alert-success','Dados cadastrado com sucesso');
            return view('egressos.regular');
        }
        else
        {
            return redirect('https://cpg.eel.usp.br/apoio-administrativo/editais-de-selecao');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Egresso  $egresso
     * @return \Illuminate\Http\Response
     */
    public function show(Egresso $egresso)
    {
        $egresso = Egresso::find($egresso->codigoEgresso);

        return view('egressos.show',
        [
            'egresso'  =>  $egresso,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Egresso  $egresso
     * @return \Illuminate\Http\Response
     */
    public function edit(Egresso $egresso)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Egresso  $egresso
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Egresso $egresso)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Egresso  $egresso
     * @return \Illuminate\Http\Response
     */
    public function destroy(Egresso $egresso)
    {
        //
    }

    public function listar()
    {
        $egressos = Egresso::orderBy('egressoNome')->paginate(30);

        return view('egressos.listar',
        [
            'egressos'  =>  $egressos,
        ]);
    }

    public function regular()
    {
        return view('egressos.regular');
    }
}
