<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use App\Models\Utils;
use App\Models\Inscricao;
use App\Models\Arquivo;
use App\Models\ResumoEscolar;
use App\Models\InscricoesResumoEscolar;
use App\Models\InscricoesArquivos;
use Carbon\Carbon;

class ResumoEscolarController extends Controller
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
        $voltar = '/escolar';
        $inscricaoResumoEscolar = true;
        $inscricaoHistorico = true;
        $inscricaoDiploma = true;
        $diploma = false;
        
        \DB::beginTransaction();

        $escolar = ResumoEscolar::create([
            'codigoUsuario'                 => Auth::user()->id,
            'escolaResumoEscolar'           => $request->escolaResumoEscolar,
            'especialidadeResumoEscolar'    => $request->especialidadeResumoEscolar,
            'inicioResumoEscolar'           => $request->inicioResumoEscolar,
            'finalResumoEscolar'            => $request->finalResumoEscolar,
            'codigoPessoaAlteracao'         => Auth::user()->codpes,
        ]);

        // $path = $request->file('historicoEscolar')->store('arquivos', 'public');

        // $historico = Arquivo::create([
        //     'codigoUsuario'         => Auth::user()->id,
        //     'codigoTipoDocumento'   => 5,
        //     'linkArquivo'           => $path,
        //     'codigoPessoaAlteracao' => Auth::user()->codpes,
        // ]);

        // if ($request->file('diplomaEscolar'))
        // {
        //     $path = $request->file('diplomaEscolar')->store('arquivos', 'public');

        //     $diploma = Arquivo::create([
        //         'codigoUsuario'         => Auth::user()->id,
        //         'codigoTipoDocumento'   => $request->inlineDocumentos,
        //         'linkArquivo'           => $path,
        //         'codigoPessoaAlteracao' => Auth::user()->codpes,
        //     ]);
        // }

        if(!empty($request->codigoInscricao))
        {
            $inscricaoResumoEscolar = InscricoesResumoEscolar::create([
                'codigoInscricao'       => $request->codigoInscricao,
                'codigoResumoEscolar'   => $escolar->codigoResumoEscolar,
                'codigoPessoaAlteracao' => Auth::user()->codpes,
            ]);

            // $inscricaoHistorico = InscricoesArquivos::create([
            //     'codigoInscricao'       => $request->codigoInscricao,
            //     'codigoArquivo'         => $historico->codigoArquivo,
            //     'codigoPessoaAlteracao' => Auth::user()->codpes,
            // ]);

            // if ($diploma)
            // {
            //     $inscricaoDiploma = InscricoesArquivos::create([
            //         'codigoInscricao'       => $request->codigoInscricao,
            //         'codigoArquivo'         => $diploma->codigoArquivo,
            //         'codigoPessoaAlteracao' => Auth::user()->codpes,
            //     ]); 
            // }

            $voltar = "inscricao/{$request->codigoInscricao}/escolar";
        }

        //if($escolar && $inscricaoResumoEscolar && $inscricaoHistorico && $inscricaoDiploma) 

        if($escolar && $inscricaoResumoEscolar) 
        {
            \DB::commit();
        } 
        else 
        {
            \DB::rollBack();
        }

        request()->session()->flash('alert-success', 'Resumo Escolar cadastrado com sucesso.');
        
        return redirect($voltar); 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ResumoEscolar  $resumoEscolar
     * @return \Illuminate\Http\Response
     */
    public function show(ResumoEscolar $resumoEscolar)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ResumoEscolar  $resumoEscolar
     * @return \Illuminate\Http\Response
     */
    public function edit(ResumoEscolar $resumoEscolar)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ResumoEscolar  $resumoEscolar
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ResumoEscolar $resumoEscolar)
    {
        $temp = '';
        $voltar = '/escolar';
        $inscricaoResumoEscolar = true;
        $inscricaoHistorico = true;
        $inscricaoDiploma = true;
        $diploma = false;
        
        \DB::beginTransaction();

        $escolar = ResumoEscolar::find($request->codigoResumoEscolar);
        $escolar->codigoUsuario                 = Auth::user()->id;
        $escolar->escolaResumoEscolar           = $request->escolaResumoEscolar;
        $escolar->especialidadeResumoEscolar    = $request->especialidadeResumoEscolar;
        $escolar->inicioResumoEscolar           = $request->inicioResumoEscolar;
        $escolar->finalResumoEscolar            = $request->finalResumoEscolar;
        $escolar->codigoPessoaAlteracao         = Auth::user()->codpes;
        $escolar->save();

        if(!empty($request->codigoInscricao))
        {
            if ($request->file('historicoEscolar'))
            {
                $path = $request->file('historicoEscolar')->store('arquivos', 'public');

                $historico = Arquivo::create([
                    'codigoUsuario'         => Auth::user()->id,
                    'codigoTipoDocumento'   => 5,
                    'linkArquivo'           => $path,
                    'codigoPessoaAlteracao' => Auth::user()->codpes,
                ]);

                $inscricaoHistorico = InscricoesArquivos::create([
                    'codigoInscricao'       => $request->codigoInscricao,
                    'codigoArquivo'         => $historico->codigoArquivo,
                    'codigoPessoaAlteracao' => Auth::user()->codpes,
                ]);

                $arquivoHistorico = $inscricaoHistorico->codigoInscricaoArquivo;
            }
            else
            {
                $arquivoHistorico = $request->arquivoHistorico;
            }

            if ($request->file('diplomaEscolar'))
            {
                $path = $request->file('diplomaEscolar')->store('arquivos', 'public');
    
                $diploma = Arquivo::create([
                    'codigoUsuario'         => Auth::user()->id,
                    'codigoTipoDocumento'   => $request->inlineDocumentos,
                    'linkArquivo'           => $path,
                    'codigoPessoaAlteracao' => Auth::user()->codpes,
                ]);

                if ($diploma)
                {
                    $inscricaoDiploma = InscricoesArquivos::create([
                        'codigoInscricao'       => $request->codigoInscricao,
                        'codigoArquivo'         => $diploma->codigoArquivo,
                        'codigoPessoaAlteracao' => Auth::user()->codpes,
                    ]); 

                    $arquivoDiploma = $inscricaoDiploma->codigoInscricaoArquivo;
                }
            }
            else
            {
                $arquivoDiploma = $request->arquivoDiploma;
            }

            if(!empty($request->codigoInscricaoResumoEscolar))
            {
                $inscricaoResumoEscolar = InscricoesResumoEscolar::find($request->codigoInscricaoResumoEscolar);
                
                $inscricaoResumoEscolar->codigoInscricao       = $request->codigoInscricao;
                $inscricaoResumoEscolar->codigoResumoEscolar   = $escolar->codigoResumoEscolar;
                $inscricaoResumoEscolar->codigoPessoaAlteracao = Auth::user()->codpes;
                $inscricaoResumoEscolar->codigoHistorico       = $arquivoHistorico;
                $inscricaoResumoEscolar->codigoDiploma         = $arquivoDiploma;
                $inscricaoResumoEscolar->save();
            }
            else
            {
                $inscricaoResumoEscolar = InscricoesResumoEscolar::create([
                    'codigoInscricao'       => $request->codigoInscricao,
                    'codigoResumoEscolar'   => $escolar->codigoResumoEscolar,
                    'codigoHistorico'       => $arquivoHistorico,
                    'codigoDiploma'         => $arquivoDiploma,
                    'codigoPessoaAlteracao' => Auth::user()->codpes,
                ]);
            }

            $voltar = "inscricao/{$request->codigoInscricao}/escolar";
        }

        if($escolar && $inscricaoResumoEscolar && $inscricaoHistorico && $inscricaoDiploma) 
        {
            \DB::commit();
        } 
        else 
        {
            \DB::rollBack();
        }

        request()->session()->flash('alert-success', 'Resumo Escolar cadastrado com sucesso.');
        
        return redirect($voltar);         
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ResumoEscolar  $resumoEscolar
     * @return \Illuminate\Http\Response
     */
    public function destroy(ResumoEscolar $resumoEscolar)
    {
        //
    }
}
