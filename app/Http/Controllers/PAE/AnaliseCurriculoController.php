<?php

namespace App\Http\Controllers\PAE;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use App\Models\Utils;
use App\Models\Arquivo;
use App\Models\Inscricao;
use App\Models\Edital;
use App\Models\PAE\Conceito;
use App\Models\PAE\Pae;
use App\Models\PAE\TipoAnalise;
use App\Models\PAE\AnaliseCurriculo;
use Uspdev\Replicado\Pessoa;
use Uspdev\Replicado\Posgraduacao;

class AnaliseCurriculoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($codigoPae)
    {
        if ((session('level') != 'admin') && (session('level') != 'manager'))
        {
            return redirect("/");
        }

        $inscricao   = Pae::obterPae($codigoPae);
        $tipos       = TipoAnalise::where('statusTipoAnalise', '=', 'S')->get();
        $anosemestre = Edital::obterSemestreAno($inscricao->codigoEdital);
        $vinculo     = Posgraduacao::obterVinculoAtivo($inscricao->codpes);
        $total       = AnaliseCurriculo::obterTotalAnalise($codigoPae);
        $arquivos    = Arquivo::listarArquivosAnalisePae($inscricao->codigoPae);

        return view('admin.pae.analise',
        [
            'utils'        => new Utils,
            'tipos'        => $tipos,
            'codigoPae'    => $codigoPae,
            'codigoEdital' => $inscricao->codigoEdital,
            'editar'       => ($total == 0 ? false : true),
            'inscricao'    => $inscricao,
            'vinculo'      => $vinculo,
            'arquivos'     => $arquivos,
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
