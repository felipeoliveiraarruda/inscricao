<?php

namespace App\Http\Controllers\PAE;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Utils;
use App\Models\Arquivo;
use App\Models\Inscricao;
use App\Models\Edital;
use App\Models\TipoDocumento;
use App\Models\PAE\Conceito;
use App\Models\PAE\Pae;
use App\Models\PAE\AnaliseCurriculo;
use App\Models\PAE\Avaliacao;
use App\Models\PAE\TipoAnalise;
use App\Models\PAE\AvaliadorPae;
use Uspdev\Replicado\Pessoa;
use Uspdev\Replicado\Posgraduacao;

class AnaliseCurriculoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($codigoPae)
    {
        if ((session('level') != 'admin') && (session('level') != 'manager'))
        {
            return redirect("/");
        }

        $inscricao   = Pae::obterPae($codigoPae);
        $anosemestre = Edital::obterSemestreAno($inscricao->codigoEdital);
        $vinculo     = Posgraduacao::obterVinculoAtivo($inscricao->codpes);
        $total       = AnaliseCurriculo::obterTotalAnalise($codigoPae);
        $lattes      = Arquivo::listarArquivosPae($codigoPae, 9);
        $ficha       = Arquivo::listarArquivosPae($codigoPae, 22);
        $arquivos    = Arquivo::listarArquivosAnalisePae($codigoPae, true);

        return view('admin.pae.analise',
        [
            'utils'        => new Utils,
            'codigoPae'    => $codigoPae,
            'codigoEdital' => $inscricao->codigoEdital,
            'editar'       => ($total == 0 ? false : true),
            'inscricao'    => $inscricao,
            'vinculo'      => $vinculo,
            'arquivos'     => $arquivos,
            'ficha'        => $ficha[0],
            'lattes'       => $lattes[0],
            'nota'         => 0,
            'docente'      => (in_array("Docenteusp", session('vinculos'))),
            'pae'          => (Auth::user()->id == 4 ? true : false),
            'requerimento' => asset("storage/pae/comprovante/{$anosemestre}/{$inscricao->numeroInscricao}.pdf"),
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
        $pontuacao = 0;

        foreach($request->aceitarDocumento as $aceitarDocumento)
        {
            $temp = explode("|", $aceitarDocumento);
            $statusAnaliseCurriculo = $temp[0];
            $codigoArquivo          = $temp[1];

            if (isset($request->tipoDocumentoAnalise[$codigoArquivo]))
            {
                $arquivo = Arquivo::find($codigoArquivo);
                $arquivo->codigoTipoDocumento = $request->tipoDocumentoAnalise[$codigoArquivo];
                $arquivo->save();
            }
            
            $analise = AnaliseCurriculo::create([
                'codigoPae'                     => $request->codigoPae,
                'codigoArquivo'                 => $codigoArquivo,
                'pontuacaoAnaliseCurriculo'     => $request->pontuacaoAnalise[$codigoArquivo],
                'statusAnaliseCurriculo'        => $statusAnaliseCurriculo,
                'justificativaAnaliseCurriculo' => (isset($request->justificativaAnalise[$codigoArquivo]) ? $request->justificativaAnalise[$codigoArquivo] : NULL),
                'codigoPessoaAlteracao'         => Auth::user()->codpes,
            ]);
            
            $pontuacao = $pontuacao + $request->pontuacaoAnalise[$codigoArquivo];
        }        
        
        $tipo  = TipoAnalise::obterTipoAnaliseCodigoDocumento($request->codigoTipoDocumento);

        if ($tipo->maximoTipoAnalise > 0)
        {
            if ($pontuacao > $tipo->maximoTipoAnalise)
            {
                $total = (float)$tipo->maximoTipoAnalise * (float)$tipo->valorTipoAnalise;    
            }
            else
            {
                $total = (float)$pontuacao * (float)$tipo->valorTipoAnalise;    
            }
        }
        else
        {
            $total = (float)$pontuacao * (float)$tipo->valorTipoAnalise;
        }

        $codigoAvaliadorPae = AvaliadorPae::obterCodigoAvaliadorPae(Auth::user()->id, $request->codigoPae);

        $avaliacao = Avaliacao::create([
            'codigoAvaliadorPae'    => $codigoAvaliadorPae,
            'codigoTipoAnalise'     => $tipo->codigoTipoAnalise,
            'pontuacaoAvaliacao'    => $pontuacao,
            'totalAvaliacao'        => $total,
            'codigoPessoaAlteracao' => Auth::user()->codpes,
        ]);

        request()->session()->flash('alert-success', 'Análise cadastrada com sucesso.');    
        return redirect("admin/{$request->codigoPae}/pae/analise");
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

    public function analisar($codigoPae, $codigoTipoDocumento)
    {
        if ((session('level') != 'admin') && (session('level') != 'manager'))
        {
            return redirect("/");
        }

        $inscricao   = Pae::obterPae($codigoPae);
        $tipos       = TipoDocumento::listarTipoDocumentosAnalisePae();
        $anosemestre = Edital::obterSemestreAno($inscricao->codigoEdital);
        $vinculo     = Posgraduacao::obterVinculoAtivo($inscricao->codpes);
        $total       = AnaliseCurriculo::obterTotalAnalise($codigoPae);
        $lattes      = Arquivo::listarArquivosPae($codigoPae, 9);
        $ficha       = Arquivo::listarArquivosPae($codigoPae, 22);
        $arquivos    = Arquivo::listarArquivosPae($codigoPae, $codigoTipoDocumento);
        
        return view('admin.pae.analisar',
        [
            'utils'                 => new Utils,
            'tipos'                 => $tipos,
            'codigoPae'             => $codigoPae,
            'codigoEdital'          => $inscricao->codigoEdital,
            'codigoTipoDocumento'   => $codigoTipoDocumento,
            'editar'                => ($total == 0 ? false : true),
            'inscricao'             => $inscricao,
            'vinculo'               => $vinculo,
            'arquivos'              => $arquivos,
            'ficha'                 => $ficha[0],
            'lattes'                => $lattes[0],
        ]);
    }


    public function analisar_edit($codigoPae, $codigoTipoDocumento)
    {
        if ((session('level') != 'admin') && (session('level') != 'manager'))
        {
            return redirect("/");
        }

        $inscricao   = Pae::obterPae($codigoPae);        
        $tipos       = TipoDocumento::listarTipoDocumentosAnalisePae();
        $anosemestre = Edital::obterSemestreAno($inscricao->codigoEdital);        
        $vinculo     = Posgraduacao::obterVinculoAtivo($inscricao->codpes);
        $total       = AnaliseCurriculo::obterTotalAnalise($codigoPae);        
        $lattes      = Arquivo::listarArquivosPae($codigoPae, 9);
        $ficha       = Arquivo::listarArquivosPae($codigoPae, 22);
        $arquivos    = Arquivo::listarArquivosPae($codigoPae, $codigoTipoDocumento);
        
        return view('admin.pae.analisar_edit',
        [
            'utils'                 => new Utils,
            'tipos'                 => $tipos,
            'codigoPae'             => $codigoPae,
            'codigoEdital'          => $inscricao->codigoEdital,
            'codigoTipoDocumento'   => $codigoTipoDocumento,
            'editar'                => ($total == 0 ? false : true),
            'inscricao'             => $inscricao,
            'vinculo'               => $vinculo,
            'arquivos'              => $arquivos,
            'ficha'                 => $ficha[0],
            'lattes'                => $lattes[0],
        ]);
    }

    public function analisar_update(Request $request)
    {
        $pontuacao = 0;

        foreach($request->codigoAnaliseCurriculo as $codigoAnaliseCurriculo)
        {
            $temp = explode("|", $request->aceitarDocumento[$codigoAnaliseCurriculo]);
            $statusAnaliseCurriculo = $temp[0];
            $codigoArquivo          = $temp[1];

            if (isset($request->tipoDocumentoAnalise[$codigoAnaliseCurriculo]))
            {
                $arquivo = Arquivo::find($codigoArquivo);
                $arquivo->codigoTipoDocumento = $request->tipoDocumentoAnalise[$codigoAnaliseCurriculo];
                $arquivo->save();
            }

            $analise =  AnaliseCurriculo::find($codigoAnaliseCurriculo);
            $analise->pontuacaoAnaliseCurriculo     = $request->pontuacaoAnalise[$codigoAnaliseCurriculo];
            $analise->statusAnaliseCurriculo        = $statusAnaliseCurriculo;
            $analise->justificativaAnaliseCurriculo = (isset($request->justificativaAnalise[$codigoAnaliseCurriculo]) ? $request->justificativaAnalise[$codigoAnaliseCurriculo] : NULL);            
            $analise->codigoPessoaAlteracao         = Auth::user()->codpes;
            
            $pontuacao = $pontuacao + $request->pontuacaoAnalise[$codigoAnaliseCurriculo];
        }        
        
        $tipo  = TipoAnalise::obterTipoAnaliseCodigoDocumento($request->codigoTipoDocumento);

        if ($tipo->maximoTipoAnalise > 0)
        {
            if ($pontuacao > $tipo->maximoTipoAnalise)
            {
                $total = (float)$tipo->maximoTipoAnalise * (float)$tipo->valorTipoAnalise;    
            }
            else
            {
                $total = (float)$pontuacao * (float)$tipo->valorTipoAnalise;    
            }
        }
        else
        {
            $total = (float)$pontuacao * (float)$tipo->valorTipoAnalise;
        }

        $temp = Avaliacao::obterAvaliacao($request->codigoPae, $request->codigoTipoDocumento);
        
        $avaliacao = Avaliacao::find($temp->codigoAvaliacao);
        $avaliacao->pontuacaoAvaliacao    = $pontuacao;
        $avaliacao->totalAvaliacao        = $total;
        $avaliacao->codigoPessoaAlteracao = Auth::user()->codpes;
        $avaliacao->save();

        request()->session()->flash('alert-success', 'Análise atualizada com sucesso.');    
        return redirect("admin/{$request->codigoPae}/pae/analise");
    }

    public function visualizar($codigoPae, $codigoTipoDocumento)
    {
        if ((session('level') != 'admin') && (session('level') != 'manager'))
        {
            return redirect("/");
        }

        $inscricao   = Pae::obterPae($codigoPae);
        $tipos       = TipoDocumento::listarTipoDocumentosAnalisePae();
        $anosemestre = Edital::obterSemestreAno($inscricao->codigoEdital);
        $vinculo     = Posgraduacao::obterVinculoAtivo($inscricao->codpes);
        $total       = AnaliseCurriculo::obterTotalAnalise($codigoPae);
        $lattes      = Arquivo::listarArquivosPae($codigoPae, 9);
        $ficha       = Arquivo::listarArquivosPae($codigoPae, 22);
        $arquivos    = Arquivo::listarArquivosPae($codigoPae, $codigoTipoDocumento);
        
        return view('admin.pae.visualizar',
        [
            'utils'                 => new Utils,
            'tipos'                 => $tipos,
            'codigoPae'             => $codigoPae,
            'codigoEdital'          => $inscricao->codigoEdital,
            'codigoTipoDocumento'   => $codigoTipoDocumento,
            'editar'                => ($total == 0 ? false : true),
            'inscricao'             => $inscricao,
            'vinculo'               => $vinculo,
            'arquivos'              => $arquivos,
            'ficha'                 => $ficha[0],
            'lattes'                => $lattes[0],
        ]);
    }
}