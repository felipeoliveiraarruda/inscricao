<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\Auth\UserUpdateRequest;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class AcessoController extends Controller
{
    public function create()
    {
        return view('auth.acesso');
    }

    public function index()
    {
        if (Auth::user()->cpf == '99999999999')
        {   
            return view('admin.dados');
        }
        else
        {
            return redirect('/');
        }
    }

    public function update(UserUpdateRequest $request)
    {        
        $validated = $request->validated();

        User::where('id', Auth::user()->id)->update([
            'cpf' => $request->cpf,
            'rg' => $request->rg,
            'telefone' => $request->telefone
        ]);        

        request()->session()->flash('alert-success','Dados Pessoais atualizado com sucesso');
        session()->forget('cpf');
        return redirect("/");
    }
}
