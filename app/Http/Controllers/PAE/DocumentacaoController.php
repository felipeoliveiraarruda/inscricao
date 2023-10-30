<?php

namespace App\Http\Controllers\PAE;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use App\Models\Utils;
use App\Models\Edital;
use App\Models\Inscricao;
use App\Models\InscricoesArquivos;
use App\Models\Arquivo;
use App\Models\TipoDocumento;
use Uspdev\Replicado\Posgraduacao;
use Illuminate\Routing\UrlGenerator;

class DocumentacaoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($codigoEdital)
    {
        if ((in_array("Alunopos", session('vinculos')) == false && in_array("Alunoposusp", session('vinculos')) == false) && (session('level') != 'manager') && (session('level') != 'admin') )
        {
            return redirect("/");
        }

        $inscricao = Inscricao::obterInscricaoPae(Auth::user()->id, $codigoEdital);
        $total     = Arquivo::verificaArquivosPae($inscricao->codigoPae);

        $arquivos  = Arquivo::listarArquivosPae($inscricao->codigoPae);
       
        return view('pae.documentacao.index',
        [
            'utils'     => new Utils,
            'codigoPae' => $inscricao->codigoPae,
            'inscricao' => $inscricao,
            'arquivos'  => $arquivos,
            'total'        => $total,
            'temp'      => '',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($codigoEdital)
    {
        if ((in_array("Alunopos", session('vinculos')) == false && in_array("Alunoposusp", session('vinculos')) == false) && (session('level') != 'manager') && (session('level') != 'admin') )
        {
            return redirect("/");
        }
        
        $inscricao = Inscricao::obterInscricaoPae(Auth::user()->id, $codigoEdital);
        $tipos     = TipoDocumento::listarTipoDocumentos($codigoEdital);
        
        return view('pae.documentacao.create',
        [
            'utils'           => new Utils,
            'codigoPae'       => $inscricao->codigoPae,
            'codigoEdital'    => $codigoEdital,
            'codigoInscricao' => $inscricao->codigoInscricao,
            'tipos'           => $tipos,
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
        foreach($request->file('arquivo') as $arquivo)
        {
            $path = $arquivo->store('pae', 'public');

            $temp = Arquivo::create([
                'codigoUsuario'         => Auth::user()->id,
                'codigoTipoDocumento'   => $request->codigoTipoDocumento,
                'linkArquivo'           => $path,
                'codigoPessoaAlteracao' => Auth::user()->codpes,
            ]);
    
            $InscricoesArquivos = InscricoesArquivos::create([
                'codigoInscricao'       => $request->codigoInscricao,
                'codigoArquivo'         => $temp->codigoArquivo,
                'codigoPessoaAlteracao' => Auth::user()->codpes,
            ]);
        }

        request()->session()->flash('alert-success', 'Documentação cadastrada com sucesso.');    
        return redirect("inscricao/{$request->codigoEdital}/pae");
    }

    /**
     * Show the form for editing the specified resource.
     * {codigoPae}/pae/documentacao/{codigoTipoDocumento}/edit
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($codigoEdital, $codigoTipoDocumento)
    {
        if ((in_array("Alunopos", session('vinculos')) == false && in_array("Alunoposusp", session('vinculos')) == false) && (session('level') != 'manager') && (session('level') != 'admin') )
        {
            return redirect("/");
        }
        
        $inscricao = Inscricao::obterInscricaoPae(Auth::user()->id, $codigoEdital);
        $tipos     = TipoDocumento::listarTipoDocumentos($codigoEdital);
        $arquivos  = Arquivo::listarArquivosPae($inscricao->codigoPae, $codigoTipoDocumento);
        
        return view('pae.documentacao.edit',
        [
            'utils'                 => new Utils,
            'codigoPae'             => $inscricao->codigoPae,
            'codigoEdital'          => $codigoEdital,
            'codigoInscricao'       => $inscricao->codigoInscricao,
            'codigoTipoDocumento'   => $codigoTipoDocumento,
            'tipos'                 => $tipos,
            'arquivos'              => $arquivos,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $codigoPae, $codigoTipoDocumento)
    { 
        if (!empty($request->file('arquivo')))
        {
            foreach($request->file('arquivo') as $chave => $valor)
            {
                $remover = unlink(Storage::path($request->arquivoAtual[$chave]));

                $path = $request->file('arquivo')[$chave]->store('pae', 'public');
        
                $arquivo = Arquivo::find($chave);
        
                $arquivo->codigoUsuario             = Auth::user()->id; 
                $arquivo->codigoTipoDocumento       = $request->codigoTipoDocumento;
                $arquivo->linkArquivo               = $path;
                $arquivo->codigoPessoaAlteracao     = Auth::user()->codpes;
                $arquivo->save();
            }

            request()->session()->flash('alert-success', 'Documentação atualizada com sucesso.');    
            return redirect("inscricao/{$request->codigoEdital}/pae");  
        }      

        return redirect("inscricao/{$request->codigoEdital}/pae");  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($codigoEdital, $codigoTipoDocumento)
    {
        Arquivo::where('codigoUsuario',       '=', Auth::user()->id)
              ->where('codigoTipoDocumento', '=', $codigoTipoDocumento)
              ->delete();

        request()->session()->flash('alert-success', 'Documentação excluída com sucesso.');    
        return redirect("inscricao/{$codigoEdital}/pae");  
    }


    public function visualizar($codigoEdital, $codigoUsuario)
    {
        if ((in_array("Alunopos", session('vinculos')) == false && in_array("Alunoposusp", session('vinculos')) == false) && (session('level') != 'manager') && (session('level') != 'admin') )
        {
            return redirect("/");
        }        

        $inscricao = Inscricao::obterInscricaoPae($codigoUsuario, $codigoEdital);
        $total     = Arquivo::verificaArquivosPae($inscricao->codigoPae);
        $arquivos  = Arquivo::listarArquivosPae($inscricao->codigoPae);
       
        return view('pae.documentacao.visualizar',
        [
            'utils'     => new Utils,
            'codigoPae' => $inscricao->codigoPae,
            'inscricao' => $inscricao,
            'arquivos'  => $arquivos,
            'total'     => $total,
            'temp'      => '',
        ]);
    }
}