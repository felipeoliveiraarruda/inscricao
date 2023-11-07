<?php

namespace App\Http\Controllers\PAE;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\PAE\Avaliacao;
use App\Models\PAE\Pae;
use App\Models\PAE\AvaliadorPae;
use App\Models\Avaliador;
use App\Models\Edital;
use App\Models\Utils;

class AvaliacaoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $avaliadores = AvaliadorPae::all();

        
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PAE\Avaliacao  $avaliacao
     * @return \Illuminate\Http\Response
     */
    public function show(Avaliacao $avaliacao)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PAE\Avaliacao  $avaliacao
     * @return \Illuminate\Http\Response
     */
    public function edit(Avaliacao $avaliacao)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PAE\Avaliacao  $avaliacao
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Avaliacao $avaliacao)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PAE\Avaliacao  $avaliacao
     * @return \Illuminate\Http\Response
     */
    public function destroy(Avaliacao $avaliacao)
    {
        //
    }

    public function distribuicao($codigoEdital)
    {
        $confirmados = Pae::obterConfirmados($codigoEdital, true);
        $avaliadores = Avaliador::obterAvaliadores($codigoEdital, true);
        $anosemestre = Edital::obterSemestreAno($codigoEdital);        
        $total       = $confirmados / $avaliadores;
        
        return view('pae.distribuicao',
        [
            'confirmados'   => $confirmados,            
            'avaliadores'   => $avaliadores,
            'anosemestre'   => $anosemestre,
            'total'         => $total,
            'codigoEdital'  => $codigoEdital,
        ]);
    }

    public function distribuicao_store(Request $request)
    {
        $confirmados = Pae::obterConfirmados($request->codigoEdital)->toArray();
        $avaliadores = Avaliador::obterAvaliadores($request->codigoEdital);
        $anosemestre = Edital::obterSemestreAno($request->codigoEdital);

        $total_avaliadores = count($avaliadores);
        $total_confirmados = count($confirmados);
        $total             = round($total_confirmados / $total_avaliadores);
        
        for($i = 1; $i <= $total; $i++)
        {
            if ($i == $total)
            {
                $avaliadores2 = $avaliadores->toArray();
            }

            foreach($avaliadores as $avaliador)
            {
                if(count($confirmados) > 0)
                {
                    if ($i == $total)
                    {
                        $sortear  = array_rand($confirmados);
                        $sortear2 = array_rand($avaliadores2);

                        $codigoAvaliador = $avaliadores2[$sortear2]["codigoAvaliador"];
                        $codigoPae       = $confirmados[$sortear]["codigoPae"];

                        unset($confirmados[$sortear]);
                        unset($avaliadores2[$sortear2]);

                        $avaliadorPae = AvaliadorPae::create([
                            'codigoAvaliador'       => $codigoAvaliador,
                            'codigoPae'             => $codigoPae,
                            'codigoPessoaAlteracao' => Auth::user()->codpes,
                        ]);
                    }
                    else
                    {
                        $sortear = array_rand($confirmados);

                        $codigoAvaliador = $avaliador->codigoAvaliador;
                        $codigoPae       = $confirmados[$sortear]["codigoPae"];

                        unset($confirmados[$sortear]);

                        $avaliadorPae = AvaliadorPae::create([
                            'codigoAvaliador'       => $codigoAvaliador,
                            'codigoPae'             => $codigoPae,
                            'codigoPessoaAlteracao' => Auth::user()->codpes,
                        ]);
                    }
                }
            }
        }

        request()->session()->flash('alert-success', 'Distribuição de Avaliadores realizada com sucesso.');    
        return redirect("inscricao/{$codigoEdital}/pae");  
    }
}
