<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Edital;
use App\Models\Utils;
use App\Models\Inscricao;
use App\Models\InscricoesDisciplinas;
use App\Models\Pdf\Matricula;
use Uspdev\Replicado\Posgraduacao;
use Carbon\Carbon;

class DeferimentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($codigoEdital)
    {
        $disciplina = array();

        $edital = Edital::find($codigoEdital);

        if($edital->dataFinalRecurso < Carbon::now())
        {
            $item = array();
            $item['title'] = 'Aviso';
            $item['story'] = 'Período de Deferimento encerrado';

            return view('components.modal',
            [
                'item' => $item,                
            ]);
        }

        $temp = Utils::listarOferecimentoPosDocente($edital->codigoCurso, Auth::user()->codpes, '04/03/2024', '16/06/2024', 'S');

        if (!empty($temp))
        {
                array_push($disciplina, $temp[0]['sgldis']);
        }

        $inscritos = InscricoesDisciplinas::select('users.*', 'inscricoes_disciplinas.*')
                                            ->join('inscricoes', 'inscricoes.codigoInscricao', '=', 'inscricoes_disciplinas.codigoInscricao')
                                            ->join('users', 'inscricoes.codigoUsuario', '=', 'users.id')
                                            ->whereIn('inscricoes_disciplinas.codigoDisciplina', $disciplina)
                                            ->where('inscricoes_disciplinas.statusDisciplina', 'N')
                                            ->where('inscricoes.statusInscricao', 'P')
                                            ->orderBy('users.name')
                                            ->get();

        $deferidos = InscricoesDisciplinas::select('users.*', 'inscricoes_disciplinas.*')
                                        ->join('inscricoes', 'inscricoes.codigoInscricao', '=', 'inscricoes_disciplinas.codigoInscricao')
                                        ->join('users', 'inscricoes.codigoUsuario', '=', 'users.id')
                                        ->whereIn('inscricoes_disciplinas.codigoDisciplina', $disciplina)
                                        ->where('inscricoes_disciplinas.statusDisciplina', 'D')
                                        ->where('inscricoes.statusInscricao', 'P')
                                        ->orderBy('users.name')
                                        ->get();                                            
                                                                                
        $temp2 = Posgraduacao::disciplina($disciplina[0]);

        return view('inscricao.deferimento',
        [
            'codigoEdital'  => $codigoEdital,
            'inscritos'     => $inscritos,
            'deferidos'     => $deferidos,
            'disciplina'    => $temp2['sgldis'].' - '.$temp2['nomdis']
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
        foreach($request->deferimentoCandidato as $deferimento)
        {
            $inscricao = InscricoesDisciplinas::find($deferimento);

            $inscricao->statusDisciplina      = 'D';
            $inscricao->codigoPessoaAlteracao = Auth::user()->codpes;
            $inscricao->save();
        }

        request()->session()->flash('alert-success', 'Deferimento realizado com sucesso.');    
        
        return redirect("inscricao/deferimento/{$request->codigoEdital}");
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
        $inscricao = InscricoesDisciplinas::find($id);

        $inscricao->statusDisciplina      = 'N';
        $inscricao->codigoPessoaAlteracao = Auth::user()->codpes;
        $inscricao->save();
        
        request()->session()->flash('alert-success', 'Deferimento desfeito com sucesso.');    
        
        return redirect(url()->previous());
    }


    public function listar($codigoEdital)
    {
       $disciplinas = InscricoesDisciplinas::select('inscricoes_disciplinas.codigoDisciplina')
                                         ->join('inscricoes', 'inscricoes.codigoInscricao', '=', 'inscricoes_disciplinas.codigoInscricao')
                                         ->where('inscricoes.codigoEdital', $codigoEdital)
                                         ->groupBy('inscricoes_disciplinas.codigoDisciplina')
                                         ->orderBy('inscricoes_disciplinas.codigoDisciplina')
                                         ->get();
                                         
        return view('deferimento',
        [
            'codigoEdital'  => $codigoEdital,
            'disciplinas'   => $disciplinas,
        ]);
    }

    public function primeira_matricula($codigoEdital)
    {
        $deferidos = InscricoesDisciplinas::select('users.*', 'inscricoes_disciplinas.*')
                                        ->join('inscricoes', 'inscricoes.codigoInscricao', '=', 'inscricoes_disciplinas.codigoInscricao')
                                        ->join('users', 'inscricoes.codigoUsuario', '=', 'users.id')
                                        ->where('inscricoes.codigoEdital', $codigoEdital)
                                        ->where('inscricoes_disciplinas.statusDisciplina', 'D')
                                        ->where('inscricoes.statusInscricao', 'P')
                                        ->groupBy('inscricoes.codigoInscricao')
                                        ->orderBy('users.name')
                                        ->get();

        foreach($deferidos as $deferido)
        {
            $edital = Edital::obterEditalInscricao($deferido->codigoInscricao);

            $pdf = new Matricula();

            if ($edital->codigoCurso == 97002)
            {
                $anexo = Inscricao::gerarMatricula($pdf, 'ppgem', $deferido->codigoInscricao);
            }

            if ($edital->codigoCurso == 97004)
            {
                $anexo = Inscricao::gerarMatricula($pdf, 'ppgpe', $deferido->codigoInscricao);
            }
        }
    }
}
