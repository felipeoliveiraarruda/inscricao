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
    public function email()
    {
        Mail::to('felipeoa@usp.br')->send(new EnviarEmailComprovante(1, 'arquivos/RcYhM9iTRtYyaaReDUBe1X7AnG4013VSyjRecQc6.pdf'));

        if (Mail::failures()) 
        {
            request()->session()->flash('alert-danger', 'Ocorreu um erro no envio do Comprovante de Inscrição.');
        }    
        else
        {
            request()->session()->flash('alert-success', 'Comprovante de Inscrição enviado com sucesso.');
        }

        return redirect("/inscricao/1");
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

        InscricoesArquivos::create([
            'codigoInscricao'       => $request->codigoInscricao,
            'codigoArquivo'         => $arquivo->codigoArquivo,
            'codigoPessoaAlteracao' => Auth::user()->codpes,
        ]);

        /*if ($request->codigoTipoDocumento == 12)
        {
            Inscricao::where('codigoInscricao', $request->codigoInscricao)->update(['situacaoInscricao' => 'P']);
            Mail::to('felipeoa@usp.br')->send(new EnviarEmailComprovante($request->codigoInscricao, $path));

            if (Mail::failures()) 
            {
                request()->session()->flash('alert-danger', 'Ocorreu um erro no envio do Comprovante de Inscrição.');
            }    
            else
            {
                request()->session()->flash('alert-success', 'Comprovante de Inscrição enviado com sucesso.');
            }
        }
        else
        {
            request()->session()->flash('alert-success', 'Documento cadastrado com sucesso');    
        }*/

        request()->session()->flash('alert-success', 'Documento cadastrado com sucesso');    
        return redirect("/inscricao/{$request->codigoInscricao}/documento");
    }

    public function edit($id)
    {
        $tipos = TipoDocumento::all();

        $arquivo = Arquivo::find($id);

        return view('arquivo.edit',
        [
            'arquivo'         => $arquivo,
            'tipos'           => $tipos, 
            'codigoInscricao' => $arquivo->codigoInscricao, 
        ]);
    }

    public function update(Request $request, $id)
    {
        //
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

    public function remover($codigoInscricao, $codigoArquivo)
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
    }    
}

