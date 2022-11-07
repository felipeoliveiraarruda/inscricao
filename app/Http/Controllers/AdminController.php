<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\Edital;
use App\Models\Utils;
use Mail;
use App\Mail\InscritosMail;

class AdminController extends Controller
{
    public function index()
    {
        if (Gate::check('admin'))
        {
            $editais = Edital::all();
        }
        else
        {
            $editais = Edital::where('codigoUsuario', Auth::user()->id)->get();
        }
        
        return view('admin.index',
        [
            'editais' => $editais,
            'utils'   => new Utils,                        
        ]);
    }

    public function listar(Request $request, $id)
    {  
        if(isset($request->search)) 
        {
            $inscritos = Edital::join('inscricoes', 'editais.codigoEdital', '=', 'inscricoes.codigoEdital')
                               ->join('users', 'inscricoes.codigoUsuario', '=', 'users.id')
                               ->where('editais.codigoEdital', $id)
                               ->where('users.name', 'LIKE', "%{$request->search}%")
                               ->orWhere('users.email', 'LIKE', "%{$request->search}%")
                               ->get();
        } 
        else 
        {
            $inscritos = Edital::join('inscricoes', 'editais.codigoEdital', '=', 'inscricoes.codigoEdital')
                               ->join('users', 'inscricoes.codigoUsuario', '=', 'users.id')
                               ->where('editais.codigoEdital', $id)->paginate(10);
        }

        $editais = Edital::where('codigoEdital', $id)->get();
        
        foreach($editais as $edital)
        {
            $curso = Utils::obterCurso($edital->codigoCurso);
        }
        
        return view('admin.listar',
        [
            'id'        => $id,
            'inscritos' => $inscritos,
            'curso'     => $curso['nomcur'],
        ]);
    }

    public function email($id)
    {
        return view('admin.email',
        [
            'id' => $id,            
        ]);  
    }

    public function enviar_email(Request $request)
    {
        if ($request->tipoDestinatario[0] == 'T')
        {
            $inscritos = Edital::join('inscricoes', 'editais.codigoEdital', '=', 'inscricoes.codigoEdital')
                                ->join('users', 'inscricoes.codigoUsuario', '=', 'users.id')
                                ->where('editais.codigoEdital', $request->codigoEdital)                                
                                ->get();
        }
        else 
        {
            $inscritos = Edital::join('inscricoes', 'editais.codigoEdital', '=', 'inscricoes.codigoEdital')
            ->join('users', 'inscricoes.codigoUsuario', '=', 'users.id')
            ->where('editais.codigoEdital', $request->codigoEdital)
            ->where('inscricoes.situacaoInscricao', $request->tipoDestinatario[0])
            ->get();
        }
        
        foreach($inscritos as $inscrito)
        {
            Mail::to(mb_strtolower($inscrito->email))->send(new InscritosMail($request->codigoEdital, $request->assunto, $request->body));

            if (Mail::failures()) 
            {
                request()->session()->flash('alert-danger', "Ocorreu um erro no envio do e-mail.");
                return redirect("admin/enviar-email/{$request->codigoEdital}");  
            }    
            else
            {    
                request()->session()->flash('alert-success', "E-mail enviado com sucesso.");
            }
        }
        
        return redirect("admin/enviar-email/{$request->codigoEdital}");
    }
}
