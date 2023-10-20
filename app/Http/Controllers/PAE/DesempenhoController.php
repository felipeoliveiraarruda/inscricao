<?php

namespace App\Http\Controllers\PAE;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use App\Models\Utils;
use App\Models\Inscricao;
use App\Models\Conceito;

class DesempenhoController extends Controller
{
    public function index($codigoEdital)
    {
        if ((session('level') != 'admin') || (session('level') != 'manager'))
        {
            return redirect("/");
        }

        $inscricao = Inscricao::obterInscricaoPae(Auth::user()->id, $codigoEdital);
        $conceitos = Conceito::where('statusConceito', '=', 'S')->get();

        return view('pae.desempenho.index',
        [
            'utils'        => new Utils,
            'conceitos'    => $conceitos,
            'codigoPae'    => $inscricao->codigoPae,
            'codigoEdital' => $codigoEdital,
            'editar'       => false,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
