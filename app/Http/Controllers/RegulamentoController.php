<?php

namespace App\Http\Controllers;

use App\Models\Regulamento;
use Illuminate\Http\Request;
use App\Models\Edital;
use App\Models\Utils;
use App\Models\RegulamentosUsers;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Arquivo\DocumentoRequest;

class RegulamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $regulamento = RegulamentosUsers::join('regulamentos', 'regulamentos_users.codigoRegulamento', '=', 'regulamentos.codigoRegulamento')
                                        ->where('codigoUsuario', '=', Auth::user()->id)
                                        ->first();

        return view('regulamentacao.index',
        [
            'regulamento'   => $regulamento,    
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($codigoRegulamento)
    {
        $regulamento = Regulamento::find($codigoRegulamento);

        return view('regulamentacao.create',
        [
            'regulamento'   => $regulamento,            
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        RegulamentosUsers::create([
            'codigoRegulamento'     => $request->codigoRegulamento,
            'codigoUsuario'         => Auth::user()->id,
            'statusRegulamento'     => $request->opcaoResolucao,
            'codigoPessoaAlteracao' => Auth::user()->codpes,
        ]); 

        request()->session()->flash('alert-success', 'Opção de Regulamento cadastrada com sucesso.');
        
        return redirect('regulamentacao/index');     
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Regulamento  $regulamento
     * @return \Illuminate\Http\Response
     */
    public function show(Regulamento $regulamento)
    {
        //alunosPrograma(int $codundclgi, int $codcur, int $codare = null)
    
        // se $codare é null, seleciona todas

        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Regulamento  $regulamento
     * @return \Illuminate\Http\Response
     */
    public function edit(Regulamento $regulamento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Regulamento  $regulamento
     * @return \Illuminate\Http\Response
     */
    public function update(DocumentoRequest $request, $codigoRegulamentoUser)
    {
        $validated = $request->validated();

        $path = $request->file('arquivo')->store('arquivos', 'public');

        $regulamento = RegulamentosUsers::find($codigoRegulamentoUser);
        $regulamento->linkArquivo = $path;
        $regulamento->save();

        request()->session()->flash('alert-success', 'Opção de Regulamento atualizada com sucesso');
        
        return redirect("regulamentacao/index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Regulamento  $regulamento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Regulamento $regulamento)
    {
        //
    }
}
