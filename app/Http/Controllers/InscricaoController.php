<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Edital;
use App\Models\Utils;
use App\Models\Inscricao;
use App\Models\Arquivo;
use App\Models\Endereco;
use App\Models\TipoDocumento;
use Codedge\Fpdf\Fpdf\Fpdf as Fpdf;
use Carbon\Carbon;

class InscricaoController extends Controller
{
    public function index()
    {
        if (Auth::user()->cpf == '99999999999')
        {    
            return redirect('admin/dados'); 
        }

        $editais = Edital::where('dataFinalEdital', '>',  Carbon::now())->get();        

       // dd(Utils::obterEndereco('12609-260'));

        return view('dashboard',
        [
            'editais'   => $editais,
            'utils'     => new Utils,
            'inscricao' => new Inscricao,
            'user_id'   => Auth::user()->id
        ]);
    }

    public function create($id)
    {
        $inscricao = Inscricao::where('codigoUsuario', Auth::user()->id)
                              ->where('codigoEdital', $id)
                              ->first();

        if (empty($inscricao))
        {            
            $numero = Inscricao::gerarNumeroInscricao($id);
            $nivel  = Edital::obterNivelEdital($id);

            $inscricao = Inscricao::create([
                'codigoEdital'          => $id,
                'codigoUsuario'         => Auth::user()->id,
                'numeroInscricao'       => "{$nivel}{$numero}",
                'codigoPessoaAlteracao' => Auth::user()->codpes,
            ]);
        } 
        $arquivo = Arquivo::join('inscricoes_arquivos', 'arquivos.codigoArquivo', '=', 'inscricoes_arquivos.codigoArquivo')
                           ->where('inscricoes_arquivos.codigoInscricao', $inscricao->codigoInscricao)->count();

        $endereco = Endereco::join('inscricoes_enderecos', 'enderecos.codigoEndereco', '=', 'inscricoes_enderecos.codigoEndereco')
                            ->where('inscricoes_enderecos.codigoInscricao', $inscricao->codigoInscricao)->count();                        

        return view('inscricao',
        [
            'codigoInscricao' => $inscricao->codigoInscricao,
            'status'          => $inscricao->situacaoInscricao,
            'arquivo'         => $arquivo,
            'endereco'        => $endereco,
        ]);
    }

    public function show(Inscricao $inscricao)
    {

    }

    public function comprovante($id, Fpdf $pdf)
    {
        $requerimento = 'Eu, '.Auth::user()->name.', portador do CPF Nº '.Auth::user()->cpf.', venho requerer minha inscrição para o processo seletivo conforme regulamenta o edital PPGPE Nº 02/2022 (DOESP de 30/09/2022).';
        
        $pdf->AddPage();
        $pdf->SetFillColor(190,190,190);
        $pdf->Image(asset('images/cabecalho.png'), 10, 10, 190);
        $pdf->Ln(35);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(190, 8, utf8_decode('COMPROVANTE DE REQUERIMENTO'), 1, 0, 'C', true);
        //$pdf->Cell(50, 8, utf8_decode("M{$id} - 02/2022"), 1, 0, 'C', true);
        $pdf->Ln();        
        $pdf->Cell(190, 2, '', 'LR',  0, 'C', false);
        $pdf->Ln();
        $pdf->Cell(190, 8, utf8_decode("NÚMERO DE INSCRIÇÃO: ME{$id} - 02/2022"), 'LR',  0, 'C', false);
        $pdf->Ln();
        $pdf->Cell(190, 2, '', 'LR',  0, 'C', false);
        $pdf->Ln();   
        $pdf->SetFont('Arial', '', 10);
        $pdf->MultiCell(190, 8, utf8_decode($requerimento), 'LR', 'J', false);
        $pdf->Cell(190, 5, '', 'LR', 0, 'L', false);
        $pdf->Ln();
        $pdf->Cell(140, 8, 'Assinatura do candidato:', 'LB', 0, 'L', false);
        $pdf->Cell(50, 8, 'Data:         /         /', 'BR', 0, 'L', false);
                        
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

    public function endereco($id)
    {
        $inscricao = Inscricao::where('codigoUsuario', Auth::user()->id)
                              ->where('codigoEdital', $id)
                              ->first();

        $enderecos = Endereco::join('inscricoes_enderecos', 'enderecos.codigoEndereco', '=', 'inscricoes_enderecos.codigoEndereco')
                             ->where('inscricoes_enderecos.codigoInscricao', $id)->get();

        $arquivos = Arquivo::join('tipo_documentos', 'arquivos.codigoTipoDocumento', '=', 'tipo_documentos.codigoTipoDocumento')
                             ->join('inscricoes_arquivos', 'arquivos.codigoArquivo', '=', 'inscricoes_arquivos.codigoArquivo')
                             ->where('inscricoes_arquivos.codigoInscricao', $id)->get();
  
        $cpf         = Arquivo::verificarArquivo($inscricao->codigoInscricao, array(1));
        $rg          = Arquivo::verificarArquivo($inscricao->codigoInscricao, array(2, 3, 4));
        $historico   = Arquivo::verificarArquivo($inscricao->codigoInscricao, array(5));
        $diploma     = Arquivo::verificarArquivo($inscricao->codigoInscricao, array(6, 7));
        $curriculo   = Arquivo::verificarArquivo($inscricao->codigoInscricao, array(8, 9));
        $projeto     = Arquivo::verificarArquivo($inscricao->codigoInscricao, array(10));
        $taxa        = Arquivo::verificarArquivo($inscricao->codigoInscricao, array(11));
        $comprovante = Arquivo::verificarArquivo($inscricao->codigoInscricao, array(12));    
        
        $total = $cpf + $rg + $historico + $diploma + $curriculo + $projeto + $taxa;
 
        return view('endereco',
        [
            'codigoInscricao' => $inscricao->codigoInscricao,
            'status'          => $inscricao->situacaoInscricao,
            'enderecos'       => $enderecos,
            'cpf'             => $cpf,
            'rg'              => $rg,
            'historico'       => $historico,
            'diploma'         => $diploma,
            'curriculo'       => $curriculo,
            'projeto'         => $projeto,
            'taxa'            => $taxa,
            'comprovante'     => $comprovante,
            'total'           => $total
        ]);
    }

    public function documento($id)
    {
        $inscricao = Inscricao::where('codigoUsuario', Auth::user()->id)
                              ->where('codigoEdital', $id)
                              ->first();

        $endereco = Endereco::join('inscricoes_enderecos', 'enderecos.codigoEndereco', '=', 'inscricoes_enderecos.codigoEndereco')
                            ->where('inscricoes_enderecos.codigoInscricao', $inscricao->codigoInscricao)->count(); 

        $arquivos = Arquivo::join('tipo_documentos', 'arquivos.codigoTipoDocumento', '=', 'tipo_documentos.codigoTipoDocumento')
                           ->join('inscricoes_arquivos', 'arquivos.codigoArquivo', '=', 'inscricoes_arquivos.codigoArquivo')
                           ->where('inscricoes_arquivos.codigoInscricao', $id)->get();
  
        $cpf         = Arquivo::verificarArquivo($inscricao->codigoInscricao, array(1));
        $rg          = Arquivo::verificarArquivo($inscricao->codigoInscricao, array(2, 3, 4));
        $historico   = Arquivo::verificarArquivo($inscricao->codigoInscricao, array(5));
        $diploma     = Arquivo::verificarArquivo($inscricao->codigoInscricao, array(6, 7));
        $curriculo   = Arquivo::verificarArquivo($inscricao->codigoInscricao, array(8, 9));
        $projeto     = Arquivo::verificarArquivo($inscricao->codigoInscricao, array(10));
        $taxa        = Arquivo::verificarArquivo($inscricao->codigoInscricao, array(11));
        $comprovante = Arquivo::verificarArquivo($inscricao->codigoInscricao, array(12));    
        
        $total = $cpf + $rg + $historico + $diploma + $curriculo + $projeto + $taxa;
 
        return view('arquivo',
        [
            'codigoInscricao' => $inscricao->codigoInscricao,
            'status'          => $inscricao->situacaoInscricao,
            'arquivos'        => $arquivos,
            'endereco'        => $endereco,
            'cpf'             => $cpf,
            'rg'              => $rg,
            'historico'       => $historico,
            'diploma'         => $diploma,
            'curriculo'       => $curriculo,
            'projeto'         => $projeto,
            'taxa'            => $taxa,
            'comprovante'     => $comprovante,
            'total'           => $total
        ]);
    }

}