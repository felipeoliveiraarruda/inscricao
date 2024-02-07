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
                                         ->orderBy('users.name')
                                         ->get();

        $deferidos = InscricoesDisciplinas::select('users.*', 'inscricoes_disciplinas.*')
                                        ->join('inscricoes', 'inscricoes.codigoInscricao', '=', 'inscricoes_disciplinas.codigoInscricao')
                                        ->join('users', 'inscricoes.codigoUsuario', '=', 'users.id')
                                        ->whereIn('inscricoes_disciplinas.codigoDisciplina', $disciplina)
                                        ->where('inscricoes_disciplinas.statusDisciplina', 'D')
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
}
