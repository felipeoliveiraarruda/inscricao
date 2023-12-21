<?php

namespace App\Http\Controllers;

use App\Models\Gcub;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use App\Models\Utils;
use App\Models\Pdf\Matricula;
use App\Models\TipoDocumento;
use App\Models\ArquivoGcub;
use Carbon\Carbon;
use Uspdev\Replicado\Posgraduacao;
use App\Http\Requests\GcubPostRequest;
use App\Http\Requests\GcubDocumentoPostRequest;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;

class GcubController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($tipo)
    {
        if (Auth::guest())
        {
            $logo = asset("images/cabecalho/presenca/logo_{$tipo}.jpg");
            $gcub = asset("images/gcub.png");

            if($tipo == 'ppgem')
            {
                $disciplinas = Utils::listarOferecimentoPos(97002, '04/03/2024', '16/06/2024');                
            }

            $paises = Utils::listarPais();            

            return view('gcub.index', 
            [
                'logo'         => $logo,
                'gcub'         => $gcub,
                'paises'       => $paises,
                'sexos'        => Utils::obterDadosSysUtils('sexo'),
                'disciplinas'  => $disciplinas,
                'tipo'         => $tipo,
                'desabilitado' => '',
            ]);
        }
    }

    public function store(GcubPostRequest $request)
    {
        $validated = $request->validated();        
       
        $passaporte = $request->passaporteAluno;

        unset($request['_token']);
        unset($request['cadastrar']);
        unset($request['passaporteAluno']);

        $gcub = Gcub::create([
            'passaporteAluno' => $passaporte,
            'dadosGcub'       => $request->collect()->toJson(),
        ]);

        request()->session()->flash('alert-success', 'Requerimento de Primeira Matrícula cadastrado com sucesso.');    
        
        return redirect("gcub/{$request->tipo}/{$gcub->codigoGcub}/show");
    }

    public function show($tipo, $codigoGcub)
    {
        $logo = asset("images/cabecalho/presenca/logo_{$tipo}.jpg");
        $gcub = asset("images/gcub.png");

        $temp  = Gcub::find($codigoGcub);
        $dados = json_decode($temp->dadosGcub);

        $dataNascimento = new Carbon($dados->dataNascimentoAluno);
        $nacionalidade  = Utils::obterPais($dados->nacionalidadeAluno);
        $paisTitulacao  = Utils::obterPais($dados->codigoPaisTitulacao);
        $dddPais        = Utils::obterPais($dados->codigoPaisAluno);

        $arquivos = ArquivoGcub::listarArquivos($codigoGcub);

        return view('gcub.show', 
        [
            'logo'           => $logo,
            'gcub'           => $gcub,
            'codigo'         => $codigoGcub,
            'dados'          => $dados,
            'passaporte'     => $temp->passaporteAluno,
            'dataNascimento' => $dataNascimento->format('d/m/Y'),
            'nacionalidade'  => $nacionalidade['nompas'],
            'paisTitulacao'  => $paisTitulacao['nompas'],
            'dddPais'        => $dddPais['codddi'],
            'total'          => count($arquivos),
            'totalNivel'     => ($dados->nivelPrograma == 'Mestrado' ? 4 : 6),
            'tipo'           => $tipo,
            'arquivos'       => $arquivos,
        ]);
    }

    public static function matricula(\App\Models\Pdf\Matricula $pdf, $codigoGcub)
    {
        $matricula = Gcub::matricula($pdf, $codigoGcub);

        return redirect(asset('storage/'.$matricula));
    }

    public function bolsista(\App\Models\Pdf\Bolsista $pdf, $codigoGcub)
    {
        Gcub::bolsista($pdf, $codigoGcub);
    }

    public function documento($tipo, $codigoGcub)
    {
        $logo = asset("images/cabecalho/presenca/logo_{$tipo}.jpg");
        $gcub = asset("images/gcub.png");

        $tipos = TipoDocumento::listarTipoDocumentosGcub();

        $temp  = Gcub::find($codigoGcub);
        $dados = json_decode($temp->dadosGcub);

        return view('gcub.documento',
        [
            'logo'         => $logo,
            'gcub'         => $gcub,
            'codigoGcub'   => $codigoGcub,
            'tipos'        => $tipos,  
            'nivel'        => $dados->nivelPrograma,
            'tipo'         => $tipo, 
        ]);
    }

    public function documento_store(GcubDocumentoPostRequest $request)    
    {
        $validated = $request->validated();

        foreach($request->file('arquivoGcub') as $key => $value)
        {
            $path = $value->store("gcub/{$request->codigoGcub}", 'public');

            $gcub = ArquivoGcub::create([
                'codigoGcub'            => $request->codigoGcub,
                'codigoTipoDocumento'   => $key,
                'linkArquivo'           => $path,
            ]);
        }

        request()->session()->flash('alert-success', 'Documento cadastrado com sucesso');    
        
        return redirect("gcub/{$request->tipo}/{$gcub->codigoGcub}/show");
    }
}