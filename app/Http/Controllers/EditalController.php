<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EditalRequest;
use App\Models\Edital;
use App\Models\Utils;
use Illuminate\Support\Facades\Auth;

class EditalController extends Controller
{
    public function index()
    {
        $editais = Edital::all();
        
        return view('admin.edital.index',
        [
            'editais' => $editais,
            'utils'   => new Utils
        ]);
    }

    public function create()    
    {
        $cursos = Utils::listarCurso();
        
        return view('admin.edital.create',
        [
            'cursos' => $cursos
        ]);
    }

    public function store(EditalRequest $request)
    {
        $validated = $request->validated();

        Edital::create([
            'codigoCurso'           => $request->codigoCurso,
            'nivelEdital'           => $request->nivelEdital,
            'linkEdital'            => $request->linkEdital,
            'dataInicioEdital'      => $request->dataInicioEdital,
            'dataFinalEdital'       => $request->dataFinalEdital,
            'codigoPessoaAlteracao' => Auth::user()->codpes,
        ]);        

        request()->session()->flash('alert-success','Edital criado com sucesso');
        return redirect("/admin/edital");
    }

    public function update(EditalRequest $request, Edital $edital)
    {
        //
    }
}
