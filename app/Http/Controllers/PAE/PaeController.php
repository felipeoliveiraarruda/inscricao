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
use App\Models\PAE\Avaliacao;
use App\Models\PAE\RecursoPae;
use App\Models\Utils;
use App\Models\Edital;
use App\Models\Inscricao;
use App\Models\InscricoesArquivos;
use App\Models\Arquivo;
use App\Models\User;
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
        if ((in_array("Alunopos", session('vinculos')) == false && in_array("Alunoposusp", session('vinculos')) == false) && (session('level') != 'manager') && (session('level') != 'admin') )
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
        if ((in_array("Alunopos", session('vinculos')) == false && in_array("Alunoposusp", session('vinculos')) == false) && (session('level') != 'manager') && (session('level') != 'admin') )
        {
            return redirect("/");
        }

        $anosemestre = Edital::obterSemestreAno($codigoEdital);
        $ultimo      = Pae::obterUltimoPae(Auth::user()->id, $codigoEdital);

        return view('pae.create',
        [
            'utils'        => new Utils,
            'user_id'      => Auth::user()->id,
            'anosemestre'  => $anosemestre,
            'codigoEdital' => $codigoEdital,
            'ultimo'       => $ultimo,
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

            if($request->importarPae[0] == 'S')
            {
                $excluir = array(22, 26);

                $arquivos = Arquivo::listarArquivosPae($request->codigoPae);
                
                foreach($arquivos as $arquivo)
                {
                    if (!in_array($arquivo->codigoTipoDocumento, $excluir))
                    {
                        $InscricoesArquivos = InscricoesArquivos::create([
                            'codigoInscricao'       => $inscricao->codigoInscricao,
                            'codigoArquivo'         => $arquivo->codigoArquivo,
                            'codigoPessoaAlteracao' => Auth::user()->codpes,
                        ]);
                    }
                }
            }

            request()->session()->flash('alert-success', 'Dados cadastrado com sucesso. Continue com o preenchimento da inscrição.');    
            return redirect("inscricao/{$request->codigoEdital}/pae");
        }
    }

    public function finalizar($codigoEdital)
    {
        if ((in_array("Alunopos", session('vinculos')) == false && in_array("Alunoposusp", session('vinculos')) == false) && (session('level') != 'manager') && (session('level') != 'admin') )
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
                                            
        $user = User::find($inscricao->id);
        $anosemestre = Edital::obterSemestreAno($inscricao->codigoEdital);
        $vinculo     = Posgraduacao::obterVinculoAtivo($user->codpes);
        $arquivos    = Arquivo::listarArquivosPae($inscricao->codigoPae);
        $total       = Arquivo::verificaArquivosPae($inscricao->codigoPae);

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
        $pdf->Cell(25, 8, utf8_decode($user->codpes), 1,  0, 'C', false);
        $pdf->Cell(60, 8, utf8_decode($user->name), 1,  0, 'C', false);
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

        $pdf->Output('F', storage_path("app/public/pae/comprovante/{$anosemestre}/{$inscricao->numeroInscricao}.pdf"));

        $temp = Inscricao::find($inscricao->codigoInscricao);
        
        $temp->statusInscricao       = 'C';
        $temp->codigoPessoaAlteracao = Auth::user()->codpes;
        $temp->save();

        Mail::to(Auth::user()->email)->cc('pae@eel.usp.br')->send(new EnviarPaeMail($request->codigoEdital, Auth::user()->id));
        
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

    /*public function reenviar($codigoEdital)
    {
        $inscritos = Edital::join('inscricoes', 'editais.codigoEdital', '=', 'inscricoes.codigoEdital')
                           ->join('users', 'inscricoes.codigoUsuario', '=', 'users.id')
                           ->leftJoin('pae', 'inscricoes.codigoInscricao', '=', 'pae.codigoInscricao')
                           ->where('editais.codigoEdital', $codigoEdital)
                           ->where('inscricoes.statusInscricao','C')->get();

        $anosemestre = Edital::obterSemestreAno($inscritos[0]->codigoEdital);
                                            
        foreach($inscritos as $inscricao)                           
        {
            $user = User::find($inscricao->id);
            $anosemestre = Edital::obterSemestreAno($inscricao->codigoEdital);
            $vinculo     = Posgraduacao::obterVinculoAtivo($user->codpes);
            $arquivos    = Arquivo::listarArquivosPae($inscricao->codigoPae);
            $total       = Arquivo::verificaArquivosPae($inscricao->codigoPae);

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
            $pdf->Cell(25, 8, utf8_decode($user->codpes), 1,  0, 'C', false);
            $pdf->Cell(60, 8, utf8_decode($user->name), 1,  0, 'C', false);
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

            $pdf->Output('F', storage_path("app/public/pae/comprovante/{$anosemestre}/{$inscricao->numeroInscricao}.pdf"));

            Mail::to($user->email)->cc('pae@eel.usp.br')->send(new EnviarPaeMail($codigoEdital, $inscricao->id));

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
    }*/
    
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
        if ((session('level') != 'manager') && (session('level') != 'admin'))
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

    public function classificacao($codigoEdital)
    {
        if ((session('level') != 'manager') && (session('level') != 'admin'))
        {
            return redirect("/");
        }

        $classificacao = Pae::obterClassificacao($codigoEdital, '', true);
        $anosemestre   = Edital::obterSemestreAno($codigoEdital);

        $classificacao = 0;

        if ($classificacao == 0)
        {
            $i = 1;

            $semRemuneracao = array();
            $comRemuneracao = array();

            $totalConceito = Conceito::obterTotalConceito();
            $notaConceito  = Conceito::obterNotaConceito();

            $conceitoIC      = AnaliseCurriculo::obterConceitoIC();
            $conceitoEstagio = AnaliseCurriculo::obterConceitoEstagio();

            $notaEstagio     = AnaliseCurriculo::obterNotaPublicacao();
            $notaPublicacao  = AnaliseCurriculo::obterNotaICEstagio();

            /*===========LANÇAR A NOTA DOS INSCRITOS QUE NUNCA RECEBRERM BOLSA PAE==============*/
            $inscritos = Pae::join('inscricoes', 'pae.codigoInscricao', '=', 'inscricoes.codigoInscricao')
                            ->join('users', 'users.id', '=', 'inscricoes.codigoUsuario')
                            ->where('inscricoes.codigoEdital', $codigoEdital)
                            ->where('inscricoes.statusInscricao', 'C')
                            ->where('pae.remuneracaoPae', 'N')->get();

            foreach($inscritos as $inscrito)
            {
               // if ($inscrito->notaFinalPae == 0.00)
                //{
                    $totalDesempenho      = DesempenhoAcademico::obterSomaTotalDesempenho($inscrito->codigoPae);
                    $quantidadeDesempenho = DesempenhoAcademico::obterSomaQuantidadeDesempenho($inscrito->codigoPae);
                    
                    if ($quantidadeDesempenho == 0)
                    {
                        $notaDesempenho = 0;
                    }
                    else
                    {
                        $notaDesempenho       = $totalDesempenho / $quantidadeDesempenho;
                    }

                    $finalDesempenho      = $notaDesempenho * $notaConceito;

                    $ic          = Avaliacao::obterSomaAvaliacao($inscrito->codigoPae, [24]);
                    $estagio     = Avaliacao::obterSomaAvaliacao($inscrito->codigoPae, [25]);
                    $publicacoes = Avaliacao::obterSomaAvaliacao($inscrito->codigoPae, [12,13,14,15,16,17,18,19,20]);

                    $finalEstagio = ($ic + $estagio) * $notaEstagio;

                    if ($publicacoes > 10)
                    {        
                        $finalPublicacao = 10 * $notaPublicacao;
                    }
                    else
                    {
                        $finalPublicacao = $publicacoes * $notaPublicacao;
                    }

                    $notaFinal = $finalDesempenho + $finalEstagio + $finalPublicacao;

                    $pae  = Pae::find($inscrito->codigoPae);
                    $pae->notaFinalPae = $notaFinal;
                    $pae->save();

                    $semRemuneracao["{$inscrito->codigoPae}"] = $notaFinal;
                //}
            }

            /*===========FAZ A ORDENAÇÃO E A CLASSIFICAÇÃO DOS QUE NUNCA RECEBRERM BOLSA PAE==============*/
            if (count($semRemuneracao) > 0) 
            {
                arsort($semRemuneracao);

                foreach($semRemuneracao as $key => $value)
                {
                    $pae  = Pae::find($key);
                    $pae->classificacaoPae = $i;
                    $pae->save();
                    $i++;
                }
            }
            
            /*===========LANÇAR A NOTA DOS INSCRITOS QUE RECEBRERM BOLSA PAE==============*/
            $inscritos = Pae::join('inscricoes', 'pae.codigoInscricao', '=', 'inscricoes.codigoInscricao')
                            ->join('users', 'users.id', '=', 'inscricoes.codigoUsuario')
                            ->where('inscricoes.codigoEdital', $codigoEdital)
                            ->where('inscricoes.statusInscricao', 'C')
                            ->where('pae.remuneracaoPae', 'S')->get();

            foreach($inscritos as $inscrito)
            {
                //if ($inscrito->notaFinalPae == 0.00)
                //{
                    $totalDesempenho      = DesempenhoAcademico::obterSomaTotalDesempenho($inscrito->codigoPae);
                    $quantidadeDesempenho = DesempenhoAcademico::obterSomaQuantidadeDesempenho($inscrito->codigoPae);

                    if ($quantidadeDesempenho == 0)
                    {
                        $notaDesempenho = 0;
                    }
                    else
                    {
                        $notaDesempenho       = $totalDesempenho / $quantidadeDesempenho;
                    }

                    $finalDesempenho      = $notaDesempenho * $notaConceito;

                    $ic          = Avaliacao::obterSomaAvaliacao($inscrito->codigoPae, [24]);
                    $estagio     = Avaliacao::obterSomaAvaliacao($inscrito->codigoPae, [25]);
                    $publicacoes = Avaliacao::obterSomaAvaliacao($inscrito->codigoPae, [12,13,14,15,16,17,18,19,20]);

                    $finalEstagio = ($ic + $estagio) * $notaEstagio;

                    if ($publicacoes > 10)
                    {        
                        $finalPublicacao = 10 * $notaPublicacao;
                    }
                    else
                    {
                        $finalPublicacao = $publicacoes * $notaPublicacao;
                    }

                    $notaFinal = $finalDesempenho + $finalEstagio + $finalPublicacao;

                    $pae  = Pae::find($inscrito->codigoPae);
                    $pae->notaFinalPae = $notaFinal;
                    $pae->save();

                    $comRemuneracao["{$inscrito->codigoPae}"] = $notaFinal;
                //}
            }

            /*===========FAZ A ORDENAÇÃO E A CLASSIFICAÇÃO DOS QUE NUNCA RECEBRERM BOLSA PAE==============*/
            if (count($comRemuneracao) > 0)
            {
                arsort($comRemuneracao);

                foreach($comRemuneracao as $key => $value)
                {
                    $pae  = Pae::find($key);
                    $pae->classificacaoPae = $i;
                    $pae->save();
                    $i++;
                }
            }
        
            /*===========DESEMPATE==============*/
            /*$empates = Pae::select('notaFinalPae')
                        ->groupBy('notaFinalPae')
                        ->having(\DB::raw('COUNT(codigoPae)'), '>', 1)
                        ->orderBy('notaFinalPae', 'desc')
                        ->get();*/

            /*===========DESEMPATE POR DATA DE INICIO NO PROGRAMA==============*/
            /*foreach($empates as $empate)
            {
                $temps = array();

                $inscritos = Pae::select(\DB::raw('`pae`.codigoPae, `pae`.classificacaoPae, `users`.codpes'))
                                ->join('inscricoes', 'pae.codigoInscricao', '=', 'inscricoes.codigoInscricao')
                                ->join('users', 'users.id', '=', 'inscricoes.codigoUsuario')
                                ->where('pae.notaFinalPae', $empate->notaFinalPae)
                                ->orderBy('pae.classificacaoPae')
                                ->get();

                $inicio = $inscritos[0]->classificacaoPae;
                $final  = $inscritos[count($inscritos)-1]->classificacaoPae; 
                $iguais = array();

                foreach($inscritos as $inscrito)
                {
                    $vinculo = Posgraduacao::obterVinculoAtivo($inscrito->codpes);
                    $temps[$inscrito->codigoPae] = $vinculo["dtainivin"];

                    //$empate = $vinculo["dtainivin"];
                }

            /* sort($temps);

                for($j = $inicio; $inicio < $final; $j++)
                {

                }


                //$result = array_unique($temps);

                echo "<pre>";
                var_dump($temps);
                echo "</pre>";
            }*/
        }

        $classificacao = Pae::obterClassificacao($codigoEdital);

        return view('admin.pae.classificacao',
        [
            'codigoEdital'  => $codigoEdital,
            'inscritos'     => $classificacao,
            'anosemestre'   => $anosemestre,
        ]);                        
    }

    public function planilha($codigoEdital)
    {
        $tabela = "<table class='table table-striped'>
                    <thead>
                        <tr>
                            <th scope='col'colspan='6'>Inscritos com prioridade para auxílio</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th scope='col'>Nº USP</th>
                            <th scope='col'>Nome</th>                                
                            <th scope='col'>Desempenho Acadêmico</th>
                            <th scope='col'>Análise Currículo Lattes</th>
                            <th scope='col'>Nota</th>
                            <th scope='col'>Classificação</th>
                        </tr>
                    </thead>";
                
        $inscritos = Pae::obterClassificacao($codigoEdital, 'N');

        $semRemuneracao = array();
        $comRemuneracao = array();

        $totalConceito = Conceito::obterTotalConceito();
        $notaConceito  = Conceito::obterNotaConceito();

        $conceitoIC      = AnaliseCurriculo::obterConceitoIC();
        $conceitoEstagio = AnaliseCurriculo::obterConceitoEstagio();

        $notaEstagio     = AnaliseCurriculo::obterNotaPublicacao();
        $notaPublicacao  = AnaliseCurriculo::obterNotaICEstagio();

        foreach($inscritos as $inscrito)
        {
            $totalDesempenho      = DesempenhoAcademico::obterSomaTotalDesempenho($inscrito->codigoPae);
            $quantidadeDesempenho = DesempenhoAcademico::obterSomaQuantidadeDesempenho($inscrito->codigoPae);
                        
            if ($quantidadeDesempenho == 0)
            {
                $notaDesempenho = 0;
            }
            else
            {
                $notaDesempenho       = $totalDesempenho / $quantidadeDesempenho;
            }
            
            $finalDesempenho      = $notaDesempenho * $notaConceito;

            $ic          = Avaliacao::obterSomaAvaliacao($inscrito->codigoPae, [24]);
            $estagio     = Avaliacao::obterSomaAvaliacao($inscrito->codigoPae, [25]);
            $publicacoes = Avaliacao::obterSomaAvaliacao($inscrito->codigoPae, [12,13,14,15,16,17,18,19,20]);

            $finalEstagio = ($ic + $estagio) * $notaEstagio;

            if ($publicacoes > 10)
            {        
                $finalPublicacao = 10 * $notaPublicacao;
            }
            else
            {
                $finalPublicacao = $publicacoes * $notaPublicacao;
            }

            $notaFinal = $finalDesempenho + $finalEstagio + $finalPublicacao;

            $tabela .= "<tr>
                        <td>".$inscrito->codpes."</td>
                        <td>".$inscrito->name."</td>
                        <td>".number_format($finalDesempenho, 2, ',', '')."</td>
                        <td>".number_format(($finalEstagio + $finalPublicacao), 2, ',', '')."</td>
                        <td>".number_format($notaFinal, 2, ',', '')."</td>
                        <td>".$inscrito->classificacaoPae."</td>
                    </tr>";

        }

        $inscritos = Pae::obterClassificacao($codigoEdital, 'S');

        $tabela .= "<table class='table table-striped'>
                    <thead>
                        <tr>
                            <th scope='col'colspan='6'>Inscritos sem prioridade para auxílio</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th scope='col'>Nº USP</th>
                            <th scope='col'>Nome</th>                                
                            <th scope='col'>Desempenho Acadêmico</th>
                            <th scope='col'>Análise Currículo Lattes</th>
                            <th scope='col'>Nota</th>
                            <th scope='col'>Classificação</th>
                        </tr>
                    </thead>";

        foreach($inscritos as $inscrito)
        {
            $totalDesempenho      = DesempenhoAcademico::obterSomaTotalDesempenho($inscrito->codigoPae);
            $quantidadeDesempenho = DesempenhoAcademico::obterSomaQuantidadeDesempenho($inscrito->codigoPae);

            if ($quantidadeDesempenho == 0)
            {
                $notaDesempenho = 0;
            }
            else
            {
                $notaDesempenho       = $totalDesempenho / $quantidadeDesempenho;
            }

            $finalDesempenho      = $notaDesempenho * $notaConceito;

            $ic          = Avaliacao::obterSomaAvaliacao($inscrito->codigoPae, [24]);
            $estagio     = Avaliacao::obterSomaAvaliacao($inscrito->codigoPae, [25]);
            $publicacoes = Avaliacao::obterSomaAvaliacao($inscrito->codigoPae, [12,13,14,15,16,17,18,19,20]);

            $finalEstagio = ($ic + $estagio) * $notaEstagio;

            if ($publicacoes > 10)
            {        
                $finalPublicacao = 10 * $notaPublicacao;
            }
            else
            {
                $finalPublicacao = $publicacoes * $notaPublicacao;
            }

            $notaFinal = $finalDesempenho + $finalEstagio + $finalPublicacao;

            $tabela .= "<tr>
                        <td>".$inscrito->codpes."</td>
                        <td>".$inscrito->name."</td>
                        <td>".number_format($finalDesempenho, 2, ',', '')."</td>
                        <td>".number_format(($finalEstagio + $finalPublicacao), 2, ',', '')."</td>
                        <td>".number_format($notaFinal, 2, ',', '')."</td>
                        <td>".$inscrito->classificacaoPae."</td>
                    </tr>";

        }

        echo utf8_decode($tabela);
    }

    public function resultado($codigoEdital)
    {
        $inscricao   = Inscricao::obterInscricaoPae(Auth::user()->id, $codigoEdital);
        $anosemestre = Edital::obterSemestreAno($codigoEdital);
        $vinculo     = Posgraduacao::obterVinculoAtivo($inscricao->codpes);
        $recursos    = RecursoPae::where('codigoPae', $inscricao->codigoPae)->get();        

        $totalConceito = Conceito::obterTotalConceito();
        $notaConceito  = Conceito::obterNotaConceito();

        $conceitoIC      = AnaliseCurriculo::obterConceitoIC();
        $conceitoEstagio = AnaliseCurriculo::obterConceitoEstagio();

        $notaEstagio     = AnaliseCurriculo::obterNotaPublicacao();
        $notaPublicacao  = AnaliseCurriculo::obterNotaICEstagio();

        $totalDesempenho      = DesempenhoAcademico::obterSomaTotalDesempenho($inscricao->codigoPae);
        $quantidadeDesempenho = DesempenhoAcademico::obterSomaQuantidadeDesempenho($inscricao->codigoPae);

        if ($quantidadeDesempenho == 0)
        {
            $notaDesempenho = 0;
        }
        else
        {
            $notaDesempenho       = $totalDesempenho / $quantidadeDesempenho;
        }


        //$notaDesempenho       = $totalDesempenho / $quantidadeDesempenho;
        $finalDesempenho      = $notaDesempenho * $notaConceito;

        $ic          = Avaliacao::obterSomaAvaliacao($inscricao->codigoPae, [24]);
        $estagio     = Avaliacao::obterSomaAvaliacao($inscricao->codigoPae, [25]);
        $publicacoes = Avaliacao::obterSomaAvaliacao($inscricao->codigoPae, [12,13,14,15,16,17,18,19,20]);

        $finalEstagio = ($ic + $estagio) * $notaEstagio;

        if ($publicacoes > 10)
        {        
            $finalPublicacao = 10 * $notaPublicacao;
        }
        else
        {
            $finalPublicacao = $publicacoes * $notaPublicacao;
        }

        $notaFinal = $finalDesempenho + $finalEstagio + $finalPublicacao;

        return view('pae.resultado',
        [
            'utils'         => new Utils,
            'inscricao'     => $inscricao,
            'anosemestre'   => $anosemestre,
            'vinculo'       => $vinculo,
            'codigoEdital'  => $codigoEdital,   
            'recurso'       => Edital::obterPeriodoRecurso($codigoEdital),
            'recursos'      => $recursos,
            'notaFinal'     => number_format($notaFinal, 2, ',', ''),
            'codigoPae'     => $inscricao->codigoPae,
        ]);
    }

    public function recurso($codigoEdital)
    {
        $anosemestre = Edital::obterSemestreAno($codigoEdital);


            $recursos = RecursoPae::join('pae', 'recurso_pae.codigoPae', '=', 'pae.codigoPae')
                                        ->join('inscricoes', 'pae.codigoInscricao', '=', 'inscricoes.codigoInscricao')
                                        ->join('users', 'inscricoes.codigoUsuario', '=', 'users.id')
                                        ->where('inscricoes.codigoEdital', $codigoEdital)
                                        ->get();
                                    
        return view('admin.pae.recurso',
        [
            'utils'         => new Utils,
            'recursos'      => $recursos,
            'codigoEdital'  => $codigoEdital,
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
