<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Edital;
use App\Models\Arquivo;
use App\Models\Inscricao;
use App\Models\InscricoesArquivos;
use App\Models\TipoDocumento;
use App\Http\Requests\ArquivoRequest;
use App\Http\Requests\Arquivo\ImagemRequest;
use App\Http\Requests\Arquivo\DocumentoRequest;
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

        $edital = Edital::obterEditalInscricao($codigoInscricao);

        if (!empty($codigoInscricao))
        {
            $arquivo = Arquivo::find($codigoArquivo);

            /*if ($arquivo->codigoTipoDocumento == 8 || $arquivo->codigoTipoDocumento == 9)
            {
                $voltar = "inscricao/{$codigoInscricao}/curriculo";
            }
            else
            {
                $voltar = "inscricao/{$codigoInscricao}/pessoal";
            }*/
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
            'link_voltar'     => "inscricao/{$edital->codigoEdital}", 
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
            request()->session()->flash('alert-success', 'Documento removido com sucesso');  
        }
        else
        {
            request()->session()->flash('alert-danger', 'Ocorreu um erro na remoção do Documento.');
        }
        
        if(!empty($codigoInscricao))
        {
            if ($codigoTipoDocumento == 8 || $codigoTipoDocumento == 9)
            {
                return redirect("inscricao/{$codigoInscricao}/curriculo");
            }
            else
            {
                return redirect("inscricao/{$codigoInscricao}/pessoal");    
            }
        }
        else
        {
            return redirect('arquivos');
        }
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
            session()->flash('alert-danger', 'Ocorreu um erro na anexação do Documento a Inscrição.');
        }

        $edital = Edital::obterEditalInscricao($codigoInscricao);

        return redirect("inscricao/{$$edital->codigoEdital}");

        /*if ($arquivo->codigoTipoDocumento == 8 || $arquivo->codigoTipoDocumento == 9)
        {
            return redirect("inscricao/{$codigoInscricao}/curriculo");
        }
        else
        {
            return redirect("inscricao/{$codigoInscricao}/pessoal");    
        }*/
    }

    public function imagem($codigoInscricao, $codigoTipoDocumento)
    {
        if ($codigoTipoDocumento == 27)
        {
            return view('arquivo.imagem',
            [
                'codigoInscricao'     => $codigoInscricao,
                'codigoTipoDocumento' => $codigoTipoDocumento,
            ]);
        }
    }

    public function store_imagem(ImagemRequest $request)
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

        request()->session()->flash('alert-success', 'Arquivo cadastrado com sucesso');    
        return redirect("/inscricao/{$request->codigoInscricao}");
    }

    public function download($codigoInscricao)
    {
        $selected_file_ids = $request->input('selected_file_ids');
        $selected_file_ids_arr = explode(',',$selected_file_ids);

        $files = UploadedFile::whereIn('id',$selected_file_ids_arr)->get();
        $zip      = new ZipArchive;
        $fileName = 'downloads.zip';

        if ($zip->open(public_path($fileName), ZipArchive::CREATE) === TRUE) {
            foreach ($files as $file){
               $path =  public_path('storage/uploads/'.$file->user_id.'/'.$file->file_name);
                $relativeName = basename($path);
                $zip->addFile($path, $relativeName);
            }
            $zip->close();
        }

        return response()->download(public_path($fileName));
    }
}

