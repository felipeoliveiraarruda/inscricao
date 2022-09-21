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
        if (session()->exists('cpf'))
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
        $user = User::find(Auth::user()->id);
        $validated = $request->validated();
        $user->update($validated);
        request()->session()->flash('alert-success','Dados Pessoais atualizado com sucesso');
        session()->forget('cpf');
        return redirect("/");
    }
}
