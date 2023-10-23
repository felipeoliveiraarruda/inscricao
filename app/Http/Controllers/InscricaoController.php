<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use App\Models\Edital;
use App\Models\Utils;
use App\Models\Inscricao;
use App\Models\Arquivo;
use App\Models\Endereco;
use App\Models\DadosPessoais;
use App\Models\TipoDocumento;
use App\Models\User;
use App\Models\TipoEntidade;
use Codedge\Fpdf\Fpdf\Fpdf as Fpdf;
use Carbon\Carbon;
use Mail;
use App\Mail\ConfirmacaoMail;
use App\Models\InscricoesArquivos;

class InscricaoController extends Controller
{
    public function index()
    {
        if (Auth::user()->cpf == '99999999999')
        {    
            return redirect('admin/dados'); 
        }

        $editais = Edital::join('niveis', 'editais.codigoNivel', '=', 'niveis.codigoNivel')
                         ->where('editais.dataFinalEdital', '>=', Carbon::now())->get();

        return view('dashboard',
        [
            'editais'   => $editais,
            'utils'     => new Utils,
            'inscricao' => new Inscricao,
            'user_id'   => Auth::user()->id
        ]);
    }

    public function create($codigoEdital)
    {        
        $inscricao = Inscricao::obterInscricao(Auth::user()->id, $codigoEdital);

        if (empty($inscricao))
        {            
            $numero = Inscricao::gerarNumeroInscricao($codigoEdital);
            $nivel  = Edital::obterNivelEdital($codigoEdital);

            $inscricao = Inscricao::create([
                'codigoEdital'          => $codigoEdital,
                'codigoUsuario'         => Auth::user()->id,
                'numeroInscricao'       => "{$nivel}{$numero}",
                'codigoPessoaAlteracao' => Auth::user()->codpes,
            ]); 
        }

        if (empty(session('total')))
        {
            Utils::obterTotalInscricao($inscricao->codigoInscricao);
        }

        return view('inscricao',
        [
            'codigoInscricao' => $inscricao->codigoInscricao,
            'status'          => $inscricao->statusInscricao,            
        ]);
    }

    public function store($codigoEdital)
    {        
        $editais = Edital::where('dataFinalEdital', '>', Carbon::now())
                         ->where('codigoEdital', $codigoEdital)->count();

        if($editais == 0)
        {
            $item = array();
            $item['title'] = 'Aviso';
            $item['story'] = 'Inscrições Encerradas';

            return view('components.modal',
            [
                'item' => $item,                
            ]);
        }

        $inscricao = Inscricao::verificarInscricao($codigoEdital, Auth::user()->id);

        if (empty($inscricao))
        {            
            $numero = Inscricao::gerarNumeroInscricao($codigoEdital);
            $nivel  = Edital::obterNivelEdital($codigoEdital);

            $inscricao = Inscricao::create([
                'codigoEdital'          => $codigoEdital,
                'codigoUsuario'         => Auth::user()->id,
                'numeroInscricao'       => "{$nivel}{$numero}",
                'codigoPessoaAlteracao' => Auth::user()->codpes,
            ]); 
        }

        return redirect("inscricao/{$inscricao->codigoInscricao}");
    }
    
    public function pessoal($codigoInscricao)
    {        
        $arquivos = '';

        $inscricao = Inscricao::obterDadosPessoaisInscricao(Auth::user()->id, $codigoInscricao);
        $arquivos  = Arquivo::listarArquivos(Auth::user()->id, array(1, 2, 3, 4), $codigoInscricao);
        Utils::obterTotalInscricao($codigoInscricao);
        $voltar = "inscricao/{$inscricao->codigoEdital}/pessoal";
    
        return view('pessoal',
        [
            'codigoInscricao'   => $codigoInscricao,
            'codigoEdital'      => $inscricao->codigoEdital,
            'link_voltar'       => $voltar,
            'arquivos'          => $arquivos,
            'pessoais'          => $inscricao,
            'arquivo_inscricao' => '',
        ]); 
    }

    public function pessoal_create($codigoInscricao)
    {
        $inscricao = Inscricao::obterDadosPessoaisInscricao(Auth::user()->id, $codigoInscricao);
        
        $paises  = Utils::listarPais();
        $estados = Utils::listarEstado(1);
        $tipos   = TipoDocumento::listarTipoDocumentosPessoal();

        return view('inscricao.pessoal',
        [
            'codigoInscricao'   => $codigoInscricao, 
            'codigoEdital'      => $inscricao->codigoEdital,
            'status'            => $inscricao->statusInscricao,                        
            'pessoais'          => $inscricao,
            'sexos'             => Utils::obterDadosSysUtils('sexo'),
            'racas'             => Utils::obterDadosSysUtils('raça'),
            'estados_civil'     => Utils::obterDadosSysUtils('civil'),
            'especiais'         => Utils::obterDadosSysUtils('especial'),
            'paises'            => $paises,
            'estados'           => $estados,
        ]); 
    }

    public function anexar(Request $request)
    {
        if (!empty($request->codigoArquivoInscricao))
        {
            $inscricao = Inscricao::where('codigoUsuario', Auth::user()->id)->where('codigoInscricao', $request->codigoInscricao)->first();
            $temp = explode('|', $request->codigoArquivoInscricao);

            for($i = 0; $i < count($temp) - 1; $i++)
            {
                $inscricaoArquivos = InscricoesArquivos::create([
                    'codigoInscricao'       => $request->codigoInscricao,
                    'codigoArquivo'         => $temp[$i],
                    'codigoPessoaAlteracao' => Auth::user()->codpes,
                ]);
            }

            $voltar = "inscricao/{$request->codigoInscricao}/pessoal";
        }
        
        request()->session()->flash('alert-success', 'Documento(s) anexado(s) com sucesso');    
        return redirect($voltar);
    }

    public function endereco($codigoInscricao)
    {         
        $inscricao = Inscricao::obterEnderecoInscricao(Auth::user()->id, $codigoInscricao);
        Utils::obterTotalInscricao($codigoInscricao);

        $voltar = "inscricao/{$inscricao->codigoEdital}/endereco";
    
        return view('endereco',
        [
            'codigoInscricao'   => $codigoInscricao,
            'codigoEdital'      => $inscricao->codigoEdital,
            'link_voltar'       => $voltar,
            'enderecos'         => $inscricao,
        ]); 
    }  
    
    public function endereco_create($codigoInscricao)
    {
        $inscricao = Inscricao::obterEnderecoInscricao(Auth::user()->id, $codigoInscricao);
        
        $estados = Utils::listarEstado(1);

        return view('inscricao.endereco',
        [
            'codigoInscricao'   => $codigoInscricao, 
            'codigoEdital'      => $inscricao->codigoEdital,
            'status'            => $inscricao->statusInscricao,                        
            'enderecos'         => $inscricao,
            'estados'           => $estados,
        ]); 
    }

    public function emergencia($codigoInscricao)
    {         
        $inscricao = Inscricao::obterEmergenciaInscricao(Auth::user()->id, $codigoInscricao);
        Utils::obterTotalInscricao($codigoInscricao);

        $voltar = "inscricao/{$inscricao->codigoEdital}/emergencia";
    
        return view('emergencia',
        [
            'codigoInscricao'   => $codigoInscricao,
            'codigoEdital'      => $inscricao->codigoEdital,
            'link_voltar'       => $voltar,
            'emergencia'        => $inscricao,
        ]); 
    } 
    
    public function emergencia_create($codigoInscricao)
    {
        $inscricao = Inscricao::obterEmergenciaInscricao(Auth::user()->id, $codigoInscricao);
        $endereco  = Inscricao::obterEnderecoInscricao(Auth::user()->id, $codigoInscricao);

        return view('inscricao.emergencia',
        [
            'codigoInscricao'           => $codigoInscricao, 
            'codigoEdital'              => $inscricao->codigoEdital,
            'codigoInscricaoEndereco'   => $endereco->codigoInscricaoEndereco,
            'status'                    => $inscricao->statusInscricao,                        
            'emergencia'                => $inscricao,
        ]); 
    }   
    
    public function escolar($codigoInscricao)
    {         
        $codigoEdital = Inscricao::obterEditalInscricao($codigoInscricao);
        $escolares    = Inscricao::obterEscolarInscricao(Auth::user()->id, $codigoInscricao);
        Utils::obterTotalInscricao($codigoInscricao);

        $voltar = "inscricao/{$codigoEdital}/escolar";
    
        return view('escolar',
        [
            'codigoInscricao'   => $codigoInscricao,
            'codigoEdital'      => $codigoEdital,
            'link_voltar'       => $voltar,
            'escolares'         => $escolares,
        ]); 
    } 
    
    public function escolar_create($codigoInscricao)
    {
        $codigoEdital = Inscricao::obterEditalInscricao($codigoInscricao);
       // $escolar      = Inscricao::obterEscolarInscricao(Auth::user()->id, $codigoInscricao);

        return view('inscricao.escolar',
        [
            'codigoInscricao'   => $codigoInscricao, 
            'codigoEdital'      => $codigoEdital,
           // 'escolar'           => $escolar,
        ]); 
    } 

    public function idioma($codigoInscricao)
    {         
        $inscricao = Inscricao::obterIdiomaInscricao(Auth::user()->id, $codigoInscricao);
        Utils::obterTotalInscricao($codigoInscricao);

        $voltar = "inscricao/{$inscricao[0]->codigoEdital}/idioma";
    
        return view('idioma',
        [
            'codigoInscricao'   => $codigoInscricao,
            'codigoEdital'      => $inscricao[0]->codigoEdital,
            'link_voltar'       => $voltar,
            'idiomas'           => $inscricao,
        ]); 
    } 
    
    public function idioma_create($codigoInscricao)
    {
        $inscricao = Inscricao::obterIdiomaInscricao(Auth::user()->id, $codigoInscricao);

        return view('inscricao.idioma',
        [
            'codigoInscricao'   => $codigoInscricao, 
            'codigoEdital'      => $inscricao[0]->codigoEdital,
            'idiomas'           => Utils::obterDadosSysUtils('idioma'),
        ]); 
    } 
    
    public function profissional($codigoInscricao)
    {         
        $codigoEdital = Inscricao::obterEditalInscricao($codigoInscricao);
        $inscricao = Inscricao::obterProfissionalInscricao(Auth::user()->id, $codigoInscricao);
      
        Utils::obterTotalInscricao($codigoInscricao);

        $voltar = "inscricao/{$codigoEdital}/profissional";
    
        return view('profissional',
        [
            'codigoInscricao'   => $codigoInscricao,
            'codigoEdital'      => $codigoEdital,
            'link_voltar'       => $voltar,
            'profissionais'     => $inscricao,
        ]); 
    } 
    
    public function profissional_create($codigoInscricao)
    {
        $codigoEdital = Inscricao::obterEditalInscricao($codigoInscricao);

        return view('inscricao.profissional',
        [
            'codigoInscricao'   => $codigoInscricao, 
            'codigoEdital'      => $codigoEdital,
        ]); 
    } 

    public function ensino($codigoInscricao)
    {         
        $codigoEdital = Inscricao::obterEditalInscricao($codigoInscricao);
        $inscricao = Inscricao::obterEnsinoInscricao(Auth::user()->id, $codigoInscricao);
      
        Utils::obterTotalInscricao($codigoInscricao);

        $voltar = "inscricao/{$codigoEdital}/ensino";
    
        return view('ensino',
        [
            'codigoInscricao'   => $codigoInscricao,
            'codigoEdital'      => $codigoEdital,
            'link_voltar'       => $voltar,
            'ensinos'           => $inscricao,
        ]); 
    } 
    
    public function ensino_create($codigoInscricao)
    {
        $codigoEdital = Inscricao::obterEditalInscricao($codigoInscricao);
        $tipos        = TipoEntidade::whereIn('codigoTipoEntidade', [2,3])->get();

        return view('inscricao.ensino',
        [
            'codigoInscricao'   => $codigoInscricao, 
            'codigoEdital'      => $codigoEdital,
            'tipos'             => $tipos, 
        ]); 
    } 

    public function financeiro($codigoInscricao)
    {         
        $inscricao = Inscricao::obterFinanceiroInscricao(Auth::user()->id, $codigoInscricao);
      
        Utils::obterTotalInscricao($codigoInscricao);

        $voltar = "inscricao/{$inscricao->codigoEdital}/financeiro";
    
        return view('financeiro',
        [
            'codigoInscricao'   => $codigoInscricao,
            'codigoEdital'      => $inscricao->codigoEdital,
            'link_voltar'       => $voltar,
            'financeiros'       => $inscricao,
        ]); 
    } 
    
    public function financeiro_create($codigoInscricao)
    {
        $inscricao = Inscricao::obterFinanceiroInscricao(Auth::user()->id, $codigoInscricao);

        return view('inscricao.financeiro',
        [
            'codigoInscricao'   => $codigoInscricao, 
            'codigoEdital'      => $inscricao->codigoEdital,
            'financeiros'       => $inscricao, 
        ]); 
    } 



    /*public function index()
    {
        if (Auth::user()->cpf == '99999999999')
        {    
            return redirect('admin/dados'); 
        }

        $editais = Edital::where('dataFinalEdital', '>', Carbon::now())->get();

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
        $editais = Edital::where('dataFinalEdital', '>', Carbon::now())
                         ->where('codigoEdital', $id)->count();

        if($editais == 0)
        {
            $item = array();
            $item['title'] = 'Aviso';
            $item['story'] = 'Inscricão Encerrada';

            return view('components.modal',
            [
                'item' => $item,                
            ]);
        }
        
        $inscricao = Utils::obterTotalInscricao($id, Auth::user()->id);

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

        /*$arquivo = Arquivo::join('inscricoes_arquivos', 'arquivos.codigoArquivo', '=', 'inscricoes_arquivos.codigoArquivo')
                          ->where('inscricoes_arquivos.codigoInscricao', $inscricao->codigoInscricao)->count();

        $endereco = Endereco::join('inscricoes_enderecos', 'enderecos.codigoEndereco', '=', 'inscricoes_enderecos.codigoEndereco')
                            ->where('inscricoes_enderecos.codigoInscricao', $inscricao->codigoInscricao)->count();                        

        $projeto     = Arquivo::verificarArquivo($inscricao->codigoInscricao, array(10));
        $taxa        = Arquivo::verificarArquivo($inscricao->codigoInscricao, array(11));
        
        $total = $projeto + $taxa;

        return view('inscricao',
        [
            'codigoInscricao' => $inscricao->codigoInscricao,
            'status'          => $inscricao->statusInscricao,
            'pessoal'         => $inscricao->pessoal,
            'arquivo'         => $inscricao->arquivo,
            'endereco'        => $inscricao->endereco,
            'total'           => 0,
            //'arquivo'         => $arquivo,
            //'endereco'        => $endereco,
            //'total'           => $total,
        ]);
    }

    public function show($id)
    {
        $temps = Inscricao::join('users', 'inscricoes.codigoUsuario', '=', 'users.id')
                              ->where('codigoInscricao', $id)->get();
        
        $enderecos = Endereco::join('inscricoes_enderecos', 'enderecos.codigoEndereco', '=', 'inscricoes_enderecos.codigoEndereco')
                             ->where('inscricoes_enderecos.codigoInscricao', $id)->get();

        $arquivos = Arquivo::join('tipo_documentos', 'arquivos.codigoTipoDocumento', '=', 'tipo_documentos.codigoTipoDocumento')
                             ->join('inscricoes_arquivos', 'arquivos.codigoArquivo', '=', 'inscricoes_arquivos.codigoArquivo')
                             ->where('inscricoes_arquivos.codigoInscricao', $id)->get();

        foreach($temps as $temp)
        {
            $inscricao = $temp;
        }

        return view('inscricao.visualizar',
        [
            'inscricao' => $inscricao,
            'enderecos' => $enderecos,
            'arquivos'  => $arquivos,
        ]);
    }

    public function comprovante($id, Fpdf $pdf)
    {
        if (Gate::check('admin'))
        {
            Inscricao::where('codigoInscricao', $id)
                     ->where('statusInscricao', 'N')->update(['statusInscricao' => 'P']);

            $temps = Inscricao::join('users', 'inscricoes.codigoUsuario', '=', 'users.id')->where('codigoInscricao', $id)->get();

            foreach($temps as $temp)
            {
                $inscricao = $temp;
            }
        }
        else
        {
            $editais = Edital::join('inscricoes', 'editais.codigoEdital', '=', 'inscricoes.codigoEdital')        
                            ->where('editais.dataFinalEdital', '>', Carbon::now())
                            ->where('inscricoes.codigoInscricao', $id)->count();

            if($editais == 0)
            {
                $item = array();
                $item['title'] = 'Aviso';
                $item['story'] = 'Inscricão Encerrada';

                return view('components.modal',
                [
                    'item' => $item,                
                ]);
            }

            Inscricao::where('codigoUsuario', Auth::user()->id)
                     ->where('codigoInscricao', $id)
                     ->where('statusInscricao', 'N')->update(['statusInscricao' => 'P']);

            $inscricao = Inscricao::join('users', 'inscricoes.codigoUsuario', '=', 'users.id')
                                  ->where('codigoUsuario', Auth::user()->id)
                                  ->where('codigoInscricao', $id)
                                  ->where('statusInscricao', 'P')->first();
        }

        $edital = Edital::obterNumeroEdital($inscricao->codigoEdital);
        $requerimento = 'Eu, '.mb_strtoupper($inscricao->name).', portador do CPF Nº '.$inscricao->cpf.', venho requerer minha inscrição para o processo seletivo conforme regulamenta o edital PPGPE Nº '.$edital.' (DOESP de 30/09/2022).';

        $pdf->AddPage();
        $pdf->SetFillColor(190,190,190);
        $pdf->Image(asset('images/cabecalho.png'), 10, 10, 190);
        $pdf->Ln(35);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(190, 8, utf8_decode('COMPROVANTE DE REQUERIMENTO'), 1, 0, 'C', true);
        $pdf->Ln();        
        $pdf->Cell(190, 2, '', 'LR',  0, 'C', false);
        $pdf->Ln();
        $pdf->Cell(190, 8, utf8_decode("NÚMERO DE INSCRIÇÃO: {$inscricao->numeroInscricao} - {$edital}"), 'LR',  0, 'C', false);
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
        $editais = Edital::join('inscricoes', 'editais.codigoEdital', '=', 'inscricoes.codigoEdital')        
                         ->where('editais.dataFinalEdital', '>', Carbon::now())
                         ->where('inscricoes.codigoInscricao', $id)->count();

        if($editais == 0)
        {
            $item = array();
            $item['title'] = 'Aviso';
            $item['story'] = 'Inscricão Encerrada';

            return view('components.modal',
            [
                'item' => $item,                
            ]);
        }

        $inscricao = Inscricao::where('codigoUsuario', Auth::user()->id)
                              ->where('codigoInscricao', $id)
                              ->first();

        $enderecos = Endereco::join('inscricoes_enderecos', 'enderecos.codigoEndereco', '=', 'inscricoes_enderecos.codigoEndereco')
                             ->where('inscricoes_enderecos.codigoInscricao', $inscricao->codigoInscricao)->get();

        $arquivos = Arquivo::join('tipo_documentos', 'arquivos.codigoTipoDocumento', '=', 'tipo_documentos.codigoTipoDocumento')
                             ->join('inscricoes_arquivos', 'arquivos.codigoArquivo', '=', 'inscricoes_arquivos.codigoArquivo')
                             ->where('inscricoes_arquivos.codigoInscricao', $inscricao->codigoInscricao)->get();
  
        $cpf         = Arquivo::verificarArquivo($inscricao->codigoInscricao, array(1));
        $rg          = Arquivo::verificarArquivo($inscricao->codigoInscricao, array(2, 3, 4));
        $historico   = Arquivo::verificarArquivo($inscricao->codigoInscricao, array(5));
        $diploma     = Arquivo::verificarArquivo($inscricao->codigoInscricao, array(6, 7));
        $curriculo   = Arquivo::verificarArquivo($inscricao->codigoInscricao, array(8, 9));
        $projeto     = Arquivo::verificarArquivo($inscricao->codigoInscricao, array(10));
        $taxa        = Arquivo::verificarArquivo($inscricao->codigoInscricao, array(11));
        $comprovante = Arquivo::verificarArquivo($inscricao->codigoInscricao, array(12));    
        
        $total = $projeto + $taxa;
 
        return view('endereco',
        [
            'codigoInscricao' => $inscricao->codigoInscricao,
            'codigoEdital'    => $inscricao->codigoEdital,
            'status'          => $inscricao->statusInscricao,
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
        $editais = Edital::join('inscricoes', 'editais.codigoEdital', '=', 'inscricoes.codigoEdital')        
                         ->where('editais.dataFinalEdital', '>', Carbon::now())
                         ->where('inscricoes.codigoInscricao', $id)->count();

        if($editais == 0)
        {
            $item = array();
            $item['title'] = 'Aviso';
            $item['story'] = 'Inscricão Encerrada';

            return view('components.modal',
            [
                'item' => $item,                
            ]);
        }

        $inscricao = Inscricao::where('codigoUsuario', Auth::user()->id)
                              ->where('codigoInscricao', $id)
                              ->first();

        $endereco = Endereco::join('inscricoes_enderecos', 'enderecos.codigoEndereco', '=', 'inscricoes_enderecos.codigoEndereco')
                            ->where('inscricoes_enderecos.codigoInscricao', $inscricao->codigoInscricao)->count(); 

        $arquivos = Arquivo::join('tipo_documentos', 'arquivos.codigoTipoDocumento', '=', 'tipo_documentos.codigoTipoDocumento')
                           ->join('inscricoes_arquivos', 'arquivos.codigoArquivo', '=', 'inscricoes_arquivos.codigoArquivo')
                           ->where('inscricoes_arquivos.codigoInscricao', $inscricao->codigoInscricao)->get();
  
        $cpf         = Arquivo::verificarArquivo($inscricao->codigoInscricao, array(1));
        $rg          = Arquivo::verificarArquivo($inscricao->codigoInscricao, array(2, 3, 4));
        $historico   = Arquivo::verificarArquivo($inscricao->codigoInscricao, array(5));
        $diploma     = Arquivo::verificarArquivo($inscricao->codigoInscricao, array(6, 7));
        $curriculo   = Arquivo::verificarArquivo($inscricao->codigoInscricao, array(8, 9));
        $projeto     = Arquivo::verificarArquivo($inscricao->codigoInscricao, array(10));
        $taxa        = Arquivo::verificarArquivo($inscricao->codigoInscricao, array(11));
        $comprovante = Arquivo::verificarArquivo($inscricao->codigoInscricao, array(12));    
        
        $total = $projeto + $taxa;
 
        return view('arquivo',
        [
            'codigoInscricao' => $inscricao->codigoInscricao,
            'codigoEdital'    => $inscricao->codigoEdital,
            'status'          => $inscricao->statusInscricao,
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
    
    public function validar($id)
    {
        $inscricao = Inscricao::join('users', 'inscricoes.codigoUsuario', '=', 'users.id')->where('codigoInscricao', $id)->first();

        Mail::to($inscricao->email)->send(new ConfirmacaoMail($id));
        
        if (Mail::failures()) 
        {
            request()->session()->flash('alert-danger', "Ocorreu um erro na validação da inscrição Nº {$inscricao->numeroInscricao}.");
        }    
        else
        {
            Inscricao::where('codigoInscricao', $id)->where('statusInscricao', 'P')->update(['statusInscricao' => 'C']);

            request()->session()->flash('alert-success', "Inscrição Nº {$inscricao->numeroInscricao} validada com sucesso.");
        } 

        return redirect("/inscricao/visualizar/{$id}");
    }*/
}