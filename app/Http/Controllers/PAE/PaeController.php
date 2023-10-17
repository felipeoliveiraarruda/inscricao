<?php

namespace App\Http\Controllers\PAE;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\PAE\Pae;
use App\Models\PAE\Conceito;
use App\Models\PAE\TipoAnalise;
use App\Models\PAE\DesempenhoAcademico;
use App\Models\PAE\AnaliseCurriculo;
use App\Models\PAE\AnaliseCurriculoArquivo;
use App\Models\Utils;
use App\Models\Edital;
use App\Models\Inscricao;
use App\Models\InscricoesArquivos;
use App\Models\Arquivo;
use App\Models\TipoDocumento;
use Uspdev\Replicado\Posgraduacao;
use Illuminate\Routing\UrlGenerator;
use Mail;
use App\Mail\PAE\EnviarPaeMail;

class PaeController extends Controller
{
    public function index($codigoEdital)
    {
        if (!in_array('Alunoposusp', session('vinculos')) && (session('level') != 'admin'))
        {
            return redirect("/");
        }

        $inscricao   = Inscricao::obterInscricaoPae(Auth::user()->id, $codigoEdital);
        $anosemestre = Edital::obterSemestreAno($codigoEdital);
        $vinculo     = Posgraduacao::obterVinculoAtivo(Auth::user()->codpes);
        $arquivos    = Arquivo::listarArquivosPae($inscricao->codigoPae);
        $total       = Arquivo::verificaArquivosPae($inscricao->codigoPae);
       
        return view('pae.index',
        [
            'utils'        => new Utils,
            'inscricao'    => $inscricao,
            'anosemestre'  => $anosemestre,
            'user'         => Auth::user(),
            'vinculo'      => $vinculo,
            'codigoEdital' => $codigoEdital,            
            'arquivos'     => $arquivos,
            'total'        => $total,
        ]);
    }

    public function create($codigoEdital)
    {
        if (!in_array('Alunoposusp', session('vinculos')) && (session('level') != 'admin'))
        {
            return redirect("/");
        }

        $anosemestre = Edital::obterSemestreAno($codigoEdital);

        return view('pae.create',
        [
            'utils'        => new Utils,
            'user_id'      => Auth::user()->id,
            'anosemestre'  => $anosemestre,
            'codigoEdital' => $codigoEdital
        ]);
    }

    public function store(Request $request)
    {
        $inscricao = Inscricao::verificarInscricao($request->codigoEdital, Auth::user()->id);

        if (empty($inscricao))
        {            
            $numero = Inscricao::gerarNumeroInscricao($request->codigoEdital);
            $nivel  = Edital::obterNivelEdital($request->codigoEdital);

            $inscricao = Inscricao::create([
                'codigoEdital'          => $request->codigoEdital,
                'codigoUsuario'         => Auth::user()->id,
                'numeroInscricao'       => "{$nivel}{$numero}",
                'codigoPessoaAlteracao' => Auth::user()->codpes,
            ]); 

            $pae = Pae::create([
                'codigoInscricao'       => $inscricao->codigoInscricao,
                'participacaoPae'       => $request->participacaoPae[0],
                'remuneracaoPae'        => $request->remuneracaoPae[0],
                'codigoPessoaAlteracao' => Auth::user()->codpes,
            ]);
    
            request()->session()->flash('alert-success', 'Dados cadastrado com sucesso. Continue com o preenchimento da inscrição.');    
            return redirect("inscricao/{$request->codigoEdital}/pae");
        }
    }

    public function finalizar(Request $request)
    {
        $inscricao = Inscricao::obterInscricaoPae(Auth::user()->id, $request->codigoEdital);

        $temp = Inscricao::find($inscricao->codigoInscricao);
        
        $temp->statusInscricao       = 'C';
        $temp->codigoPessoaAlteracao = Auth::user()->codpes;
        $temp->save();

        Mail::to(Auth::user()->email)->send(new EnviarPaeMail($request->codigoEdital));
        
        if (Mail::failures()) 
        {
            request()->session()->flash('alert-danger', "Ocorreu um erro no envio da inscrição {$inscricao->numeroInscricao}.");
        }    
        else
        {
            request()->session()->flash('alert-success', "Inscrição {$inscricao->numeroInscricao} enviada com sucesso.");
        } 

        return redirect("dashboard");
    }

   /* public function desempenho($codigoEdital)
    {
        if (!in_array('Alunoposusp', session('vinculos')) && (session('level') != 'admin'))
        {
            return redirect("/");
        }

        $inscricao = Inscricao::obterInscricaoPae(Auth::user()->id, $codigoEdital);
        $conceitos = Conceito::where('statusConceito', '=', 'S')->get();

        return view('pae.desempenho',
        [
            'utils'        => new Utils,
            'conceitos'    => $conceitos,
            'codigoPae'    => $inscricao->codigoPae,
            'codigoEdital' => $codigoEdital,
            'editar'       => false,
        ]);
    }

    public function desempenho_store(Request $request)
    {
        foreach($request->codigoConceito as $conceito)
        {
            if ($request->quantidadeDesempenhoAcademico[$conceito] != "")
            {
                $desempenho = DesempenhoAcademico::create([
                    'codigoPae'                     => $request->codigoPae,
                    'codigoConceito'                => $conceito,
                    'quantidadeDesempenhoAcademico' => $request->quantidadeDesempenhoAcademico[$conceito],
                    'codigoPessoaAlteracao'         => Auth::user()->codpes,
                ]); 
            }
        }

        request()->session()->flash('alert-success', 'Desempenho Acadêmico cadastrado com sucesso.');    
        return redirect("inscricao/{$request->codigoEdital}/pae");
    }

    public function desempenho_edit($codigoEdital)
    {
        if (!in_array('Alunoposusp', session('vinculos')) && (session('level') != 'admin'))
        {
            return redirect("/");
        }

        $inscricao = Inscricao::obterInscricaoPae(Auth::user()->id, $codigoEdital);
        $conceitos = DesempenhoAcademico::listarDesempenho(Auth::user()->id, $codigoEdital);
        
        return view('pae.desempenho',
        [
            'utils'        => new Utils,
            'conceitos'    => $conceitos,
            'codigoPae'    => $inscricao->codigoPae,
            'codigoEdital' => $codigoEdital,
            'editar'       => true,
        ]);
    }

    public function desempenho_update(Request $request)
    {
        foreach($request->codigoDesempenhoAcademico as $temp)
        {
            $desempenho = DesempenhoAcademico::find($temp);
            $desempenho->quantidadeDesempenhoAcademico = $request->quantidadeDesempenhoAcademico[$temp];
            $desempenho->save();
        }

        request()->session()->flash('alert-success', 'Desempenho Acadêmico atualizado com sucesso.');    
        return redirect("inscricao/{$request->codigoEdital}/pae");
    }

    public function desempenho_destroy($codigoDesempenhoAcademico)
    {
        DesempenhoAcademico::destroy($codigoDesempenhoAcademico);

        request()->session()->flash('alert-success', 'Desempenho Acadêmico excluído com sucesso.');    
        return redirect(url()->previous());
    }

    public function analise($codigoEdital)
    {
        if (!in_array('Alunoposusp', session('vinculos')) && (session('level') != 'admin'))
        {
            return redirect("/");
        }

        $inscricao = Inscricao::obterInscricaoPae(Auth::user()->id, $codigoEdital);
        $tipos     = TipoAnalise::where('statusTipoAnalise', '=', 'S')->get();

        return view('pae.analise',
        [
            'utils'        => new Utils,
            'tipos'        => $tipos,
            'codigoPae'    => $inscricao->codigoPae,
            'codigoEdital' => $codigoEdital,
            'editar'       => false,
        ]);
    }

    public function analise_store(Request $request)
    {
        foreach($request->codigoTipoAnalise as $tipo)
        {
            if ($request->pontuacaoAnaliseCurriculo[$tipo] != "")
            {
                $tipoAnalise = AnaliseCurriculo::create([
                    'codigoPae'                 => $request->codigoPae,
                    'codigoTipoAnalise'         => $tipo,
                    'pontuacaoAnaliseCurriculo' => $request->pontuacaoAnaliseCurriculo[$tipo],
                    'codigoPessoaAlteracao'     => Auth::user()->codpes,
                ]); 
            }
        }

        request()->session()->flash('alert-success', 'Análise do Currículo Lattes cadastrado com sucesso.');    
        return redirect("inscricao/{$request->codigoEdital}/pae");
    }

    public function analise_edit($codigoEdital)
    {
        if (!in_array('Alunoposusp', session('vinculos')) && (session('level') != 'admin'))
        {
            return redirect("/");
        }

        $inscricao = Inscricao::obterInscricaoPae(Auth::user()->id, $codigoEdital);
        $tipos     = AnaliseCurriculo::listarAnalise(Auth::user()->id, $codigoEdital);
        
        return view('pae.analise',
        [
            'utils'        => new Utils,
            'tipos'        => $tipos,
            'codigoPae'    => $inscricao->codigoPae,
            'codigoEdital' => $codigoEdital,
            'editar'       => true,
        ]);
    }

    public function analise_update(Request $request)
    {
        foreach($request->codigoAnaliseCurriculo as $temp)
        {
            $analise = AnaliseCurriculo::find($temp);
            $analise->pontuacaoAnaliseCurriculo = $request->pontuacaoAnaliseCurriculo[$temp];
            $analise->save();
        }

        request()->session()->flash('alert-success', 'Análise do Currículo Lattes atualizado com sucesso.');    
        return redirect("inscricao/{$request->codigoEdital}/pae");
    }

    public function analise_destroy($codigoAnaliseCurriculo)
    {
        AnaliseCurriculo::destroy($codigoAnaliseCurriculo);

        request()->session()->flash('alert-success', 'Análise do Currículo excluído com sucesso.');    
        return redirect(url()->previous());
    }

    public function documentacao_create($codigoEdital)
    {
        if (!in_array('Alunoposusp', session('vinculos')) && (session('level') != 'admin'))
        {
            return redirect("/");
        }

        $inscricao = Inscricao::obterInscricaoPae(Auth::user()->id, $codigoEdital);
        $tipos     = TipoDocumento::listarTipoDocumentos($codigoEdital);
        
        return view('pae.documentacao.create',
        [
            'utils'           => new Utils,
            'codigoPae'       => $inscricao->codigoPae,
            'codigoEdital'    => $codigoEdital,
            'codigoInscricao' => $inscricao->codigoInscricao,
            'tipos'           => $tipos,
        ]);
    }
    
    public function documentacao_store(Request $request)
    {
        foreach($request->file('arquivo') as $arquivo)
        {
            $path = $arquivo->store('pae', 'public');

            $temp = Arquivo::create([
                'codigoUsuario'         => Auth::user()->id,
                'codigoTipoDocumento'   => $request->codigoTipoDocumento,
                'linkArquivo'           => $path,
                'codigoPessoaAlteracao' => Auth::user()->codpes,
            ]);
    
            $inscricaoDocumentos = InscricoesArquivos::create([
                'codigoInscricao'       => $request->codigoInscricao,
                'codigoArquivo'         => $temp->codigoArquivo,
                'codigoPessoaAlteracao' => Auth::user()->codpes,
            ]);
        }

        request()->session()->flash('alert-success', 'Documentação cadastrada com sucesso.');    
        return redirect("inscricao/{$request->codigoEdital}/pae");
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
