<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\Edital;
use App\Models\Utils;
use App\Models\TipoDocumento;
use Carbon\Carbon;
use Mail;
use App\Mail\ConfirmacaoMail;

class HomeController extends Controller
{
    public function index()
    {
        $editais = Edital::join('niveis', 'editais.codigoNivel', '=', 'niveis.codigoNivel')
                         ->where('dataFinalEdital', '>',  Carbon::now())->get();

        if (Auth::guest())
        {
            return view('index', 
            [
                'editais' => $editais,
                'utils'   => new Utils,        
            ]);
        }
        else
        {    
            if (empty(session('level')))
            {
                Utils::setSession(Auth::user()->id);
            }
            
            if (in_array('Servidorusp', session('vinculos')) && (session('level') == 'admin' || session('level') == 'manager'))
            {
                return redirect('admin');                
            }
            else
            {
                return redirect('dashboard');
            }
        }
    }

    public function modelo()
    {
        $tipos = TipoDocumento::all();

        return view('modelo',
        [
            'arquivo'         => new Arquivo,
            'codigoInscricao' => 1,
            'tipos'           => $tipos,  
        ]);
    }

    public function teste(Request $request)
    {
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

        request()->session()->flash('alert-success', 'Documento cadastrado com sucesso');    
        return redirect("/modelo");
    }

    function email()
    {
        Mail::to('dev.ci.eel@usp.br')->send(new ConfirmacaoMail(1));

        if (Mail::failures()) 
        {
            request()->session()->flash('alert-danger', 'Ocorreu um erro no envio do e-mail.');
        }    
        else
        {
            request()->session()->flash('alert-success', 'E-mail enviado com sucesso.');
        } 

        return redirect("/modelo");
    }
}
