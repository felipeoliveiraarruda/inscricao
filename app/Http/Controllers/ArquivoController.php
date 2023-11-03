<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Arquivo;
use App\Models\Inscricao;
use App\Models\InscricoesArquivos;
use App\Models\TipoDocumento;
use App\Http\Requests\ArquivoRequest;
use Mail;
use App\Mail\EnviarEmailComprovante;

class ArquivoController extends Controller
{    
    public function index()
    {
        $arquivos = Arquivo::where('codigoUsuario', Auth::user()->id)->get(); 
        
        return view('arquivo.index',
        [
            'arquivos' => $arquivos,
        ]);        
    }

    public function create($id)
    {
        $tipos = TipoDocumento::all();

        return view('arquivo.create',
        [
            'arquivo'         => new Arquivo,
            'codigoInscricao' => $id,
            'tipos'           => $tipos,  
            //'codigoTipoDocumento' => $id,
        ]);
    }

    public function store(ArquivoRequest $request)
    {
        $validated = $request->validated();

        $path = $request->file('arquivo')->store('arquivos', 'public');

        $arquivo = Arquivo::create([
            'codigoUsuario'         => Auth::user()->id,
            'codigoTipoDocumento'   => $request->codigoTipoDocumento,
            'linkArquivo'           => $path,
            'codigoPessoaAlteracao' => Auth::user()->codpes,
        ]);

        if (!empty($request->codigoInscricao))
        {
            InscricoesArquivos::create([
                'codigoInscricao'       => $request->codigoInscricao,
                'codigoArquivo'         => $arquivo->codigoArquivo,
                'codigoPessoaAlteracao' => Auth::user()->codpes,
            ]);
        }

        request()->session()->flash('alert-success', 'Documento cadastrado com sucesso');    
        return redirect("/inscricao/{$request->codigoInscricao}/documento");
    }

    public function edit($codigoArquivo, $codigoInscricao = '')
    {
        if (!empty($codigoInscricao))
        {
            //$temp = Inscricao::where('codigoUsuario', Auth::user()->id)->where('codigoInscricao', $inscricao)->first();
            $voltar = "inscricao/{$codigoInscricao}/pessoal";
        }
        else
        {
            $voltar = 'arquivo';
        }

        $tipos = TipoDocumento::all();

        $arquivo = Arquivo::find($codigoArquivo);

        return view('arquivo.edit',
        [
            'arquivo'         => $arquivo,
            'tipos'           => $tipos, 
            'codigoInscricao' => $codigoInscricao,
            'link_voltar'     => $voltar, 
        ]);
    }

    public function update(Request $request, $id)
    {     
        $remover = unlink(Storage::path($request->arquivoAtual));

        $path = $request->file('arquivo')->store('arquivos', 'public');

        $arquivo = Arquivo::find($id);

        $arquivo->codigoUsuario             = Auth::user()->id; 
        $arquivo->codigoTipoDocumento       = $request->codigoTipoDocumento;
        $arquivo->linkArquivo               = $path;
        $arquivo->codigoPessoaAlteracao     = Auth::user()->codpes;
        $arquivo->save();

        request()->session()->flash('alert-success', 'Documento atualizado com sucesso');

        if(!empty($request->codigoInscricao))
        {
            return redirect("inscricao/{$request->codigoInscricao}/pessoal");
        }
        else
        {
            return redirect('arquivos');
        }        
    }

    public function comprovante($id)
    {
        return view('arquivo.comprovante',
        [
            'arquivo'             => new Arquivo,
            'codigoInscricao'     => $id,
            'codigoTipoDocumento' => 12,
        ]);
    }

    public function destroy($codigoArquivo, $codigoInscricao = '')
    {
        $arquivo = Arquivo::find($codigoArquivo);
                
        $remover = unlink(Storage::path($arquivo->linkArquivo));

        $arquivo = Arquivo::where(['codigoArquivo' => $codigoArquivo])->delete();  

        if (!empty($codigoInscricao))
        {
            $inscricao = InscricoesArquivos::where(['codigoArquivo' => $codigoArquivo])
                                           ->where(['codigoInscricao' => $codigoInscricao])
                                           ->delete();
        }

        if(($remover) && ($inscricao) && ($arquivo))
        {
            request()->session()->flash('alert-success', 'Documento removido com sucesso');  
        }
        else
        {
            request()->session()->flash('alert-danger', 'Ocorreu um erro na remoção do Documento.');
        }
        
        if(!empty($codigoInscricao))
        {
            return redirect("inscricao/{$codigoInscricao}/pessoal");
        }
        else
        {
            return redirect('arquivos');
        }
    }

    /*public function remover($codigoInscricao, $codigoArquivo)
    {
        $arquivo = Arquivo::find($codigoArquivo);
                
        $remover = unlink(Storage::path('public/'.$arquivo->linkArquivo));

        $inscricao = InscricoesArquivos::where([
            'codigoInscricao'   => $codigoInscricao,
            'codigoArquivo'     => $codigoArquivo,
        ])->delete();

        $arquivo = Arquivo::where(['codigoArquivo' => $codigoArquivo])->delete();        

        if(($remover) && ($inscricao) && ($arquivo))
        {
            request()->session()->flash('alert-success', 'Documento removido com sucesso');  
        }
        else
        {
            request()->session()->flash('alert-danger', 'Ocorreu um erro na remoção do Documento.');
        }
        
        return redirect("/inscricao/{$codigoInscricao}/documento");
    } */
}

