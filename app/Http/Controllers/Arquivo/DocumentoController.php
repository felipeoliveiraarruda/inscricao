<?php

namespace App\Http\Controllers\Arquivo;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Edital;
use App\Models\Arquivo;
use App\Models\Inscricao;
use App\Models\InscricoesArquivos;
use App\Models\TipoDocumento;
use App\Http\Requests\Arquivo\DocumentoRequest;

class DocumentoController extends Controller
{
    public function create($codigoInscricao, $codigoTipoDocumento)
    {
        $codigoEdital = Inscricao::obterEditalInscricao($codigoInscricao);

        return view('arquivo.documento.create',
        [
            'codigoInscricao'     => $codigoInscricao,
            'codigoTipoDocumento' => $codigoTipoDocumento,
            'codigoEdital'        => $codigoEdital
        ]);
    }

    public function store(DocumentoRequest $request)
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

        if (!empty($request->inlineCoorientador))
        {
            $inscricao = Inscricao::find($request->codigoInscricao);
            $inscricao->planoCoorientador = $request->inlineCoorientador;
            $inscricao->save();
        }

        $edital = Edital::obterEditalInscricao($request->codigoInscricao);

        request()->session()->flash('alert-success', 'Arquivo cadastrado com sucesso');    
        return redirect("/inscricao/{$request->codigoInscricao}/obrigatorios");
    }

    public function edit($codigoArquivo, $codigoInscricao)
    {
        $arquivo = Arquivo::find($codigoArquivo);

        $tipos = TipoDocumento::all();        

        $edital = Edital::obterEditalInscricao($codigoInscricao);

        return view('arquivo.documento.edit',
        [
            'arquivo'               => $arquivo,
            'tipos'                 => $tipos, 
            'codigoInscricao'       => $codigoInscricao,
            'codigoTipoDocumento'   => $arquivo->codigoTipoDocumento,
            'link_voltar'           => "inscricao/{$edital->codigoEdital}", 
        ]);
    }

    public function update(DocumentoRequest $request, $codigoArquivo)
    {     
        $validated = $request->validated();

        //$remover = unlink(Storage::path($request->arquivoAtual));

        $path = $request->file('arquivo')->store('arquivos', 'public');

        $arquivo = Arquivo::find($codigoArquivo);

        $arquivo->codigoUsuario             = Auth::user()->id; 
        $arquivo->codigoTipoDocumento       = $request->codigoTipoDocumento;
        $arquivo->linkArquivo               = $path;
        $arquivo->codigoPessoaAlteracao     = Auth::user()->codpes;
        $arquivo->save();

        $edital = Edital::obterEditalInscricao($request->codigoInscricao);
        request()->session()->flash('alert-success', 'Arquivo atualizado com sucesso');
        return redirect("/inscricao/{$request->codigoInscricao}/obrigatorios");   
    }

    public function destroy($codigoArquivo, $codigoInscricao)
    {
        $arquivo = Arquivo::find($codigoArquivo);

        $codigoTipoDocumento = $arquivo->codigoTipoDocumento;
                
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
            request()->session()->flash('alert-success', 'Arquivo removido com sucesso');  
        }
        else
        {
            request()->session()->flash('alert-danger', 'Ocorreu um erro na remoção do Arquivo.');
        }
        
        $edital = Edital::obterEditalInscricao($codigoInscricao);
        return redirect("/inscricao/{$request->codigoInscricao}/obrigatorios");   
    }


    public function anexar($codigoArquivo, $codigoInscricao)
    {
        $arquivo = Arquivo::find($codigoArquivo);

        $inscricao = InscricoesArquivos::create([
            'codigoInscricao'       => $codigoInscricao,
            'codigoArquivo'         => $codigoArquivo,
            'codigoPessoaAlteracao' => Auth::user()->codpes,
        ]);

        if($inscricao)
        {
            session()->flash('alert-success', 'Arquivo anexado a Inscrição com sucesso');  
        }
        else
        {
            session()->flash('alert-danger', 'Ocorreu um erro na anexação do Arquivo a Inscrição.');
        }

        $edital = Edital::obterEditalInscricao($codigoInscricao);
        return redirect("/inscricao/{$request->codigoInscricao}/obrigatorios");   
    }

}
