<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Illuminate\Support\Facades\Password;
use App\Models\Edital;
use App\Models\Nivel;
use App\Models\TipoDocumento;
use App\Models\Utils;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('admin'))
        {
            $item = array();

            $item['title'] = 'AVISO';
            $item['story'] = 'Você não tem permissão para acessar essa página';

            return view('components.modal', compact('item'));
        }

        $usuarios = User::all();
        
        return view('admin.user.index',
        [
            'usuarios' => $usuarios
        ]);
    }

    public function recuperacao($id)
    {
        $usuarios = User::find($id);

        $status = Password::sendResetLink(
            $usuarios->only('email')
        );
       
        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($usuarios->only('email'))
                            ->withErrors(['email' => __($status)]);
    }

    public function create()    
    {
        $cursos = Utils::listarCurso();
        $niveis = Nivel::all();
        $tipos  = TipoDocumento::all();
        
        return view('admin.user.create',
        [
            'cursos' => $cursos,
            'niveis' => $niveis,
            'tipos'  => $tipos
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
