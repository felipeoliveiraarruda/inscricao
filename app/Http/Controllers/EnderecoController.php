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

    public function store(EnderecoRequest $request)
    {
        $validated = $request->validated();

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

        InscricoesEnderecos::create([
            'codigoInscricao'       => $request->codigoInscricao,
            'codigoEndereco'        => $endereco->codigoEndereco,
            'codigoPessoaAlteracao' => Auth::user()->codpes,
        ]);

        request()->session()->flash('alert-success', 'Endereço cadastrado com sucesso.');
        return redirect("/inscricao/{$request->codigoInscricao}/endereco");
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
