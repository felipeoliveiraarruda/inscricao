<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\EditalRequest;
use App\Models\Edital;
use App\Models\EditalTipoDocumento;
use App\Models\Nivel;
use App\Models\TipoDocumento;
use App\Models\Utils;

class EditalController extends Controller
{
    public function index()
    {                
        /*if ((session('level') != "admin") || (session('level') != "manager"))
        {
            return redirect("/");
        }*/

        if (session('level') == "admin")
        {
            $editais = Edital::join('niveis', 'editais.codigoNivel', '=', 'niveis.codigoNivel')->get();
            
        }
        
        if (session('level') == "manager")
        {
            $editais = Edital::join('niveis', 'editais.codigoNivel', '=', 'niveis.codigoNivel')
                             ->where('codigoUsuario', '=',  Auth::user()->id)->get();
        }
        
        return view('admin.edital.index',
        [
            'editais' => $editais,
            'utils'   => new Utils
        ]);
    }

    public function create()    
    {
        /*if ((session('level') != 'admin' || session('level') != 'manager'))
        {
            return redirect("/");
        }*/

        $cursos = Utils::listarCurso();
        $niveis = Nivel::all();
        $tipos  = TipoDocumento::all();
        
        return view('admin.edital.create',
        [
            'cursos' => $cursos,
            'niveis' => $niveis,
            'tipos'  => $tipos
        ]);
    }

    public function store(EditalRequest $request)
    {    
        $validated = $request->validated();

        $edital = Edital::create([
            'codigoCurso'           => $request->codigoCurso,
            'codigoUsuario'         => Auth::user()->id,
            'codigoNivel'           => $request->codigoNivel,
            'linkEdital'            => $request->linkEdital,
            'dataInicioEdital'      => $request->dataInicioEdital,
            'dataFinalEdital'       => $request->dataFinalEdital,
            'dataDoeEdital'         => $request->dataDoeEdital,
            'codigoPessoaAlteracao' => Auth::user()->codpes,
        ]);

        foreach($request->codigoTipoDocumento as $tipo)
        {
            EditalTipoDocumento::create([
                'codigoEdital'          => $edital->codigoEdital,
                'codigoTipoDocumento'   => $tipo,
                'codigoPessoaAlteracao' => Auth::user()->codpes,
            ]);
        }

        request()->session()->flash('alert-success','Edital criado com sucesso');
        return redirect("/admin/edital");
    }

    public function update(EditalRequest $request, Edital $edital)
    {
        //
    }
}
