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
use Uspdev\Replicado\Pessoa;
use Uspdev\Replicado\Posgraduacao;
use Uspdev\Replicado\Estrutura;
use Illuminate\Routing\UrlGenerator;
use Mail;
use App\Mail\PAE\EnviarPaeMail;
use Codedge\Fpdf\Fpdf\Fpdf as Fpdf;

class PaeController extends Controller
{
    public function index($codigoEdital)
    {
        if ((in_array("Alunopos", session('vinculos')) == false && in_array("Alunoposusp", session('vinculos')) == false) && (session('level') != 'admin'))
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
        if ((in_array("Alunopos", session('vinculos')) == false && in_array("Alunoposusp", session('vinculos')) == false) && (session('level') != 'admin'))
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

            $vinculo  = Posgraduacao::obterVinculoAtivo(Auth::user()->codpes);
            $programa = Posgraduacao::programas(NULL, NULL, $vinculo['codare']);
           
            $pae = Pae::create([
                'codigoInscricao'       => $inscricao->codigoInscricao,
                'codigoCurso'           => $programa[0]['codcur'],
                'codigoArea'            => $vinculo['codare'],
                'participacaoPae'       => $request->participacaoPae[0],
                'remuneracaoPae'        => $request->remuneracaoPae[0],
                'codigoPessoaAlteracao' => Auth::user()->codpes,
            ]);
    
            request()->session()->flash('alert-success', 'Dados cadastrado com sucesso. Continue com o preenchimento da inscrição.');    
            return redirect("inscricao/{$request->codigoEdital}/pae");
        }
    }

    public function finalizar($codigoEdital)
    {
        if ((in_array("Alunopos", session('vinculos')) == false && in_array("Alunoposusp", session('vinculos')) == false) && (session('level') != 'admin'))
        {
            return redirect("/");
        }

        $inscricao   = Inscricao::obterInscricaoPae(Auth::user()->id, $codigoEdital);
        $anosemestre = Edital::obterSemestreAno($codigoEdital);
        $vinculo     = Posgraduacao::obterVinculoAtivo(Auth::user()->codpes);
        $arquivos    = Arquivo::listarArquivosPae($inscricao->codigoPae);
        $total       = Arquivo::verificaArquivosPae($inscricao->codigoPae);
       
        return view('pae.finalizar',
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

    public function finalizar_store(Request $request)
    {
        $inscricao = Inscricao::obterInscricaoPae(Auth::user()->id, $request->codigoEdital);

        $temp = Inscricao::find($inscricao->codigoInscricao);
        
        $temp->statusInscricao       = 'C';
        $temp->codigoPessoaAlteracao = Auth::user()->codpes;
        $temp->save();

        Mail::to(Auth::user()->email)->cc('pae@eel.usp.br')->send(new EnviarPaeMail($request->codigoEdital));
        
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

    public function reenviar($codigoEdital)
    {
        $inscritos = Edital::join('inscricoes', 'editais.codigoEdital', '=', 'inscricoes.codigoEdital')
                           ->join('users', 'inscricoes.codigoUsuario', '=', 'users.id')
                           ->leftJoin('pae', 'inscricoes.codigoInscricao', '=', 'pae.codigoInscricao')
                           ->where('editais.codigoEdital', $codigoEdital)
                           ->where('inscricoes.statusInscricao','C')->get();
                                            
        foreach($inscritos as $inscricao)                           
        {
            $pdf = new Fpdf();

            $pdf->AddPage();
            $pdf->SetFillColor(190,190,190);
            $pdf->Image(asset('images/cabecalho/pae.png'), 10, 10, 190);
            $pdf->Ln(40);
    
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(190, 8, utf8_decode("COMPROVANTE DE INSCRIÇÃO Nº {$inscricao->numeroInscricao}"), 1, 0, 'C', true);
            $pdf->Ln();        
            $pdf->Cell(190, 2, '', 0,  0, 'C', false);
            $pdf->Ln();   
            $pdf->Output('F', public_path("pae/comprovante/{$inscricao->numeroInscricao}.pdf"));

            exit;

            Mail::to('pae@eel.usp.br')->send(new EnviarPaeMail($codigoEdital, $inscricao->id));

            if (Mail::failures()) 
            {
                echo 'Erro';
                exit;
            }
            else
            {
                echo "Inscrição {$inscricao->numeroInscricao} enviada com sucesso.<br/>";
            }
        }
    }
    
    public function comprovante($codigoEdital)
    {
        $inscricao   = Inscricao::obterInscricaoPae(Auth::user()->id, $codigoEdital);
        $anosemestre = Edital::obterSemestreAno($codigoEdital);
        $vinculo     = Posgraduacao::obterVinculoAtivo(Auth::user()->codpes);
        $arquivos    = Arquivo::listarArquivosPae($inscricao->codigoPae);
        
        $pdf = new Fpdf();

        $pdf->AddPage();
        $pdf->SetFillColor(190,190,190);
        $pdf->Image(asset('images/cabecalho/pae.png'), 10, 10, 190);
        $pdf->Ln(40);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(190, 8, utf8_decode("COMPROVANTE DE INSCRIÇÃO Nº {$inscricao->numeroInscricao} - {$anosemestre}"), 1, 0, 'C', true);
        $pdf->Ln();        
        $pdf->Cell(190, 2, '', 0,  0, 'C', false);
        $pdf->Ln();   
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(25, 8, utf8_decode("Número USP"), 'TLR',  0, 'C', false);
        $pdf->Cell(60, 8, utf8_decode("Nome"), 'TLR',  0, 'C', false);
        $pdf->Cell(50, 8, utf8_decode("Programa"), 'TLR',  0, 'C', false);
        $pdf->Cell(55, 8, utf8_decode("Já recebeu remuneração do PAE?"), 'TLR',  0, 'C', false);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Ln();  
        $pdf->Cell(25, 8, utf8_decode(Auth::user()->codpes), 1,  0, 'C', false);
        $pdf->Cell(60, 8, utf8_decode(Auth::user()->name), 1,  0, 'C', false);
        $pdf->Cell(50, 8, utf8_decode($vinculo['nomcur'].'-'.$vinculo['nivpgm']), 1,  0, 'C', false);
        $pdf->Cell(55, 8, utf8_decode(($inscricao->remuneracaoPae == "S") ? "Sim" : "Não"), 1,  0, 'C', false);        
        $pdf->Ln();        
        $pdf->Cell(190, 2, '', 0,  0, 'C', false);
        $pdf->Ln();   
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(190, 8, utf8_decode("DOCUMENTOS COMPROBATÓRIOS"), 1, 0, 'C', true);
        $pdf->Ln();        
        $pdf->Cell(190, 2, '', 0,  0, 'C', false);
        $pdf->Ln();   
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(160, 8, utf8_decode("Tipo de Documento"), 1,  0, 'C', false);
        $pdf->Cell(30, 8, utf8_decode("Quantidade"), 1, 0, 'C', false);
        $pdf->Ln(); 

        $pdf->SetFont('Arial', '', 9);

        foreach($arquivos as $arquivo)
        {
            $pdf->Cell(160, 8, utf8_decode($arquivo->tipoDocumento), 1, 0, 'L', false);
            $pdf->Cell(30, 8, utf8_decode($arquivo->total), 1, 0, 'C', false);
            $pdf->Ln();   
        }

        $pdf->SetFont('Arial','B',8);
        $pdf->SetY(-30);
        $pdf->Cell(95, 3, utf8_decode('ÁREA I'), 0, 0, 'L');
        $pdf->Cell(95, 3, utf8_decode('ÁREA II'), 0, 0, 'R');
        $pdf->SetFont('Times', '', 7);
        $pdf->Ln();
        $pdf->Cell(95, 3, 'Estrada Municipal  do Campinho, 100, Campinho - LORENA - S.P.', 0, 0, 'L');
        $pdf->Cell(95, 3, 'Estrada Municipal Chiquito de Aquino, 1000, Mondesir - LORENA - S.P.', 0, 0, 'R');
        $pdf->Ln();
        $pdf->Cell(95, 3, 'CEP 12602-810 - Tel. (012) 3159-5000', 0, 0, 'L');
        $pdf->Cell(95, 3, 'CEP 12612-550 - Tel. (012) 3159-9009', 0, 0, 'R');

        $pdf->Output();
    }

    public function visualizar($codigoEdital, $codigoUsuario)
    {
        if ((session('level') != 'manager'))
        {
            return redirect("/");
        }

        $inscricao   = Inscricao::obterInscricaoPae($codigoUsuario, $codigoEdital);
        $anosemestre = Edital::obterSemestreAno($codigoEdital);
        $vinculo     = Posgraduacao::obterVinculoAtivo($inscricao->codpes);
        $arquivos    = Arquivo::listarArquivosPae($inscricao->codigoPae);
        $total       = Arquivo::verificaArquivosPae($inscricao->codigoPae);
       
        return view('pae.visualizar',
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
