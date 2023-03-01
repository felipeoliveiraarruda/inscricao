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

    public function edit($id, $inscricao = '')
    {
        if (!empty($inscricao))
        {
            //$temp = Inscricao::where('codigoUsuario', Auth::user()->id)->where('codigoInscricao', $inscricao)->first();
            $voltar = "pessoal/novo/{$inscricao}";
        }
        else
        {
            $voltar = 'arquivo';
        }

        $tipos = TipoDocumento::all();

        $arquivo = Arquivo::find($id);

        return view('arquivo.edit',
        [
            'arquivo'         => $arquivo,
            'tipos'           => $tipos, 
            'codigoInscricao' => $inscricao,
            'link_voltar'     => $voltar, 
        ]);

        /*return view('pessoal.edit',
        [
            'codigoInscricao'   => $inscricao,
            'link_voltar'       => $voltar,
            'sexos'             => Utils::obterDadosSysUtils('sexo'),
            'racas'             => Utils::obterDadosSysUtils('raça'),
            'estados_civil'     => Utils::obterDadosSysUtils('civil'),
        ]);*/
    }

    public function update(Request $request, $id)
    { 
        $temp = explode('/', $request->arquivoAtual);

        $remover = unlink(Storage::path('public/'.$request->arquivoAtual));

        $path = $request->file('arquivo')->storeAs('arquivos', $temp[1], 'public');

        $arquivo = Arquivo::find($id);

        $arquivo->codigoUsuario             = Auth::user()->id; 
        $arquivo->codigoTipoDocumento       = $request->codigoTipoDocumento;
        $arquivo->linkArquivo               = $path;
        $arquivo->codigoPessoaAlteracao     = Auth::user()->codpes;
        $arquivo->save();

        request()->session()->flash('alert-success', 'Documento atualizado com sucesso');

        if(!empty($request->codigoInscricao))
        {
            return redirect("pessoal/novo/".$request->codigoInscricao);
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

    public function alterar(Request $request, $codigoArquivo)
    {
        /*$arquivo = Arquivo::find($codigoArquivo);
                
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
        
        return redirect("/inscricao/{$codigoInscricao}/documento");*/
        dd($request);
    }    
}

