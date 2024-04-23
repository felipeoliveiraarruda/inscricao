<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use App\Models\Utils;
use App\Models\Inscricao;
use App\Models\Emergencia;
use App\Models\Endereco;
use App\Models\InscricoesEnderecos;
use Carbon\Carbon;

class EmergenciaController extends Controller
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
        $voltar = '/emergencia';
        $inscricaoEndereco = true;
        $endereco = true;
        
        \DB::beginTransaction();

        $emergencia = Emergencia::create([
            'codigoUsuario'            => Auth::user()->id,
            'nomePessoaEmergencia'     => $request->nomePessoaEmergencia,
            'telefonePessoaEmergencia' => $request->telefonePessoaEmergencia,
            'codigoPessoaAlteracao'    => Auth::user()->codpes,
        ]);

        if($request->mesmoEndereco == 'S')
        {
            $inscricaoEndereco = InscricoesEnderecos::find($request->codigoInscricaoEndereco);
            $inscricaoEndereco->codigoEmergencia = $emergencia->codigoEmergencia;
            $inscricaoEndereco->save();

            if(!empty($request->codigoInscricao))
            {
                $voltar = "inscricao/{$request->codigoInscricao}/emergencia";
            }
        }
        else
        {
            $endereco = Endereco::create([
                'codigoUsuario'         => Auth::user()->id,
                'cepEndereco'           => $request->cep,
                'logradouroEndereco'    => $request->logradouro,
                'numeroEndereco'        => $request->numero,
                'complementoEndereco'   => $request->complemento,
                'bairroEndereco'        => $request->bairro,
                'localidadeEndereco'    => $request->localidade,
                'ufEndereco'            => $request->uf,
                'codigoPessoaAlteracao' => Auth::user()->codpes,
            ]);
            
            if(!empty($request->codigoInscricao))
            {
                $inscricaoEndereco = InscricoesEnderecos::create([
                    'codigoInscricao'       => $request->codigoInscricao,
                    'codigoEndereco'        => $endereco->codigoEndereco,
                    'codigoEmergencia'      => $emergencia->codigoEmergencia,
                    'codigoPessoaAlteracao' => Auth::user()->codpes,
                ]);
    
                $voltar = "inscricao/{$request->codigoInscricao}/emergencia";
            }
        }

        if($emergencia && $endereco && $inscricaoEndereco) 
        {
            \DB::commit();
        } 
        else 
        {
            \DB::rollBack();
        }

        request()->session()->flash('alert-success', 'Pessoa a ser notificada em caso de Emergência cadastrada com sucesso.');
        
        return redirect($voltar); 
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
        $temp = '';
        $voltar = '/emergencia';
        $inscricaoEndereco = true;
        $endereco = true;
        
        \DB::beginTransaction();

        $emergencia = Emergencia::find($request->codigoEmergencia);
        $emergencia->codigoUsuario            = Auth::user()->id;
        $emergencia->nomePessoaEmergencia     = $request->nomePessoaEmergencia;
        $emergencia->telefonePessoaEmergencia = $request->telefonePessoaEmergencia;
        $emergencia->codigoPessoaAlteracao    = Auth::user()->codpes;
        $emergencia->save();

        if(!empty($request->codigoInscricao))
        {
            $inscricaoEndereco = InscricoesEnderecos::where('codigoInscricao', '=', $request->codigoInscricao)->first();

            if($request->mesmoEnderecoOptions[0] == 'S')
            {                
                $inscricaoEndereco->codigoEmergencia = $emergencia->codigoEmergencia;
                $inscricaoEndereco->mesmoEndereco    = $request->mesmoEnderecoOptions[0];
                $inscricaoEndereco->save();
    
                $voltar = "inscricao/{$request->codigoInscricao}/emergencia";
            }
            else
            {
                $inscricaoEndereco->mesmoEndereco = $request->mesmoEnderecoOptions[0];
                $inscricaoEndereco->save();

                if(empty($inscricaoEndereco->codigoInscricaoEndereco))
                {
                    $endereco = Endereco::create([
                        'codigoUsuario'         => Auth::user()->id,
                        'cepEndereco'           => $request->cep,
                        'logradouroEndereco'    => $request->logradouro,
                        'numeroEndereco'        => $request->numero,
                        'complementoEndereco'   => $request->complemento,
                        'bairroEndereco'        => $request->bairro,
                        'localidadeEndereco'    => $request->localidade,
                        'ufEndereco'            => $request->uf,
                        'codigoPessoaAlteracao' => Auth::user()->codpes,
                    ]);

                    $inscricaoEndereco = InscricoesEnderecos::create([
                        'codigoInscricao'       => $request->codigoInscricao,
                        'codigoEndereco'        => $endereco->codigoEndereco,
                        'codigoEmergencia'      => $emergencia->codigoEmergencia,
                        'mesmoEndereco'         => $request->mesmoEnderecoOptions[0],
                        'codigoPessoaAlteracao' => Auth::user()->codpes,
                    ]);
                }
                else
                {
                    $endereco = Endereco::find($request->codigoEndereco);
                    $endereco->codigoUsuario         = Auth::user()->id;
                    $endereco->cepEndereco           = $request->cep;
                    $endereco->logradouroEndereco    = $request->logradouro;
                    $endereco->numeroEndereco        = $request->numero;
                    $endereco->complementoEndereco   = $request->complemento;
                    $endereco->bairroEndereco        = $request->bairro;
                    $endereco->localidadeEndereco    = $request->localidade;
                    $endereco->ufEndereco            = $request->uf;
                    $endereco->codigoPessoaAlteracao = Auth::user()->codpes;
                    $endereco->save();

                    $inscricaoEndereco = InscricoesEnderecos::find($request->codigoInscricaoEndereco);
                    $inscricaoEndereco->codigoInscricao       = $request->codigoInscricao;
                    $inscricaoEndereco->codigoEndereco        = $request->codigoEndereco;
                    $inscricaoEndereco->codigoEmergencia      = $request->codigoEmergencia;
                    $inscricaoEndereco->mesmoEndereco         = $request->mesmoEnderecoOptions[0];
                    $inscricaoEndereco->codigoPessoaAlteracao = Auth::user()->codpes;
                    $inscricaoEndereco->save();
                }

                $voltar = "inscricao/{$request->codigoInscricao}/emergencia";
            }
        }

        if($emergencia && $endereco && $inscricaoEndereco) 
        {
            \DB::commit();
        } 
        else 
        {
            \DB::rollBack();
        }

        request()->session()->flash('alert-success', 'Pessoa a ser notificada em caso de Emergência cadastrada com sucesso.');
        
        return redirect($voltar); 
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
