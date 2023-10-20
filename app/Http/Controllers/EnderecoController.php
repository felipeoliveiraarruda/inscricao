<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Endereco;
use App\Models\Utils;
use App\Models\InscricoesEnderecos;
use App\Http\Requests\EnderecoRequest;

class EnderecoController extends Controller
{    
    public function index()
    {
        $enderecos = Endereco::where('codigoUsuario', Auth::user()->id)->get(); 
        
        return view('endereco.index',
        [
            'enderecos' => $enderecos,
        ]);        
    }

    public function create($id)
    {
        return view('endereco.create',
        [
            'codigoInscricao' => $id,
        ]); 
    }

    public function store(Request $request)
    {
        $temp = '';
        $voltar = '/endereco';
        $inscricaoEndereco = true;
        
        \DB::beginTransaction();

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
                'codigoPessoaAlteracao' => Auth::user()->codpes,
            ]);

            $voltar = "inscricao/{$request->codigoInscricao}/endereco";
        }

        if($endereco && $inscricaoEndereco) 
        {
            \DB::commit();
        } 
        else 
        {
            \DB::rollBack();
        }

        request()->session()->flash('alert-success', 'Endereço cadastrado com sucesso.');
        
        return redirect($voltar);        
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, Endereco $enderecos)
    {
        $temp = '';
        $voltar = '/endereco';
        $inscricaoEndereco = true;
        
        \DB::beginTransaction();

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
        
        if(!empty($request->codigoInscricao))
        {
            $inscricaoEndereco = InscricoesEnderecos::find($request->codigoEndereco);
            $inscricaoEndereco->codigoInscricao       = $request->codigoInscricao;
            $inscricaoEndereco->codigoEndereco        = $endereco->codigoEndereco;
            $inscricaoEndereco->codigoPessoaAlteracao = Auth::user()->codpes;

            $voltar = "inscricao/{$request->codigoInscricao}/endereco";
        }

        if($endereco && $inscricaoEndereco) 
        {
            \DB::commit();
        } 
        else 
        {
            \DB::rollBack();
        }

        request()->session()->flash('alert-success', 'Endereço cadastrado com sucesso.');
        
        return redirect($voltar); 
    }

    public function destroy($id)
    {
        //
    }
}
