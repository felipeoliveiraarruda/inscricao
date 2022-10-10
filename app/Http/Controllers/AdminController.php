<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\Edital;
use App\Models\Utils;

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
                               ->whereOr('users.email', 'LIKE', "%{$request->search}%")
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
}
