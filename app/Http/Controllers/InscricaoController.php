<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Edital;
use App\Models\Utils;
use App\Models\Inscricao;
use App\Models\Arquivo;
use App\Models\Endereco;
use App\Models\DadosPessoais;
use App\Models\TipoDocumento;
use App\Models\User;
use App\Models\TipoEntidade;
use App\Models\Comprovante;
use App\Models\ProcessoSeletivo;
use App\Models\InscricoesResumoEscolar;
use App\Models\InscricoesDisciplinas;
use Uspdev\Replicado\Posgraduacao;
use Codedge\Fpdf\Fpdf\Fpdf as Fpdf;
use Carbon\Carbon;
use Mail;
use App\Mail\ConfirmacaoMail;
use App\Mail\ComprovanteMail;
use App\Mail\ConfirmacaoEspecialMail;
use App\Mail\MatriculaMail;
use App\Mail\DevolucaoMail;
use App\Models\InscricoesArquivos;
use App\Models\InscricoesProficiencia;
use ZipArchive;
use App\Models\Pdf\Matricula;

class InscricaoController extends Controller
{
    public function index()
    {
        if (Auth::user()->cpf == '99999999999')
        {    
            return redirect('admin/dados'); 
        }

        $liberados = array();

        $inscricoes = Edital::join('niveis', 'editais.codigoNivel', '=', 'niveis.codigoNivel')
                            ->join('inscricoes', 'editais.codigoEdital', '=', 'inscricoes.codigoEdital')
                            ->where('inscricoes.codigoUsuario', '=', Auth::user()->id)
                            ->get();      

        if (count($liberados) > 0)
        {
            $editais = Edital::join('niveis', 'editais.codigoNivel', '=', 'niveis.codigoNivel')
                            ->join('inscricoes', 'editais.codigoEdital', '=', 'inscricoes.codigoEdital')
                            ->where('inscricoes.codigoUsuario', '=', Auth::user()->id)
                            ->groupBy('editais.codigoEdital')
                            ->get();
        }
        else
        {   
            $editais = Edital::join('niveis', 'editais.codigoNivel', '=', 'niveis.codigoNivel')
                            ->whereRaw('NOW() > `dataInicioEdital` AND NOW() < `dataFinalEdital`')
                            //->whereRaw('`dataInicioEdital` > NOW()')
                            ->get();
        }

        if (empty(session('level')))
        {
            Utils::setSession(Auth::user()->id);
        }

        return view('dashboard',
        [
            'editais'       => $editais,
            'inscricoes'    => $inscricoes,
            'utils'         => new Utils,
            'inscricao'     => new Inscricao,
            'user_id'       => Auth::user()->id,
            'liberado'      => (in_array(Auth::user()->id, $liberados) ? true : false),
            'level'         => session('level'),
            'total'         => count($editais),
        ]);
    }

    public function realizadas()
    {
        $editais = Edital::join('niveis', 'editais.codigoNivel', '=', 'niveis.codigoNivel')
                         ->join('inscricoes', 'editais.codigoEdital', '=', 'inscricoes.codigoEdital')
                         ->where('inscricoes.codigoUsuario', '=', Auth::user()->id)
                         ->get();     

        if (empty(session('level')))
        {
            Utils::setSession(Auth::user()->id);
        }

        return view('realizadas',
        [
            'editais'   => $editais,
            'utils'     => new Utils,
            'inscricao' => new Inscricao,
            'user_id'   => Auth::user()->id,
            'level'     => session('level'),
            'total'     => count($editais),
        ]); 
    }

    public function create($codigoEdital)
    {        
        $aprovados = array(211, 212, 213, 214, 219, 244, 272);
        
        $inscricao = Inscricao::obterInscricao(Auth::user()->id, $codigoEdital);

        if (in_array($inscricao->codigoInscricao, $aprovados))
        {
            session(['aprovado' => 1]);
        }
        else
        {
            session(['aprovado' => 0]);
        }

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

        $edital = Edital::join('niveis', 'editais.codigoNivel', '=', 'niveis.codigoNivel')
                        ->join('users', 'editais.codigoUsuario', '=', 'users.id')
                        ->where('editais.codigoEdital', $codigoEdital)->first();

        $sigla       = Utils::obterSiglaCurso($edital->codigoCurso);
        $anosemestre = Edital::obterSemestreAno($codigoEdital, true);
        $status      = Inscricao::obterStatusInscricao($inscricao->codigoInscricao);
        session(['nivel' => $inscricao->codigoNivel]);

        if ($inscricao->codigoNivel == 4 || $inscricao->codigoNivel == 2)
        {
            $total = Utils::obterTotalArquivos($inscricao->codigoInscricao, array(27, 28, 1, 2, 3, 4, 5, 6, 7, 9));

            $foto                 = Inscricao::obterAnexoInscricao($inscricao->codigoInscricao, array(27));
            $cpf                  = Inscricao::obterAnexoInscricao($inscricao->codigoInscricao, array(1));
            $rg                   = Inscricao::obterAnexoInscricao($inscricao->codigoInscricao, array(2));
            $rne                  = Inscricao::obterAnexoInscricao($inscricao->codigoInscricao, array(3));
            $passaporte           = Inscricao::obterAnexoInscricao($inscricao->codigoInscricao, array(4));
            $historico            = Inscricao::obterAnexoInscricao($inscricao->codigoInscricao, array(5, 29, 40));
            $diploma              = Inscricao::obterAnexoInscricao($inscricao->codigoInscricao, array(6, 7, 30, 41));
            $curriculo            = Inscricao::obterAnexoInscricao($inscricao->codigoInscricao, array(9));
            $requerimento         = Inscricao::obterAnexoInscricao($inscricao->codigoInscricao, array(28));
    
            $curriculo_coorientador = Inscricao::obterAnexoInscricao($inscricao->codigoInscricao, array(36));
            $credenciamento         = Inscricao::obterAnexoInscricao($inscricao->codigoInscricao, array(37));
            $carta_aceite           = Inscricao::obterAnexoInscricao($inscricao->codigoInscricao, array(38));
            $carta_coorientador     = Inscricao::obterAnexoInscricao($inscricao->codigoInscricao, array(39));
        }

        if ($inscricao->codigoNivel == 3)
        {
            $total = Utils::obterTotalArquivos($inscricao->codigoInscricao, array(27, 28, 1, 2, 4, 3, 29, 30, 9, 31, 32, 33, 34, 35, 36, 37, 38, 39));

            $foto                 = Inscricao::obterAnexoInscricao($inscricao->codigoInscricao, array(27));
            $cpf                  = Inscricao::obterAnexoInscricao($inscricao->codigoInscricao, array(1));
            $rg                   = Inscricao::obterAnexoInscricao($inscricao->codigoInscricao, array(2));
            $rne                  = Inscricao::obterAnexoInscricao($inscricao->codigoInscricao, array(3));
            $passaporte           = Inscricao::obterAnexoInscricao($inscricao->codigoInscricao, array(4));
            $historico            = Inscricao::obterAnexoInscricao($inscricao->codigoInscricao, array(29));
            $diploma              = Inscricao::obterAnexoInscricao($inscricao->codigoInscricao, array(30));
            $curriculo            = Inscricao::obterAnexoInscricao($inscricao->codigoInscricao, array(9));
            $plano_estudo         = Inscricao::obterAnexoInscricao($inscricao->codigoInscricao, array(31));
            $projeto              = Inscricao::obterAnexoInscricao($inscricao->codigoInscricao, array(32));
            $carta                = Inscricao::obterAnexoInscricao($inscricao->codigoInscricao, array(33));
            $curriculo_orientador = Inscricao::obterAnexoInscricao($inscricao->codigoInscricao, array(34));
            $termo_orientacao     = Inscricao::obterAnexoInscricao($inscricao->codigoInscricao, array(35));
            $requerimento         = Inscricao::obterAnexoInscricao($inscricao->codigoInscricao, array(28));

            if ($inscricao->planoCoorientador == 'S')
            {
                $curriculo_coorientador = Inscricao::obterAnexoInscricao($inscricao->codigoInscricao, array(36));
                $credenciamento         = Inscricao::obterAnexoInscricao($inscricao->codigoInscricao, array(37));
                $carta_aceite           = Inscricao::obterAnexoInscricao($inscricao->codigoInscricao, array(38));
                $carta_coorientador     = Inscricao::obterAnexoInscricao($inscricao->codigoInscricao, array(39));
            }
        }

        if ($inscricao->codigoNivel == 1)
        {
            $total = Utils::obterTotalArquivos($inscricao->codigoInscricao, array(1, 2, 3, 4, 5, 6, 9, 27, 28));

            $foto                 = Inscricao::obterAnexoInscricao($inscricao->codigoInscricao, array(27));
            $cpf                  = Inscricao::obterAnexoInscricao($inscricao->codigoInscricao, array(1));
            $rg                   = Inscricao::obterAnexoInscricao($inscricao->codigoInscricao, array(2));
            $rne                  = Inscricao::obterAnexoInscricao($inscricao->codigoInscricao, array(3));
            $passaporte           = Inscricao::obterAnexoInscricao($inscricao->codigoInscricao, array(4));
            $historico            = Inscricao::obterAnexoInscricao($inscricao->codigoInscricao, array(5));
            $diploma              = Inscricao::obterAnexoInscricao($inscricao->codigoInscricao, array(6));
            $requerimento         = Inscricao::obterAnexoInscricao($inscricao->codigoInscricao, array(28));
            $curriculo            = Inscricao::obterAnexoInscricao($inscricao->codigoInscricao, array(9));
        }

        Utils::obterTotalInscricao($inscricao->codigoInscricao);

        return view('inscricao',
        [
            'codigoInscricao'        => $inscricao->codigoInscricao,
            'codigoCurso'            => $inscricao->codigoCurso,
            'status'                 => $status,
            'codigoEdital'           => $codigoEdital, 
            'total'                  => $total,
            'sigla'                  => $sigla,
            'anosemestre'            => $anosemestre,
            'email'                  => $edital->email,
            'coorientador'           => $inscricao->planoCoorientador,
            'foto'                   => (empty($foto) ? '' : $foto),
            'cpf'                    => (empty($cpf) ? '' : $cpf),
            'rg'                     => (empty($rg) ? '' : $rg),
            'rne'                    => (empty($rne) ? '' : $rne),
            'passaporte'             => (empty($passaporte) ? '' : $passaporte),
            'historico'              => (empty($historico) ? '' : $historico),
            'diploma'                => (empty($diploma) ? '' : $diploma),
            'curriculo'              => (empty($curriculo) ? '' : $curriculo),
            'plano_estudo'           => (empty($plano_estudo) ? '' : $plano_estudo),
            'projeto'                => (empty($projeto) ? '' : $projeto),
            'carta'                  => (empty($carta) ? '' : $carta),
            'curriculo_orientador'   => (empty($curriculo_orientador) ? '' : $curriculo_orientador),
            'termo_orientacao'       => (empty($termo_orientacao) ? '' : $termo_orientacao),
            'curriculo_coorientador' => (empty($curriculo_coorientador) ? '' : $curriculo_coorientador),
            'credenciamento'         => (empty($credenciamento) ? '' : $credenciamento),
            'carta_aceite'           => (empty($carta_aceite) ? '' : $carta_aceite),
            'requerimento'           => (empty($requerimento) ? '' : $requerimento),
            'carta_coorientador'     => (empty($carta_coorientador) ? '' : $carta_coorientador),
            'pais'                   => $inscricao->paisPessoal,     
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

        return redirect("inscricao/{$codigoEdital}");
    }
    
    public function pessoal($codigoInscricao)
    {        
        $arquivos = '';

        $inscricao = Inscricao::obterDadosPessoaisInscricao($codigoInscricao);

        $arquivos = Arquivo::listarArquivos(Auth::user()->id, array(1, 2, 3, 4, 27), $codigoInscricao);
        
        Utils::obterTotalInscricao($codigoInscricao);
        $total = Utils::obterTotalArquivos($codigoInscricao, array(27, 28, 1, 2, 4, 3, 29, 30, 9, 31, 32, 33, 34, 35, 36, 37, 38));

        $voltar = "inscricao/{$inscricao->codigoEdital}/pessoal";

        $status = Inscricao::obterStatusInscricao($codigoInscricao);
    
        return view('pessoal',
        [
            'codigoInscricao'   => $codigoInscricao,
            'codigoEdital'      => $inscricao->codigoEdital,
            'link_voltar'       => $voltar,
            'arquivos'          => $arquivos,
            'pessoais'          => $inscricao,
            'status'            => $status,
            'nivel'             => session(['nivel']),
            'total'             => $total,
        ]); 
    }

    public function pessoal_create($codigoInscricao)
    {
        $inscricao = Inscricao::obterDadosPessoaisInscricao($codigoInscricao);
        $status = Inscricao::obterStatusInscricao($codigoInscricao);

        if ($status == 'P')
        {
            return redirect("inscricao/{$inscricao->codigoEdital}"); 
        }

        $paises  = Utils::listarPais();

        if(empty($inscricao))
        {
            $estados = Utils::listarEstado(1);
        }
        else
        {
            $estados = Utils::listarEstado($inscricao->paisPessoal);
        }
        
        $paises  = Utils::listarPais();
        $tipos   = TipoDocumento::listarTipoDocumentosPessoal();

        $status = Inscricao::obterStatusInscricao($codigoInscricao);

        return view('inscricao.pessoal',
        [
            'codigoInscricao'   => $codigoInscricao, 
            'codigoEdital'      => $inscricao->codigoEdital,
            'status'            => $status,                        
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
        $inscricao = Inscricao::obterEnderecoInscricao($codigoInscricao);
        Utils::obterTotalInscricao($codigoInscricao);
        $total = Utils::obterTotalArquivos($inscricao->codigoInscricao, array(27, 1, 2, 3, 4, 29, 9, 31, 32, 33, 34, 35, 28));

        $voltar = "inscricao/{$inscricao->codigoEdital}/endereco";

        $status = Inscricao::obterStatusInscricao($codigoInscricao);
    
        return view('endereco',
        [
            'codigoInscricao'   => $codigoInscricao,
            'codigoEdital'      => $inscricao->codigoEdital,
            'link_voltar'       => $voltar,
            'enderecos'         => $inscricao,
            'nivel'             => session(['nivel']),
            'status'            => $status,
            'total'             => $total,
        ]); 
    }  
    
    public function endereco_create($codigoInscricao)
    {
        $inscricao = Inscricao::obterEnderecoInscricao($codigoInscricao);
        $status = Inscricao::obterStatusInscricao($codigoInscricao);

        $update    = false;

        if ($status == 'P')
        {
            return redirect("inscricao/{$inscricao->codigoEdital}"); 
        }

        if (!empty($inscricao->codigoEndereco))
        {
            $update = true;
        }
        
        $estados = Utils::listarEstado(1);

        $status = Inscricao::obterStatusInscricao($codigoInscricao);

        return view('inscricao.endereco',
        [
            'codigoInscricao'   => $codigoInscricao, 
            'codigoEdital'      => $inscricao->codigoEdital,    
            'enderecos'         => $inscricao,
            'estados'           => $estados,
            'update'            => $update,
            'status'            => $status,
        ]); 
    }

    public function emergencia($codigoInscricao)
    {         
        $inscricao = Inscricao::obterEmergenciaInscricao($codigoInscricao);
        Utils::obterTotalInscricao($codigoInscricao);
        $total = Utils::obterTotalArquivos($codigoInscricao, array(27, 28, 1, 2, 4, 3, 29, 30, 9, 31, 32, 33, 34, 35, 36, 37, 38));;
        $endereco = '';

        $status    = Inscricao::obterStatusInscricao($codigoInscricao);
        $edital    = Inscricao::obterEditalInscricao($codigoInscricao);

        if (!empty($inscricao->codigoEmergenciaEndereco))
        {
            $endereco = Endereco::find($inscricao->codigoEmergenciaEndereco);
        }

        $voltar = "inscricao/{$edital}/emergencia";
    
        return view('emergencia',
        [
            'codigoInscricao'   => $codigoInscricao,
            'codigoEdital'      => $edital,
            'link_voltar'       => $voltar,
            'emergencia'        => $inscricao,
            'nivel'             => session(['nivel']),
            'status'            => $status,
            'total'             => $total,
            'endereco'          => $endereco,
        ]); 
    } 
    
    public function emergencia_create($codigoInscricao)
    {
        $inscricao = Inscricao::obterEmergenciaInscricao($codigoInscricao);

        if (!empty($inscricao->codigoEmergenciaEndereco))
        {
            $endereco = Endereco::find($inscricao->codigoEmergenciaEndereco);
            $codigoInscricaoEndereco = $inscricao->codigoEmergenciaEndereco;
        }
        else
        {
            $endereco = '';
            $codigoInscricaoEndereco = '';
            $inscricao = '';
        }

        $status    = Inscricao::obterStatusInscricao($codigoInscricao);
        $edital    = Inscricao::obterEditalInscricao($codigoInscricao);

        return view('inscricao.emergencia',
        [
            'codigoInscricao'           => $codigoInscricao, 
            'codigoEdital'              => $edital,
            'codigoInscricaoEndereco'   => $codigoInscricaoEndereco,                     
            'emergencia'                => $inscricao,
            'endereco'                  => $endereco,
        ]); 
    }   
    
    public function escolar($codigoInscricao)
    {         
        $codigoEdital = Inscricao::obterEditalInscricao($codigoInscricao);
        $escolares    = Inscricao::obterEscolarInscricao($codigoInscricao);
        Utils::obterTotalInscricao($codigoInscricao);
        $total = Utils::obterTotalArquivos($codigoInscricao, array(27, 28, 1, 2, 4, 3, 29, 30, 9, 31, 32, 33, 34, 35, 36, 37, 38));;

        $voltar = "inscricao/{$codigoEdital}/escolar";

        $status = Inscricao::obterStatusInscricao($codigoInscricao);
    
        return view('escolar',
        [
            'codigoInscricao'   => $codigoInscricao,
            'codigoEdital'      => $codigoEdital,
            'link_voltar'       => $voltar,
            'escolares'         => $escolares,
            'nivel'             => session(['nivel']),
            'status'            => $status,
            'total'             => $total,
        ]); 
    } 
    
    public function escolar_create($codigoInscricao, $codigoResumoEscolar = '')
    {
        if (!empty($codigoResumoEscolar))
        {
            $inscricao = Inscricao::obterEscolarInscricao($codigoInscricao, $codigoResumoEscolar);
            $codigoInscricaoResumoEscolar = $inscricao->codigoInscricaoResumoEscolar;
        }
        else
        {
            $inscricao = array();
            $codigoInscricaoResumoEscolar = '';
        }
        
        if (!empty($codigoInscricaoResumoEscolar))
        {
            $arquivos = InscricoesResumoEscolar::find($codigoInscricaoResumoEscolar);
            $codigoHistorico = $arquivos->codigoHistorico;
            $codigoDiploma   = $arquivos->codigoDiploma;
        }
        else
        {
            $codigoHistorico = '';
            $codigoDiploma   = '';
        }

        $status = Inscricao::obterStatusInscricao($codigoInscricao);
        $edital = Inscricao::obterEditalInscricao($codigoInscricao);

        if ($status == 'P')
        {
            return redirect("inscricao/{$edital}"); 
        }

        return view('inscricao.escolar',
        [
            'codigoInscricao'               => $codigoInscricao, 
            'codigoEdital'                  => $edital,
            'escolar'                       => $inscricao,
            'codigoResumoEscolar'           => $codigoResumoEscolar,
            'codigoInscricaoResumoEscolar'  => $codigoInscricaoResumoEscolar,
            'status'                        => $status,
            'codigoHistorico'               => $codigoHistorico,
            'codigoDiploma'                 => $codigoDiploma,
            'ano'                           => date('Y'),
        ]); 
    } 

    public function idioma($codigoInscricao)
    {         
        $inscricao = Inscricao::obterIdiomaInscricao($codigoInscricao);
        Utils::obterTotalInscricao($codigoInscricao);
        $total = Utils::obterTotalArquivos($codigoInscricao, array(27, 28, 1, 2, 4, 3, 29, 30, 9, 31, 32, 33, 34, 35, 36, 37, 38));;
        $voltar = "inscricao/{$inscricao[0]->codigoEdital}/idioma";

        $status = Inscricao::obterStatusInscricao($codigoInscricao);
    
        return view('idioma',
        [
            'codigoInscricao'   => $codigoInscricao,
            'codigoEdital'      => $inscricao[0]->codigoEdital,
            'link_voltar'       => $voltar,
            'idiomas'           => $inscricao,
            'nivel'             => session(['nivel']),
            'status'            => $status,
            'total'             => $total,
        ]); 
    } 
    
    public function idioma_create($codigoInscricao, $codigoIdioma = '')
    {
        if (!empty($codigoIdioma))
        {
            $inscricao = Inscricao::obterIdiomaInscricao($codigoInscricao, $codigoIdioma);
            $codigoInscricaoIdioma = $inscricao->codigoInscricaoIdioma;
        }
        else
        {
            $inscricao = array();
            $codigoInscricaoIdioma = '';
        }
        
        $status    = Inscricao::obterStatusInscricao($codigoInscricao);
        $edital    = Inscricao::obterEditalInscricao($codigoInscricao);

        if ($status == 'P')
        {
            return redirect("inscricao/{$edital}"); 
        }

        return view('inscricao.idioma',
        [
            'codigoInscricao'       => $codigoInscricao, 
            'codigoEdital'          => $edital,
            'idiomas'               => Utils::obterDadosSysUtils('idioma'),
            'idioma'                => $inscricao,
            'codigoIdioma'          => $codigoIdioma,
            'codigoInscricaoIdioma' => $codigoInscricaoIdioma,
        ]); 
    } 
    
    public function profissional($codigoInscricao)
    {         
        $codigoEdital = Inscricao::obterEditalInscricao($codigoInscricao);
        $inscricao    = Inscricao::obterProfissionalInscricao($codigoInscricao);
      
        Utils::obterTotalInscricao($codigoInscricao);
        $total = Utils::obterTotalArquivos($codigoInscricao, array(27, 28, 1, 2, 4, 3, 29, 30, 9, 31, 32, 33, 34, 35, 36, 37, 38));;

        $voltar = "inscricao/{$codigoEdital}/profissional";

        $status = Inscricao::obterStatusInscricao($codigoInscricao);
    
        return view('profissional',
        [
            'codigoInscricao'   => $codigoInscricao,
            'codigoEdital'      => $codigoEdital,
            'link_voltar'       => $voltar,
            'profissionais'     => $inscricao,
            'nivel'             => session(['nivel']),
            'status'            => $status,
            'total'             => $total,
        ]); 
    } 
    
    public function profissional_create($codigoInscricao, $codigoExperiencia = '')
    {
        if (!empty($codigoExperiencia))
        {
            $inscricao = Inscricao::obterProfissionalInscricao($codigoInscricao, $codigoExperiencia);
            $codigoInscricaoExperiencia = $inscricao->codigoInscricaoExperiencia;
        }
        else
        {
            $inscricao = array();
            $codigoInscricaoExperiencia = '';
        }
        
        $status    = Inscricao::obterStatusInscricao($codigoInscricao);
        $edital    = Inscricao::obterEditalInscricao($codigoInscricao);

        if ($status == 'P')
        {
            return redirect("inscricao/{$edital}"); 
        }

        return view('inscricao.profissional',
        [
            'codigoInscricao'               => $codigoInscricao, 
            'codigoEdital'                  => $edital,
            'codigoInscricaoExperiencia'    => $codigoInscricaoExperiencia,
            'profissional'                  => $inscricao,
            'codigoExperiencia'             => $codigoExperiencia,
            'status'                        => $status,
        ]); 
    } 

    public function ensino($codigoInscricao)
    {         
        $codigoEdital = Inscricao::obterEditalInscricao($codigoInscricao);
        $inscricao    = Inscricao::obterEnsinoInscricao($codigoInscricao); 

        Utils::obterTotalInscricao($codigoInscricao);
        $total = Utils::obterTotalArquivos($codigoInscricao, array(27, 28, 1, 2, 4, 3, 29, 30, 9, 31, 32, 33, 34, 35, 36, 37, 38));;

        $voltar = "inscricao/{$codigoEdital}/ensino";

        $status = Inscricao::obterStatusInscricao($codigoInscricao);
    
        return view('ensino',
        [
            'codigoInscricao'   => $codigoInscricao,
            'codigoEdital'      => $codigoEdital,
            'link_voltar'       => $voltar,
            'ensinos'           => $inscricao,
            'nivel'             => session(['nivel']),
            'status'            => $status,
            'total'             => $total,
        ]); 
    } 
    
    public function ensino_create($codigoInscricao, $codigoExperiencia = '')
    {
        if (!empty($codigoExperiencia))
        {
            $inscricao = Inscricao::obterEnsinoInscricao($codigoInscricao, $codigoExperiencia);
            $codigoInscricaoExperiencia = $inscricao->codigoInscricaoExperiencia;
        }
        else
        {
            $inscricao = array();
            $codigoInscricaoExperiencia = '';
        }

        $tipos     = TipoEntidade::whereIn('codigoTipoEntidade', [2,3])->get();
        $status    = Inscricao::obterStatusInscricao($codigoInscricao);
        $edital    = Inscricao::obterEditalInscricao($codigoInscricao);

        if ($status == 'P')
        {
            return redirect("inscricao/{$edital}"); 
        }

        return view('inscricao.ensino',
        [
            'codigoInscricao'               => $codigoInscricao, 
            'codigoEdital'                  => $edital,
            'tipos'                         => $tipos, 
            'codigoInscricaoExperiencia'    => $codigoInscricaoExperiencia,
            'ensino'                        => $inscricao,
            'codigoExperiencia'             => $codigoExperiencia,
            'status'                        => $status,
        ]); 
    } 

    public function financeiro($codigoInscricao)
    {         
        $inscricao = Inscricao::obterFinanceiroInscricao($codigoInscricao);
      
        Utils::obterTotalInscricao($codigoInscricao);
        $total = Utils::obterTotalArquivos($codigoInscricao, array(27, 28, 1, 2, 4, 3, 29, 30, 9, 31, 32, 33, 34, 35, 36, 37, 38));;

        $voltar = "inscricao/{$inscricao->codigoEdital}/financeiro";

        $status = Inscricao::obterStatusInscricao($codigoInscricao);
    
        return view('financeiro',
        [
            'codigoInscricao'   => $codigoInscricao,
            'codigoEdital'      => $inscricao->codigoEdital,
            'link_voltar'       => $voltar,
            'financeiros'       => $inscricao,
            'nivel'             => session(['nivel']),
            'status'            => $status,
            'total'             => $total,
        ]); 
    } 
    
    public function financeiro_create($codigoInscricao)
    {
        $inscricao = Inscricao::obterFinanceiroInscricao($codigoInscricao);

        $status    = Inscricao::obterStatusInscricao($codigoInscricao);
        $edital    = Inscricao::obterEditalInscricao($codigoInscricao);

        if ($status == 'P')
        {
            return redirect("inscricao/{$inscricao->codigoEdital}"); 
        }

        $resumo = Inscricao::obterUltimaTitulacao($codigoInscricao);

        return view('inscricao.financeiro',
        [
            'codigoInscricao'   => $codigoInscricao, 
            'codigoEdital'      => $inscricao->codigoEdital,
            'financeiros'       => $inscricao,
            'status'            => $status, 
            'resumo'            => $resumo,
        ]); 
    } 

    public function expectativas($codigoInscricao)
    {         
        $inscricao = Inscricao::obterExpectativaInscricao($codigoInscricao);
      
        Utils::obterTotalInscricao($codigoInscricao);
        $total = Utils::obterTotalArquivos($codigoInscricao, array(27, 28, 1, 2, 4, 3, 29, 30, 9, 31, 32, 33, 34, 35, 36, 37, 38));;

        $voltar = "inscricao/{$inscricao->codigoEdital}/expectativas";

        $nivel = Edital::obterNivelEdital($inscricao->codigoEdital);

        if ($nivel == 'AE')
        {
            $titulo = 'Por que cursar disciplina como aluno especial?';
        }
        else
        {
            $titulo = 'Quais as suas expectativas com relação ao curso?';
        }

        $status = Inscricao::obterStatusInscricao($codigoInscricao);
    
        return view('expectativas',
        [
            'codigoInscricao'   => $codigoInscricao,
            'codigoEdital'      => $inscricao->codigoEdital,
            'link_voltar'       => $voltar,
            'expectativas'      => $inscricao,
            'nivel'             => session(['nivel']),
            'status'            => $status,
            'total'             => $total,
            'titulo'            => $titulo,
        ]); 
    } 

    public function expectativas_create($codigoInscricao)
    {         
        $inscricao = Inscricao::obterExpectativaInscricao($codigoInscricao);

        $status    = Inscricao::obterStatusInscricao($codigoInscricao);
        $edital    = Inscricao::obterEditalInscricao($codigoInscricao);

        if ($status == 'P')
        {
            return redirect("inscricao/{$inscricao->codigoEdital}"); 
        }

        $nivel = Edital::obterNivelEdital($inscricao->codigoEdital);

        if ($nivel == 'AE')
        {
            $titulo = 'Por que cursar disciplina como aluno especial?';
        }
        else
        {
            $titulo = 'Quais as suas expectativas com relação ao curso?';
        }
      
        Utils::obterTotalInscricao($codigoInscricao);
        $total = Utils::obterTotalArquivos($codigoInscricao, array(27, 28, 1, 2, 4, 3, 29, 30, 9, 31, 32, 33, 34, 35, 36, 37, 38));;
        
        $voltar = "inscricao/{$inscricao->codigoEdital}/expectativas";

        $status = Inscricao::obterStatusInscricao($codigoInscricao);
    
        return view('inscricao.expectativas',
        [
            'codigoInscricao'   => $codigoInscricao,
            'codigoEdital'      => $inscricao->codigoEdital,
            'link_voltar'       => $voltar,
            'expectativas'      => $inscricao,
            'total'             => $total,
            'titulo'            => $titulo,
            'status'            => $status,
        ]); 
    } 
    
    public function expectativas_store(Request $request)
    { 
        $inscricao = Inscricao::find($request->codigoInscricao);
        $inscricao->expectativasInscricao = $request->expectativasInscricao;
        $inscricao->save();

        request()->session()->flash('alert-success', 'Expectativa cadastrada com sucesso.');
        
        $voltar = "inscricao/{$request->codigoInscricao}/expectativas";

        return redirect($voltar); 
    }

    public function curriculo($codigoInscricao)
    {         
        $inscricao = Inscricao::obterCurriculoInscricao($codigoInscricao);

        $status    = Inscricao::obterStatusInscricao($codigoInscricao);
        $edital    = Inscricao::obterEditalInscricao($codigoInscricao);
      
        Utils::obterTotalInscricao($codigoInscricao);
        $total = Utils::obterTotalArquivos($codigoInscricao, array(27, 28, 1, 2, 4, 3, 29, 30, 9, 31, 32, 33, 34, 35, 36, 37, 38));;

        $voltar = "inscricao/{$edital}/curriculo";
    
        return view('curriculo',
        [
            'codigoInscricao'   => $codigoInscricao,
            'codigoEdital'      => $edital,
            'link_voltar'       => $voltar,
            'curriculo'         => $inscricao,
            'nivel'             => session(['nivel']),
            'total'             => $total,
            'status'            => $status,
        ]); 
    } 

    public function curriculo_create($codigoInscricao)
    {         
        $inscricao = Inscricao::obterExpectativaInscricao($codigoInscricao);
        $tipos     = TipoDocumento::whereIn('codigoTipoDocumento', [8,9])->get();

        if ($status == 'P')
        {
            return redirect("inscricao/{$inscricao->codigoEdital}"); 
        }
      
        Utils::obterTotalInscricao($codigoInscricao);

        $voltar = "inscricao/{$inscricao->codigoEdital}/curriculo";

        $status = Inscricao::obterStatusInscricao($codigoInscricao);
    
        return view('inscricao.curriculo',
        [
            'codigoInscricao'   => $codigoInscricao,
            'codigoEdital'      => $inscricao->codigoEdital,
            'link_voltar'       => $voltar,
            'curriculo'         => $inscricao,
            'tipos'             => $tipos,
            'status'            => $status,
        ]); 
    } 
    
    public function curriculo_store(Request $request)
    { 
        $path = $request->file('arquivo')->store('arquivos', 'public');

        $arquivo = Arquivo::create([
            'codigoUsuario'         => Auth::user()->id,
            'codigoTipoDocumento'   => $request->codigoTipoDocumento,
            'linkArquivo'           => $path,
            'codigoPessoaAlteracao' => Auth::user()->codpes,
        ]);

        $inscricaoDocumentos = InscricoesArquivos::create([
            'codigoInscricao'       => $request->codigoInscricao,
            'codigoArquivo'         => $arquivo->codigoArquivo,
            'codigoPessoaAlteracao' => Auth::user()->codpes,
        ]);

        request()->session()->flash('alert-success', 'Currículo cadastrado com sucesso');    
        $voltar = "inscricao/{$request->codigoInscricao}/curriculo";

        return redirect($voltar); 
    }

    public function projeto($codigoInscricao)
    {         
        $codigoEdital = Inscricao::obterEditalInscricao($codigoInscricao);
        $inscricao = Inscricao::obterProjetoInscricao($codigoInscricao);
      
        Utils::obterTotalInscricao($codigoInscricao);
        $total = Utils::obterTotalArquivos($codigoInscricao, array(27, 28, 1, 2, 4, 3, 29, 30, 9, 31, 32, 33, 34, 35, 36, 37, 38));;

        $voltar = "inscricao/{$codigoEdital}/pre-projeto";

        $status = Inscricao::obterStatusInscricao($codigoInscricao);
    
        return view('pre_projeto',
        [
            'codigoInscricao'   => $codigoInscricao,
            'codigoEdital'      => $codigoEdital,
            'link_voltar'       => $voltar,
            'projeto'           => $inscricao,
            'nivel'             => session(['nivel']),
            'total'             => $total,
            'status'            => $status,
        ]); 
    } 

    public function projeto_create($codigoInscricao)
    {         
        $inscricao = Inscricao::obterExpectativaInscricao($codigoInscricao);

        if ($status == 'P')
        {
            return redirect("inscricao/{$inscricao->codigoEdital}"); 
        }
      
        Utils::obterTotalInscricao($codigoInscricao);

        $voltar = "inscricao/{$inscricao->codigoEdital}/pre-projeto";

        $status = Inscricao::obterStatusInscricao($codigoInscricao);
    
        return view('inscricao.pre_projeto',
        [
            'codigoInscricao'   => $codigoInscricao,
            'codigoEdital'      => $inscricao->codigoEdital,
            'link_voltar'       => $voltar,
            'projeto'           => $inscricao,
            'status'            => $status,
        ]); 
    } 
    
    public function projeto_store(Request $request)
    { 
        $path = $request->file('arquivo')->store('arquivos', 'public');

        $arquivo = Arquivo::create([
            'codigoUsuario'         => Auth::user()->id,
            'codigoTipoDocumento'   => $request->codigoTipoDocumento,
            'linkArquivo'           => $path,
            'codigoPessoaAlteracao' => Auth::user()->codpes,
        ]);

        $inscricaoDocumentos = InscricoesArquivos::create([
            'codigoInscricao'       => $request->codigoInscricao,
            'codigoArquivo'         => $arquivo->codigoArquivo,
            'codigoPessoaAlteracao' => Auth::user()->codpes,
        ]);

        request()->session()->flash('alert-success', 'Pré-projeto cadastrado com sucesso');    
        $voltar = "inscricao/{$request->codigoInscricao}/pre-projeto";

        return redirect($voltar); 
    }

    public function disciplina($codigoInscricao)
    {         
        $inscricao = Inscricao::obterDisciplinaInscricao($codigoInscricao);

        $status    = Inscricao::obterStatusInscricao($codigoInscricao);
        $edital    = Inscricao::obterEditalInscricao($codigoInscricao);
      
        Utils::obterTotalInscricao($codigoInscricao);
        $total = Utils::obterTotalArquivos($codigoInscricao, array(27, 28, 1, 2, 4, 3, 29, 30, 9, 31, 32, 33, 34, 35, 36, 37, 38));

        $voltar = "inscricao/{$edital}/disciplina";
    
        return view('disciplina',
        [
            'codigoInscricao'   => $codigoInscricao,
            'codigoEdital'      => $edital,
            'link_voltar'       => $voltar,
            'disciplinas'       => $inscricao,
            'nivel'             => session(['nivel']),
            'total'             => $total,
            'status'            => $status,
            'count'             => count($inscricao),
        ]); 
    } 

    public function disciplina_create($codigoInscricao)
    {         
        $inscricao = Inscricao::obterDisciplinaInscricao($codigoInscricao);

        $status    = Inscricao::obterStatusInscricao($codigoInscricao);
        $edital    = Inscricao::obterEditalInscricao($codigoInscricao);

        if ($status == 'P')
        {
            return redirect("inscricao/{$inscricao->codigoEdital}"); 
        }
      
        Utils::obterTotalInscricao($codigoInscricao);
        $total = Utils::obterTotalArquivos($codigoInscricao, array(27, 28, 1, 2, 4, 3, 29, 30, 9, 31, 32, 33, 34, 35, 36, 37, 38));

        $codigoCurso = Edital::obterCursoEdital($edital);
        
        $disciplinas = Utils::listarOferecimentoPos($codigoCurso, '05/08/2024', '17/11/2024');

        if ($codigoCurso == '97002')
        {
            $temp = array('sgldis' => 'PEM5121', 'nomdis' => 'Supercondutividade Aplicada e Experimental', 'numseqdis' => 4, 'numcretotdis' => 12, 'numofe' => 4, 'numvagespofe' => 10);
            array_push($disciplinas, $temp);
        }

        $status = Inscricao::obterStatusInscricao($codigoInscricao);

        $voltar = "inscricao/{$edital}/disciplinas";
    
        return view('inscricao.disciplinas',
        [
            'codigoInscricao'   => $codigoInscricao,
            'codigoEdital'      => $edital,
            'link_voltar'       => $voltar,
            'disciplinas'       => $inscricao,
            'total'             => $total,
            'disciplinas'       => $disciplinas,
            'limite'            => ($codigoCurso == 97002 ? 1 : 0),
            'status'            => $status,
        ]); 
    } 

    public function disciplina_store(Request $request)
    { 
        foreach($request->disciplinasGcub as $disciplina)
        {
            $inscricaoDisciplinas = InscricoesDisciplinas::create([
                'codigoInscricao'       => $request->codigoInscricao,
                'codigoDisciplina'      => $disciplina,
                'codigoPessoaAlteracao' => Auth::user()->codpes,
            ]);
        }

        $inscricao = Inscricao::find($request->codigoInscricao);
        $inscricao->alunoEspecial = $request->alunoEspecial;
        $inscricao->dataAlunoEspecial = $request->dataAlunoEspecial;
        $inscricao->save();


        request()->session()->flash('alert-success', 'Disciplina cadastrada com sucesso');    
        $voltar = "inscricao/{$request->codigoInscricao}/disciplina";

        return redirect($voltar); 
    }

    public function obrigatorio($codigoInscricao)
    {             
        $inscricao = Inscricao::obterObrigatorioInscricao($codigoInscricao, array(26, 27, 28, 1, 2, 4, 3, 5, 9, 6, 29, 30, 9, 31, 32, 33, 34, 35, 36, 37, 38));;

        $status    = Inscricao::obterStatusInscricao($codigoInscricao);
        $edital    = Inscricao::obterEditalInscricao($codigoInscricao);
      
        Utils::obterTotalInscricao($codigoInscricao);
        $total = Utils::obterTotalArquivos($codigoInscricao, array(26, 27, 28, 1, 2, 4, 3, 5, 9, 29, 30, 9, 31, 32, 33, 34, 35, 36, 37, 38));

        $voltar = "inscricao/{$edital}/disciplina";
    
        return view('obrigatorio',
        [
            'codigoInscricao'   => $codigoInscricao,
            'codigoEdital'      => $edital,
            'link_voltar'       => $voltar,
            'arquivos'          => $inscricao,
            'nivel'             => session(['nivel']),
            'total'             => $total,
            'status'            => $status,
            'count'             => count($inscricao),
        ]); 
    }
      

    public function requerimento($codigoInscricao)
    {         
        $codigoEdital = Inscricao::obterEditalInscricao($codigoInscricao);
        $inscricao    = Inscricao::obterRequerimentoInscricao($codigoInscricao);
      
        Utils::obterTotalInscricao($codigoInscricao);
        $total = Utils::obterTotalArquivos($codigoInscricao, array(27, 28, 1, 2, 4, 3, 29, 30, 9, 31, 32, 33, 34, 35, 36, 37, 38));

        $voltar = "inscricao/{$codigoEdital}/requerimento";
    
        return view('requerimento',
        [
            'codigoInscricao'   => $codigoInscricao,
            'codigoEdital'      => $codigoEdital,
            'link_voltar'       => $voltar,
            'requerimento'      => $inscricao,
            'total'             => $total,
            'nivel'             => session(['nivel']),
        ]); 
    } 

    public function requerimento_store(\App\Models\Comprovante $pdf, Request $request)
    { 
        $codigoEdital = Inscricao::obterEditalInscricao($request->codigoInscricao);
        $edital       = Edital::join('niveis', 'editais.codigoNivel', '=', 'niveis.codigoNivel')
                              ->join('users', 'editais.codigoUsuario', '=', 'users.id')
                              ->where('editais.codigoEdital', $codigoEdital)->first();

        /*if ($request->codigoTipo == 'ppgem')
        {
            $path = $request->file('arquivo')->store('arquivos', 'public');

            $arquivo = Arquivo::create([
                'codigoUsuario'         => Auth::user()->id,
                'codigoTipoDocumento'   => $request->codigoTipoDocumento,
                'linkArquivo'           => $path,
                'codigoPessoaAlteracao' => Auth::user()->codpes,
            ]);
    
            $inscricaoDocumentos = InscricoesArquivos::create([
                'codigoInscricao'       => $request->codigoInscricao,
                'codigoArquivo'         => $arquivo->codigoArquivo,
                'codigoPessoaAlteracao' => Auth::user()->codpes,
            ]);
        }
        else if ($request->codigoTipo == 'ppgpe')
        {
            $pessoais     = Inscricao::obterDadosPessoaisInscricao($request->codigoInscricao);
            $foto         = Inscricao::obterFotoInscricao($request->codigoInscricao);
            $sigla        = Utils::obterSiglaCurso($edital->codigoCurso);
            $anosemestre  = Edital::obterSemestreAno($pessoais->codigoEdital, true);
            $arquivo      = Inscricao::obterRequerimentoInscricao($request->codigoInscricao);
            $nivel        = Edital::obterNivelEdital($pessoais->codigoEdital);
    
            $pdf->setCabecalho($sigla);
          
            $pdf->SetDisplayMode('real');
            $pdf->AliasNbPages();
            $pdf->AddPage();
            $pdf->SetFont('Arial','B', 16);
            $pdf->SetFillColor(190,190,190);
            $pdf->MultiCell(190, 8, utf8_decode($pdf->obterTitulo($edital->siglaNivel, $edital->codigoCurso, $sigla)), 1, 'C', true);            
            $pdf->Ln(2);
    
            $pdf->SetFont('Arial','B', 10);    
            $pdf->Cell(140, 8, utf8_decode('NÚMERO DE INSCRIÇÃO'), 1, 0, 'R', true);
            $pdf->SetFont('Arial', '', 10);
            $pdf->SetFillColor(255,255,255);
            $pdf->Cell(50, 8, $pessoais->numeroInscricao . ' - '.$anosemestre, 1, 0, 'L', true);
            $pdf->Ln();
    
            if ($edital->codigoNivel == 1)
            {
                $eixoy    = 71;            
                $eixofoto = 63; 
            }
            else
            {
                $eixoy    = 79;
                $eixofoto = 71;
            }
    
            $pdf->SetFont('Arial','B', 10);
            $pdf->SetFillColor(190,190,190);
            $pdf->Cell(10, 8, utf8_decode('1.'), 1, 0, 'L', true);
            $pdf->Cell(130, 8, utf8_decode('DADOS PESSOAIS:'), '1', 0, 'J', true);
            $pdf->Image(asset("storage/{$foto->linkArquivo}"), 156, $eixofoto, 37, 50); 
            $pdf->Cell(50, 50, utf8_decode(''), 1, 0, 'R');
            $pdf->SetFont('Arial', 'B', 10);
      
            $pdf->SetY($eixoy);
            $pdf->Cell(13,  8, utf8_decode('Nome:'), 'LB',  0, 'C', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(127, 8, utf8_decode($pessoais->name), 'B',  0, 'L', false);
            $eixoy = $eixoy + 8;
    
            $pdf->SetY($eixoy);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(12, 8, utf8_decode('Sexo:'), 'L',  0, 'L', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(20, 8, utf8_decode($pessoais->sexoPessoal), 0,  0, 'L', false);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(23, 8, utf8_decode('Estado Civil:'), 0,  0, 'L', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(45, 8, utf8_decode($pessoais->estadoCivilPessoal), 0,  0, 'L', false);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(32, 8, utf8_decode('Nº Dependente(s):'), 0,  0, 'L', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(5, 8, utf8_decode($pessoais->dependentePessoal), 0,  0, 'L', false);
            $eixoy = $eixoy + 8;   
    
            $pdf->SetY($eixoy);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(12, 8, utf8_decode('CPF:'), 'L', 0, 'L', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(25, 8, $pessoais->cpf, 0, 0, 'L', false);
            
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(37, 8, utf8_decode('Data de Nascimento:'), 0,  0, 'L', false);    
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(20, 8, $pessoais->dataNascimentoPessoal->format('d/m/Y'), 0,  0, 'L', false);
            $eixoy = $eixoy + 8;
    
            $pais = Utils::obterPais($pessoais->paisPessoal);
            $localidade = Utils::obterLocalidade($pessoais->naturalidadePessoal);
    
            $pdf->SetY($eixoy);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(15, 8, utf8_decode('Cidade:'), 'L', 0, 'L', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(55, 8, utf8_decode($localidade["cidloc"]), 0,  0, 'L', false);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(23, 8, utf8_decode('Estado/País:'), 0,  0, 'L', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(40, 8, "{$localidade['sglest']}/{$pais['nompas']}", 0,  0, 'L', false);
            $eixoy = $eixoy + 8;
            
            $pdf->SetY($eixoy);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(20, 8, utf8_decode('Identidade:'), 'L',  0, 'L', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(25, 8, $pessoais->numeroRG, 0,  0, '', false);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(31, 8, utf8_decode('Data de Emissão:'), 0,  0, 'L', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(20, 8, $pessoais->dataEmissaoRG, 0,  0, 'L', false);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(28, 8, utf8_decode('Orgão Emissor:'), 0,  0, 'L', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(50, 8, $pessoais->orgaoEmissorRG, 0,  0, 'L', false);
            $eixoy = $eixoy + 8;
    
            if ($pessoais->especialPessoal == 'S')
            {
                $tipos = str_replace('|', ', ', $pessoais->tipoEspecialPessoal);
                $necessidades = utf8_decode("Sim - {$tipos}");
            }
            else
            {
                $necessidades = utf8_decode('Não');
            }
    
            $pdf->SetY($eixoy);
            $pdf->Cell(140, 2, '', 'LB',  0, 'L', false);
            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(20, 8, utf8_decode('Raça/Cor:'), 'LB',  0, 'L', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(30, 8, utf8_decode($pessoais->racaPessoal), 'B',  0, 'L', false);
            $pdf->SetFont('Arial', 'B', 10);        
            $pdf->Cell(70, 8, utf8_decode('É portador de Necessidades Especiais?'), 'LB',  0, 'L', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(70, 8, $necessidades, 'BR',  0, 'L', false);
            
            if ((substr($pessoais->codpes, 0, 2) == 88))
            {
                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(13, 8, utf8_decode('E-mail:'), 'LB',  0, 'L', false);
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(177, 8, $pessoais->email, 'BR',  0, 'L', false);
            }
            else
            {
                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(13, 8, utf8_decode('E-mail:'), 'LB',  0, 'L', false);
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(100, 8, $pessoais->email, 'BR',  0, 'L', false);
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(25, 8, utf8_decode('Número USP:'), 'LB',  0, 'L', false);
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(52, 8, $pessoais->codpes, 'BR',  0, 'L', false);
            }
    
            $enderecos = Inscricao::obterEnderecoInscricao($request->codigoInscricao);
    
            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(18, 8, utf8_decode('Endereço:'), 'LB',  0, 'L', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(172, 8, utf8_decode("{$enderecos->logradouroEndereco}, {$enderecos->numeroEndereco} {$enderecos->complementoEndereco}"), 'BR',  0, 'L', false);
    
            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(13, 8, utf8_decode('Bairro:'), 'LB',  0, 'L', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(60, 8, utf8_decode($enderecos->bairroEndereco), 'B',  0, 'L', false);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(17, 8, utf8_decode('Telefone:'), 'B',  0, 'L', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(100, 8, $pessoais->telefone, 'BR',  0, 'L', false);
    
            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(9, 8, utf8_decode('CEP:'), 'LB',  0, 'L', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(46, 8, $enderecos->cepEndereco, 'B',  0, 'L', false);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(18, 8, utf8_decode('Cidade:'), 'B',  0, 'R', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(52, 8, utf8_decode($enderecos->localidadeEndereco), 'B',  0, 'L', false);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(25, 8, utf8_decode('Estado:'), 'B',  0, 'R', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(40, 8, $enderecos->ufEndereco, 'BR',  0, 'L', false);
    
            if ($nivel == 'AE')
            {
                $temp = Inscricao::find($request->codigoInscricao);
    
                if ($temp->alunoEspecial == 'S')
                {
                    $cursou = 'Sim - '.$temp->dataAlunoEspecial;
                }
                else
                {
                    $cursou = utf8_decode('Não');
                }
    
                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(80, 8, utf8_decode('Já cursou Disciplina como Aluno Especial? '), 'LB',  0, 'L', false);
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(110, 8, $cursou, 'BR',  0, 'L', false);    
            }
    
            $pdf->Ln();
            $pdf->SetFont("Arial","B", 10);
            $pdf->Cell(10, 8, utf8_decode("2."), 1, 0, "L", true);
            $pdf->Cell(180, 8, utf8_decode("PESSOA A SER NOTIFICADA EM CASO DE EMERGÊNCIA:"), "1", 0, "J", true);
    
            $emergencias = Inscricao::obterEmergenciaInscricao($request->codigoInscricao);
            $endereco = Endereco::find($emergencias->codigoEmergenciaEndereco);
    
            $pdf->Ln();
            $pdf->SetFont("Arial", "B", 10);
            $pdf->Cell(13, 8, utf8_decode("Nome:"), "LB",  0, "L", false);
            $pdf->SetFont("Arial", "", 10);
            $pdf->Cell(177, 8, utf8_decode($emergencias->nomePessoaEmergencia), "BR",  0, "L", false);
    
            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(18, 8, utf8_decode('Endereço:'), 'LB',  0, 'L', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(172, 8, utf8_decode("{$endereco->logradouroEndereco}, {$endereco->numeroEndereco} {$endereco->complementoEndereco}"), 'BR',  0, 'L', false);
    
            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(13, 8, utf8_decode('Bairro:'), 'LB',  0, 'L', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(60, 8, utf8_decode($endereco->bairroEndereco), 'B',  0, 'L', false);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(17, 8, utf8_decode('Telefone:'), 'B',  0, 'L', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(100, 8, $emergencias->telefonePessoaEmergencia, 'BR',  0, 'L', false);
    
            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(9, 8, utf8_decode('CEP:'), 'LB',  0, 'L', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(46, 8, $endereco->cepEndereco, 'B',  0, 'L', false);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(18, 8, utf8_decode('Cidade:'), 'B',  0, 'R', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(52, 8, utf8_decode($endereco->localidadeEndereco), 'B',  0, 'L', false);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(25, 8, utf8_decode('Estado:'), 'B',  0, 'R', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(40, 8, $endereco->ufEndereco, 'BR',  0, 'L', false);
    
            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(10, 8, utf8_decode('3.'), 1, 0, 'L', true);
            $pdf->Cell(180, 8, utf8_decode('RESUMO ESCOLAR'), '1', 0, 'J', true);
    
            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(70, 8, utf8_decode('ESCOLA'), 1, 0, 'C', false);
            $pdf->Cell(70, 8, utf8_decode('TÍTULO/ESPECIALIDADE'), 1, 0, 'C', false);    
            $pdf->Cell(25, 8, utf8_decode('INÍCIO'), 1, 0, 'C', false);
            $pdf->Cell(25, 8, utf8_decode('FIM'), 1, 0, 'C', false);
            
            $pdf->SetFont('Arial', '', 10);
    
            $escolares = Inscricao::obterEscolarInscricao($request->codigoInscricao);
    
            foreach($escolares as $escolar)
            {
                $pdf->Ln();
                $pdf->CellFitScale(70, 8, utf8_decode($escolar->escolaResumoEscolar), 1, 0, 'J', false);
                $pdf->CellFitScale(70, 8, utf8_decode($escolar->especialidadeResumoEscolar), 1, 0, 'J', false);
                $pdf->CellFitScale(25, 8, $escolar->inicioResumoEscolar->format('m/Y'), 1, 0, 'C', false);
                            
                if ($escolar->finalResumoEscolar == '')
                {
                    $pdf->Cell(25, 8, '-', 1, 0, 'C', false);        
                }
                else
                {
                    $pdf->CellFitScale(25, 8, $escolar->finalResumoEscolar->format('m/Y'), 1, 0, 'C', false);            
                }
            }
    
            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(10, 8, utf8_decode('4.'), 1, 0, 'L', true);
            $pdf->Cell(180, 8, utf8_decode('CONHECIMENTO DE IDIOMAS ESTRANGEIROS'), 1, 0, 'J', true);
    
            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(70, 8, utf8_decode('IDIOMA'), 1, 0, 'C', false);
            $pdf->Cell(40, 8, utf8_decode('LEITURA'), 1, 0, 'C', false);
            $pdf->Cell(40, 8, utf8_decode('REDAÇÃO'), 1, 0, 'C', false);
            $pdf->Cell(40, 8, utf8_decode('CONVERSAÇÃO'), 1, 0, 'C', false);
    
            $idiomas = Inscricao::obterIdiomaInscricao($request->codigoInscricao);
    
            foreach($idiomas as $idioma)
            {
                $pdf->Ln();
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(70, 8, utf8_decode($idioma->descricaoIdioma), 1, 0, 'C', false);
                $pdf->Cell(40, 8, utf8_decode($idioma->leituraIdioma), 1, 0, 'C', false);
                $pdf->Cell(40, 8, utf8_decode($idioma->redacaoIdioma), 1, 0, 'C', false);
                $pdf->Cell(40, 8, utf8_decode($idioma->conversacaoIdioma), 1, 0, 'C', false);
            }
    
            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(10, 8, utf8_decode('5.'), 1, 0, 'L', true);
            $pdf->Cell(180, 8, utf8_decode('EXPERIÊNCIA PROFISSIONAL'), '1', 0, 'J', true);
    
            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(70, 8, utf8_decode('ENTIDADE'), 1, 0, 'C', false);
            $pdf->Cell(70, 8, utf8_decode('POSIÇÃO OCUPADA'), 1, 0, 'C', false);
            $pdf->Cell(25, 8, utf8_decode('INÍCIO'), 1, 0, 'C', false);
            $pdf->Cell(25, 8, utf8_decode('FIM'), 1, 0, 'C', false);            
            $pdf->SetFont('Arial', '', 10);
    
            $profissionais = Inscricao::obterProfissionalInscricao($request->codigoInscricao);
    
            if (!empty($profissionais))
            {
                foreach($profissionais as $profissional)
                {   
                    $pdf->Ln();
                    $pdf->CellFitScale(70, 8, utf8_decode($profissional->entidadeExperiencia), 1, 0, 'J', false);
                    $pdf->CellFitScale(70, 8, utf8_decode($profissional->posicaoExperiencia), 1, 0, 'J', false);    
                    $pdf->CellFitScale(25, 8, $profissional->inicioExperiencia->format('m/Y'), 1, 0, 'C', false);
                   
                    if ($profissional->finalExperiencia == '')
                    {
                        $pdf->Cell(25, 8, '-', 1, 0, 'C', false);        
                    }
                    else
                    {
                        $pdf->CellFitScale(25, 8, $profissional->finalExperiencia->format('m/Y'), 1, 0, 'C', false);            
                    }
                }
            }
    
            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(10, 8, utf8_decode('6.'), 1, 0, 'L', true);
            $pdf->Cell(180, 8, utf8_decode('EXPERIÊNCIA EM ENSINO'), '1', 0, 'J', true);
    
            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(70, 8, utf8_decode('ENTIDADE'), 1, 0, 'C', false);
            $pdf->Cell(70, 8, utf8_decode('POSIÇÃO OCUPADA'), 1, 0, 'C', false);
            $pdf->Cell(25, 8, utf8_decode('INÍCIO'), 1, 0, 'C', false);
            $pdf->Cell(25, 8, utf8_decode('FIM'), 1, 0, 'C', false);
            $pdf->SetFont('Arial', '', 10);
    
            $ensinos = Inscricao::obterEnsinoInscricao($request->codigoInscricao);
    
            if (!empty($ensinos))
            {
                foreach($ensinos as $ensino)
                {   
                    $pdf->Ln();
                    $pdf->CellFitScale(70, 8, utf8_decode($ensino->entidadeExperiencia), 1, 0, 'J', false);
                    $pdf->CellFitScale(70, 8, utf8_decode($ensino->posicaoExperiencia), 1, 0, 'J', false);    
                    $pdf->CellFitScale(25, 8, $ensino->inicioExperiencia->format('m/Y'), 1, 0, 'C', false);    
    
                    if ($ensino->finalExperiencia == '')
                    {
                        $pdf->Cell(25, 8, '-', 1, 0, 'C', false);        
                    }
                    else
                    {
                        $pdf->CellFitScale(25, 8, $ensino->finalExperiencia->format('m/Y'), 1, 0, 'C', false);            
                    }
                }
            }
    
            if ($nivel == 'AE')
            {
                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(10, 8, utf8_decode('7.'), 1, 0, 'L', true);
                $pdf->Cell(180, 8, utf8_decode("POR QUE CURSAR DISCIPLINA COMO ALUNO ESPECIAL?"), '1', 0, 'J', true);
    
                $expectativas = Inscricao::obterExpectativaInscricao($request->codigoInscricao);
        
                $pdf->Ln();
                $pdf->SetFont("Arial","", 10);
                $pdf->MultiCell(190, 8, utf8_decode($expectativas->expectativasInscricao), 1, "J", false); 
    
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(10, 8, utf8_decode('8.'), 1, 0, 'L', true);
                $pdf->Cell(180, 8, utf8_decode('QUAL DISCIPLINA QUER CURSAR COMO ALUNO ESPECIAL?'), 1, 0, 'J', true);         
                $pdf->SetFont('Arial', '', 10);  
    
                $inscricao = Inscricao::obterDisciplinaInscricao($request->codigoInscricao);
    
                foreach($inscricao as $disciplina)
                {
                    $temp = Posgraduacao::disciplina($disciplina->codigoDisciplina);
    
                    $pdf->Ln();
                    $pdf->CellFitScale(190, 8, utf8_decode($temp['sgldis'].'-'.$temp['numseqdis'].' - '.$temp['nomdis'].' ('.$temp['numcretotdis'].' créditos)'), 1, 0, 'J', false);
                } 
    
                $pdf->Ln(); 
            }
            else
            {
                $pdf->Ln();
                $pdf->SetFont("Arial","B", 10);
                $pdf->Cell(10, 8, utf8_decode("7."), 1, 0, "L", true);
                $pdf->Cell(180, 8, utf8_decode("RECURSOS FINANCEIROS"), "1", 0, "J", true);
            
                $pdf->Ln();
                $pdf->SetFont("Arial","", 10);
                $pdf->Cell(80, 8, utf8_decode('Possui bolsa de estudos de alguma instituição?'), "L", 0, "L");
        
                $financeiros = Inscricao::obterFinanceiroInscricao($request->codigoInscricao);
           
                if ($financeiros->bolsaRecursoFinanceiro == 'S')
                {
                    $bolsa = true;
                    $pdf->Cell(110, 8, utf8_decode("SIM ( X )   NÃO (  )"), "R", 0, "J");
                    $pdf->Ln();
            
                    $pdf->Cell(100, 8, utf8_decode("- Nome do órgão financiador: ".$financeiros->orgaoRecursoFinanceiro), "L", 0, "L");
                    $pdf->Cell(90, 8, utf8_decode("- Tipo de Bolsa: ".$financeiros->tipoBolsaFinanceiro), "R", 0, "L");
                    $pdf->Ln();
                    $pdf->Cell(190, 8, utf8_decode("- Período de vigência (mês/ano):  de ".date('m/Y', strtotime($financeiros->inicioRecursoFinanceiro))." a ".date('m/Y', strtotime($financeiros->finalRecursoFinanceiro))), "LR", 0, "L");
                    $pdf->Ln();
            
                    $pdf->Cell(190, 8, "", "LR", 0, "L");
                    $pdf->Ln();
                }
                else
                {
                    $solicitar = ($financeiros->solicitarRecursoFinanceiro == 'S') ? 'Sim' : 'Não';
        
                    $pdf->Cell(110, 8, utf8_decode("SIM (  )   NÃO ( X )"), "R", 0, "J");
                    $pdf->Ln();
            
                    $pdf->Cell(190, 8, utf8_decode("- Deseja solicitar bolsa? {$solicitar}"), "LR", 0, "L");
                    $pdf->Ln();
            
                    $pdf->Cell(190, 8, "", "LR", 0, "L");
                    $pdf->Ln();
                }   
        
                $pdf->SetFont("Arial","B", 10);
                $pdf->MultiCell(190, 8, utf8_decode("Obs.: As bolsas da CAPES e do CNPq são concedidas competitivamente em número limitado. Não é permitido ao bolsista acumular bolsas ou ter vínculo empregatício com qualquer instituição ou empresa."), "LRB", "J", false);
                $pdf->SetFont("Arial","B", 10);
                $pdf->Cell(10, 8, utf8_decode("8."), 1, 0, "L", true);
                $pdf->Cell(180, 8, utf8_decode("QUAIS AS SUAS EXPECTATIVAS COM RELAÇÃO AO CURSO ?"), "1", 0, "J", true);
        
                $expectativas = Inscricao::obterExpectativaInscricao($request->codigoInscricao);
        
                $pdf->Ln();
                $pdf->SetFont("Arial","", 10);
                $pdf->MultiCell(190, 8, utf8_decode($expectativas->expectativasInscricao), 1, "J", false);
            }
    
            if ($nivel == 'ME')
            {            
                if ($edital->dataDoeEdital->format('m') < 7)
                {
                    $semestre  = 'segundo semestre de '.$edital->dataDoeEdital->format('Y');
                    $diretorio =  $edital->dataDoeEdital->format('Y').'2';
                }
                else
                {
                    $ano       = $edital->dataDoeEdital->format('Y') + 1;
                    $semestre  = 'primero semestre de '.$ano;
                    $diretorio = $ano.'1'; 
                }
    
                $assunto      = "MESTRADO - {$sigla}";
                $curso        = 'Seleção do Curso de Mestrado para ingresso no '.$semestre;
                $requerimento = 'Venho requerer minha inscrição para '.$curso.' conforme regulamenta o edital '.$sigla.' Nº '.$anosemestre.' (DOESP de '.$edital->dataDoeEdital->format('d/m/Y').').';
            }
    
            /*if ($nivel == 'AE')
            {         
                if ($edital->dataFinalEdital->format('m') < 7)
                {
                    $semestre  = 'primero semestre de '.$edital->dataFinalEdital->format('Y');
                    $diretorio = $edital->dataFinalEdital->format('Y').'1'; 
                }
                else
                {                
                    $semestre  = 'segundo semestre de '.$edital->dataFinalEdital->format('Y');
                    $diretorio =  $edital->dataFinalEdital->format('Y').'2';
                }
                
                $assunto      = "MESTRADO - {$sigla}";
                $curso        = 'Seleção do Curso de Mestrado para ingresso no '.$semestre;
                $requerimento = 'Venho requerer minha inscrição para aluno especial conforme regulamenta o edital '.$sigla.' Nº '.$anosemestre.'.';
            }

            if ($nivel == 'DF')
            {            
                $diretorio = $ano;
    
                $assunto      = "DOUTORADO FLUXO CONTINUO - {$sigla}";
                $curso        = 'Seleção do Curso de Doutorado Fluxo Contínuo para ingresso em '.$ano;
                $requerimento = 'Venho requerer minha inscrição para '.$curso.' conforme regulamenta o edital '.$sigla.' Nº '.$anosemestre.' (DOESP de '.$edital->dataDoeEdital->format('d/m/Y').').';
            }
    
            $pdf->SetFont('Arial', '', 10);
            $pdf->MultiCell(190, 8, utf8_decode($requerimento), 'LR', 'J', false);
            $pdf->Cell(190, 8, utf8_decode(''), 'LR', 0, 'L', false);
            $pdf->Ln();
            $pdf->Cell(140, 8, 'Assinatura do candidato:', 'LB', 0, 'L', false);
            $pdf->Cell(50, 8, 'Data:         /         /', 'BR', 0, 'L', false);
    
            $sigla   = Str::lower($sigla);
            $arquivo = storage_path("app/public/{$sigla}/comprovante/{$diretorio}/{$pessoais->numeroInscricao}.pdf");
            $nome    = "{$sigla}/comprovante/{$diretorio}/{$pessoais->numeroInscricao}.pdf";
    
            if (!file_exists($arquivo))
            {
                $pdf->Output('F', $arquivo);
    
                if (file_exists($arquivo))
                {
                    $arquivo = Arquivo::create([
                        'codigoUsuario'         => Auth::user()->id,
                        'codigoTipoDocumento'   => 26,
                        'linkArquivo'           => $nome,
                        'codigoPessoaAlteracao' => Auth::user()->codpes,
                    ]);
            
                    $inscricaoDocumentos = InscricoesArquivos::create([
                        'codigoInscricao'       => $request->codigoInscricao,
                        'codigoArquivo'         => $arquivo->codigoArquivo,
                        'codigoPessoaAlteracao' => Auth::user()->codpes,
                    ]);
                }
            }
        }*/

        Mail::to($edital->email)->send(new ComprovanteMail($request->codigoInscricao));
            
        if (Mail::failures()) 
        {
            request()->session()->flash('alert-danger', "Ocorreu um erro no cadastrado do Requerimento.");
        }    
        else
        {
            Inscricao::where('codigoInscricao', $request->codigoInscricao)->where('statusInscricao', 'N')->update(['statusInscricao' => 'P']);

            request()->session()->flash('alert-success', 'Requerimento cadastrado com sucesso'); 
        } 

        /*$path = $request->file('arquivo')->store('arquivos', 'public');

        $arquivo = Arquivo::create([
            'codigoUsuario'         => Auth::user()->id,
            'codigoTipoDocumento'   => $request->codigoTipoDocumento,
            'linkArquivo'           => $path,
            'codigoPessoaAlteracao' => Auth::user()->codpes,
        ]);

        $inscricaoDocumentos = InscricoesArquivos::create([
            'codigoInscricao'       => $request->codigoInscricao,
            'codigoArquivo'         => $arquivo->codigoArquivo,
            'codigoPessoaAlteracao' => Auth::user()->codpes,
        ]);
       
        Mail::to($edital->email)->send(new ComprovanteMail($request->codigoInscricao));
        
        if (Mail::failures()) 
        {
            request()->session()->flash('alert-danger', "Ocorreu um erro no cadastrado do Requerimento.");
        }    
        else
        {
            Inscricao::where('codigoInscricao', $request->codigoInscricao)->where('statusInscricao', 'N')->update(['statusInscricao' => 'P']);

            request()->session()->flash('alert-success', 'Requerimento cadastrado com sucesso'); 
        } 
    
        if ($request->codigoTipo == 'ppgem')
        {
            return redirect("inscricao/{$request->codigoInscricao}/requerimento"); 
        }
        else
        {
            return redirect("inscricao/{$edital->codigoEdital}"); 
        }*/

        return redirect("inscricao/{$edital->codigoEdital}"); 
    }
    
    public function bolsista($codigoInscricao)
    {         
        $codigoEdital = Inscricao::obterEditalInscricao($codigoInscricao);
           
        Utils::obterTotalInscricao($codigoInscricao);
        $total = Utils::obterTotalArquivos($codigoInscricao, array(27, 28, 1, 2, 4, 3, 29, 30, 9, 31, 32, 33, 34, 35, 36, 37, 38));;

        $voltar = "inscricao/{$codigoEdital}";
    
        return view('inscricao.bolsista',
        [
            'codigoInscricao'   => $codigoInscricao,
            'codigoEdital'      => $codigoEdital,
            'link_voltar'       => $voltar,
            //'requerimento'      => $inscricao,
            'total'             => $total,
            'nivel'             => session(['nivel']),
        ]); 
    } 

    public static function comprovante(\App\Models\Comprovante $pdf, $codigoInscricao, $especial = 'N')
    {
        /*$editais = Edital::join('inscricoes', 'editais.codigoEdital', '=', 'inscricoes.codigoEdital')        
                         ->where('editais.dataFinalEdital', '>', Carbon::now())
                         ->where('inscricoes.codigoInscricao', $codigoInscricao)->count();

        if($editais == 0)
        {
            $item = array();
            $item['title'] = 'Aviso';
            $item['story'] = 'Inscricão Encerrada';

            return view('components.modal',
            [
                'item' => $item,                
            ]);
        }*/

        $pessoais     = Inscricao::obterDadosPessoaisInscricao($codigoInscricao);
        $edital       = Edital::join('niveis', 'editais.codigoNivel', '=', 'niveis.codigoNivel')->where('editais.codigoEdital', $pessoais->codigoEdital)->first();
        $foto         = Inscricao::obterFotoInscricao($codigoInscricao);
        $sigla        = Utils::obterSiglaCurso($edital->codigoCurso);
        $anosemestre  = Edital::obterSemestreAno($pessoais->codigoEdital, true);
        $arquivo      = Inscricao::obterRequerimentoInscricao($codigoInscricao);
        $nivel        = Edital::obterNivelEdital($pessoais->codigoEdital);

        $pdf->setCabecalho($sigla);
      
        $pdf->SetDisplayMode('real');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial','B', 16);
        $pdf->SetFillColor(190,190,190);
        $pdf->MultiCell(190, 8, utf8_decode($pdf->obterTitulo($edital->siglaNivel, $edital->codigoCurso, $sigla)), 1, 'C', true);            
        $pdf->Ln(2);

        $pdf->SetFont('Arial','B', 10);    
        $pdf->Cell(140, 8, utf8_decode('NÚMERO DE INSCRIÇÃO'), 1, 0, 'R', true);
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetFillColor(255,255,255);
        $pdf->Cell(50, 8, $pessoais->numeroInscricao . ' - '.$anosemestre, 1, 0, 'L', true);
        $pdf->Ln();

        if ($edital->codigoNivel == 1)
        {
            $eixoy    = 71;            
            $eixofoto = 63; 
        }
        else
        {
            $eixoy    = 79;
            $eixofoto = 71;
        }

        if ($nivel == 'DF')
        {
            $pdf->SetFont('Arial','B', 10);
            $pdf->SetFillColor(190,190,190);
            $pdf->Cell(10, 8, utf8_decode('1.'), 1, 0, 'L', true);
            $pdf->Cell(130, 8, utf8_decode('DADOS PESSOAIS:'), '1', 0, 'J', true);
            $pdf->Image(asset("storage/{$foto->linkArquivo}"), 156, $eixofoto, 37, 50); 
            $pdf->Cell(50, 50, utf8_decode(''), 1, 0, 'R');
            $pdf->SetFont('Arial', 'B', 10);
    
            $pdf->SetY($eixoy);
            $pdf->Cell(13,  8, utf8_decode('Nome:'), 'LB',  0, 'C', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(127, 8, utf8_decode($pessoais->name), 'B',  0, 'L', false);
            $eixoy = $eixoy + 8;

            $pdf->SetY($eixoy);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(12, 8, utf8_decode('Sexo:'), 'L',  0, 'L', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(53, 8, utf8_decode($pessoais->sexoPessoal), 0,  0, 'L', false);
            $pdf->SetFont('Arial', 'B', 10);
            /*$pdf->Cell(23, 8, utf8_decode('Estado Civil:'), 0,  0, 'L', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(45, 8, utf8_decode($pessoais->estadoCivilPessoal), 0,  0, 'L', false);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(32, 8, utf8_decode('Nº Dependente(s):'), 0,  0, 'L', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(5, 8, utf8_decode($pessoais->dependentePessoal), 0,  0, 'L', false);*/
            $eixoy = $eixoy + 8;   

            $pdf->SetY($eixoy);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(12, 8, utf8_decode('CPF:'), 'L', 0, 'L', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(25, 8, $pessoais->cpf, 0, 0, 'L', false);
            
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(37, 8, utf8_decode('Data de Nascimento:'), 0,  0, 'L', false);    
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(20, 8, $pessoais->dataNascimentoPessoal->format('d/m/Y'), 0,  0, 'L', false);
            $eixoy = $eixoy + 8;

            $pais = Utils::obterPais($pessoais->paisPessoal);
            $localidade = Utils::obterLocalidade($pessoais->naturalidadePessoal);

            $pdf->SetY($eixoy);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(15, 8, utf8_decode('Cidade:'), 'L', 0, 'L', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(55, 8, utf8_decode($localidade["cidloc"]), 0,  0, 'L', false);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(23, 8, utf8_decode('Estado/País:'), 0,  0, 'L', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(40, 8, "{$localidade['sglest']}/{$pais['nompas']}", 0,  0, 'L', false);
            $eixoy = $eixoy + 8;
            
            $pdf->SetY($eixoy);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(20, 8, utf8_decode('Identidade:'), 'L',  0, 'L', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(25, 8, $pessoais->numeroDocumento, 0,  0, '', false);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(31, 8, utf8_decode('Data de Emissão:'), 0,  0, 'L', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(20, 8, $pessoais->dataEmissaoRG, 0,  0, 'L', false);
            $pdf->SetFont('Arial', 'B', 10);
            /*$pdf->Cell(28, 8, utf8_decode('Orgão Emissor:'), 0,  0, 'L', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(50, 8, $pessoais->orgaoEmissorRG, 0,  0, 'L', false);*/
            $eixoy = $eixoy + 8;

            if ($pessoais->especialPessoal == 'S')
            {
                $tipos = str_replace('|', ', ', $pessoais->tipoEspecialPessoal);
                $necessidades = utf8_decode("Sim - {$tipos}");
            }
            else
            {
                $necessidades = utf8_decode('Não');
            }

            $pdf->SetY($eixoy);
            $pdf->Cell(140, 2, '', 'LB',  0, 'L', false);
            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(20, 8, utf8_decode('Raça/Cor:'), 'LB',  0, 'L', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(30, 8, utf8_decode($pessoais->racaPessoal), 'B',  0, 'L', false);
            $pdf->SetFont('Arial', 'B', 10);        
            $pdf->Cell(70, 8, utf8_decode('É portador de Necessidades Especiais?'), 'LB',  0, 'L', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(70, 8, $necessidades, 'BR',  0, 'L', false);
            
            if ((substr($pessoais->codpes, 0, 2) == 88))
            {
                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(13, 8, utf8_decode('E-mail:'), 'LB',  0, 'L', false);
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(177, 8, $pessoais->email, 'BR',  0, 'L', false);
            }
            else
            {
                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(13, 8, utf8_decode('E-mail:'), 'LB',  0, 'L', false);
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(100, 8, $pessoais->email, 'BR',  0, 'L', false);
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(25, 8, utf8_decode('Número USP:'), 'LB',  0, 'L', false);
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(52, 8, $pessoais->codpes, 'BR',  0, 'L', false);
            }

            $enderecos = Inscricao::obterEnderecoInscricao($codigoInscricao);

            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(18, 8, utf8_decode('Endereço:'), 'LB',  0, 'L', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(172, 8, utf8_decode("{$enderecos->logradouroEndereco}, {$enderecos->numeroEndereco} {$enderecos->complementoEndereco}"), 'BR',  0, 'L', false);

            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(13, 8, utf8_decode('Bairro:'), 'LB',  0, 'L', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(60, 8, utf8_decode($enderecos->bairroEndereco), 'B',  0, 'L', false);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(17, 8, utf8_decode('Telefone:'), 'B',  0, 'L', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(100, 8, $pessoais->telefone, 'BR',  0, 'L', false);

            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(9, 8, utf8_decode('CEP:'), 'LB',  0, 'L', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(46, 8, $enderecos->cepEndereco, 'B',  0, 'L', false);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(18, 8, utf8_decode('Cidade:'), 'B',  0, 'R', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(52, 8, utf8_decode($enderecos->localidadeEndereco), 'B',  0, 'L', false);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(25, 8, utf8_decode('Estado:'), 'B',  0, 'R', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(40, 8, $enderecos->ufEndereco, 'BR',  0, 'L', false);

            if ($nivel == 'AE')
            {
                $temp = Inscricao::find($codigoInscricao);

                if ($temp->alunoEspecial == 'S')
                {
                    $cursou = 'Sim - '.$temp->dataAlunoEspecial;
                }
                else
                {
                    $cursou = utf8_decode('Não');
                }

                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(80, 8, utf8_decode('Já cursou Disciplina como Aluno Especial? '), 'LB',  0, 'L', false);
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(110, 8, $cursou, 'BR',  0, 'L', false);    
            }

            $pdf->Ln();
            $pdf->SetFont("Arial","B", 10);
            $pdf->Cell(10, 8, utf8_decode("2."), 1, 0, "L", true);
            $pdf->Cell(180, 8, utf8_decode("PESSOA A SER NOTIFICADA EM CASO DE EMERGÊNCIA:"), "1", 0, "J", true);

            $emergencias = Inscricao::obterEmergenciaInscricao($codigoInscricao);
            $endereco = Endereco::find($emergencias->codigoEmergenciaEndereco);

            $pdf->Ln();
            $pdf->SetFont("Arial", "B", 10);
            $pdf->Cell(13, 8, utf8_decode("Nome:"), "LB",  0, "L", false);
            $pdf->SetFont("Arial", "", 10);
            $pdf->Cell(177, 8, utf8_decode($emergencias->nomePessoaEmergencia), "BR",  0, "L", false);

            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(18, 8, utf8_decode('Endereço:'), 'LB',  0, 'L', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(172, 8, utf8_decode("{$endereco->logradouroEndereco}, {$endereco->numeroEndereco} {$endereco->complementoEndereco}"), 'BR',  0, 'L', false);

            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(13, 8, utf8_decode('Bairro:'), 'LB',  0, 'L', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(60, 8, utf8_decode($endereco->bairroEndereco), 'B',  0, 'L', false);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(17, 8, utf8_decode('Telefone:'), 'B',  0, 'L', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(100, 8, $emergencias->telefonePessoaEmergencia, 'BR',  0, 'L', false);

            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(9, 8, utf8_decode('CEP:'), 'LB',  0, 'L', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(46, 8, $endereco->cepEndereco, 'B',  0, 'L', false);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(18, 8, utf8_decode('Cidade:'), 'B',  0, 'R', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(52, 8, utf8_decode($endereco->localidadeEndereco), 'B',  0, 'L', false);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(25, 8, utf8_decode('Estado:'), 'B',  0, 'R', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(40, 8, $endereco->ufEndereco, 'BR',  0, 'L', false);

            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(10, 8, utf8_decode('3.'), 1, 0, 'L', true);
            $pdf->Cell(180, 8, utf8_decode('RESUMO ESCOLAR'), '1', 0, 'J', true);

            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(70, 8, utf8_decode('ESCOLA'), 1, 0, 'C', false);
            $pdf->Cell(70, 8, utf8_decode('TÍTULO/ESPECIALIDADE'), 1, 0, 'C', false);    
            $pdf->Cell(25, 8, utf8_decode('INÍCIO'), 1, 0, 'C', false);
            $pdf->Cell(25, 8, utf8_decode('FIM'), 1, 0, 'C', false);
            
            $pdf->SetFont('Arial', '', 10);

            $escolares = Inscricao::obterEscolarInscricao($codigoInscricao);

            foreach($escolares as $escolar)
            {
                $pdf->Ln();
                $pdf->CellFitScale(70, 8, utf8_decode($escolar->escolaResumoEscolar), 1, 0, 'J', false);
                $pdf->CellFitScale(70, 8, utf8_decode($escolar->especialidadeResumoEscolar), 1, 0, 'J', false);
                $pdf->CellFitScale(25, 8, $escolar->inicioResumoEscolar->format('m/Y'), 1, 0, 'C', false);
                            
                if ($escolar->finalResumoEscolar == '')
                {
                    $pdf->Cell(25, 8, '-', 1, 0, 'C', false);        
                }
                else
                {
                    $pdf->CellFitScale(25, 8, $escolar->finalResumoEscolar->format('m/Y'), 1, 0, 'C', false);            
                }
            }

            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(10, 8, utf8_decode('4.'), 1, 0, 'L', true);
            $pdf->Cell(180, 8, utf8_decode('CONHECIMENTO DE IDIOMAS ESTRANGEIROS'), 1, 0, 'J', true);

            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(70, 8, utf8_decode('IDIOMA'), 1, 0, 'C', false);
            $pdf->Cell(40, 8, utf8_decode('LEITURA'), 1, 0, 'C', false);
            $pdf->Cell(40, 8, utf8_decode('REDAÇÃO'), 1, 0, 'C', false);
            $pdf->Cell(40, 8, utf8_decode('CONVERSAÇÃO'), 1, 0, 'C', false);

            $idiomas = Inscricao::obterIdiomaInscricao($codigoInscricao);

            foreach($idiomas as $idioma)
            {
                $pdf->Ln();
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(70, 8, utf8_decode($idioma->descricaoIdioma), 1, 0, 'C', false);
                $pdf->Cell(40, 8, utf8_decode($idioma->leituraIdioma), 1, 0, 'C', false);
                $pdf->Cell(40, 8, utf8_decode($idioma->redacaoIdioma), 1, 0, 'C', false);
                $pdf->Cell(40, 8, utf8_decode($idioma->conversacaoIdioma), 1, 0, 'C', false);
            }

            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(10, 8, utf8_decode('5.'), 1, 0, 'L', true);
            $pdf->Cell(180, 8, utf8_decode('EXPERIÊNCIA PROFISSIONAL'), '1', 0, 'J', true);

            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(70, 8, utf8_decode('ENTIDADE'), 1, 0, 'C', false);
            $pdf->Cell(70, 8, utf8_decode('POSIÇÃO OCUPADA'), 1, 0, 'C', false);
            $pdf->Cell(25, 8, utf8_decode('INÍCIO'), 1, 0, 'C', false);
            $pdf->Cell(25, 8, utf8_decode('FIM'), 1, 0, 'C', false);            
            $pdf->SetFont('Arial', '', 10);

            $profissionais = Inscricao::obterProfissionalInscricao($codigoInscricao);

            if (!empty($profissionais->codigoExperiencia))
            {
                foreach($profissionais as $profissional)
                {   
                    $pdf->Ln();
                    $pdf->CellFitScale(70, 8, utf8_decode($profissional->entidadeExperiencia), 1, 0, 'J', false);
                    $pdf->CellFitScale(70, 8, utf8_decode($profissional->posicaoExperiencia), 1, 0, 'J', false);    
                    $pdf->CellFitScale(25, 8, $profissional->inicioExperiencia->format('m/Y'), 1, 0, 'C', false);
                
                    if ($profissional->finalExperiencia == '')
                    {
                        $pdf->Cell(25, 8, '-', 1, 0, 'C', false);        
                    }
                    else
                    {
                        $pdf->CellFitScale(25, 8, $profissional->finalExperiencia->format('m/Y'), 1, 0, 'C', false);            
                    }
                }
            }

            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(10, 8, utf8_decode('6.'), 1, 0, 'L', true);
            $pdf->Cell(180, 8, utf8_decode('EXPERIÊNCIA EM ENSINO'), '1', 0, 'J', true);

            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(70, 8, utf8_decode('ENTIDADE'), 1, 0, 'C', false);
            $pdf->Cell(70, 8, utf8_decode('POSIÇÃO OCUPADA'), 1, 0, 'C', false);
            $pdf->Cell(25, 8, utf8_decode('INÍCIO'), 1, 0, 'C', false);
            $pdf->Cell(25, 8, utf8_decode('FIM'), 1, 0, 'C', false);
            $pdf->SetFont('Arial', '', 10);

            $ensinos = Inscricao::obterEnsinoInscricao($codigoInscricao);

            if (!empty($ensinos))
            {
                foreach($ensinos as $ensino)
                {   
                    $pdf->Ln();
                    $pdf->CellFitScale(70, 8, utf8_decode($ensino->entidadeExperiencia), 1, 0, 'J', false);
                    $pdf->CellFitScale(70, 8, utf8_decode($ensino->posicaoExperiencia), 1, 0, 'J', false);    
                    $pdf->CellFitScale(25, 8, $ensino->inicioExperiencia->format('m/Y'), 1, 0, 'C', false);    

                    if ($ensino->finalExperiencia == '')
                    {
                        $pdf->Cell(25, 8, '-', 1, 0, 'C', false);        
                    }
                    else
                    {
                        $pdf->CellFitScale(25, 8, $ensino->finalExperiencia->format('m/Y'), 1, 0, 'C', false);            
                    }
                }
            }

            if ($nivel == 'AE')
            {        
                $pdf->Ln();
                $pdf->SetFont("Arial","", 10);
                $pdf->MultiCell(190, 8, utf8_decode($expectativas->expectativasInscricao), 1, "J", false); 

                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(10, 8, utf8_decode('3.'), 1, 0, 'L', true);
                $pdf->Cell(180, 8, utf8_decode('QUAL DISCIPLINA QUER CURSAR COMO ALUNO ESPECIAL?'), 1, 0, 'J', true);         
                $pdf->SetFont('Arial', '', 10);  

                $inscricao = Inscricao::obterDisciplinaInscricao($codigoInscricao);

                foreach($inscricao as $disciplina)
                {
                    $temp = Posgraduacao::disciplina($disciplina->codigoDisciplina);

                    $pdf->Ln();
                    $pdf->CellFitScale(190, 8, utf8_decode($temp['sgldis'].'-'.$temp['numseqdis'].' - '.$temp['nomdis'].' ('.$temp['numcretotdis'].' créditos)'), 1, 0, 'J', false);
                } 

                if ($inscricao->codigoCurso == 97004)
                {
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', 10);
                    $pdf->Cell(10, 8, utf8_decode('4.'), 1, 0, 'L', true);
                    $pdf->Cell(180, 8, utf8_decode("POR QUE CURSAR DISCIPLINA COMO ALUNO ESPECIAL?"), '1', 0, 'J', true);
    
                    $expectativas = Inscricao::obterExpectativaInscricao($codigoInscricao);
    
                    $pdf->Ln();
                } 
            }
            else
            {
                $pdf->Ln();
                $pdf->SetFont("Arial","B", 10);
                $pdf->Cell(10, 8, utf8_decode("7."), 1, 0, "L", true);
                $pdf->Cell(180, 8, utf8_decode("RECURSOS FINANCEIROS"), "1", 0, "J", true);
            
                $pdf->Ln();
                $pdf->SetFont("Arial","", 10);
                $pdf->Cell(80, 8, utf8_decode('Possui bolsa de estudos de alguma instituição?'), "L", 0, "L");
        
                $financeiros = Inscricao::obterFinanceiroInscricao($codigoInscricao);
        
                if ($financeiros->bolsaRecursoFinanceiro == 'S')
                {
                    $bolsa = true;
                    $pdf->Cell(110, 8, utf8_decode("SIM ( X )   NÃO (  )"), "R", 0, "J");
                    $pdf->Ln();
            
                    $pdf->Cell(100, 8, utf8_decode("- Nome do órgão financiador: ".$financeiros->orgaoRecursoFinanceiro), "L", 0, "L");
                    $pdf->Cell(90, 8, utf8_decode("- Tipo de Bolsa: ".$financeiros->tipoBolsaFinanceiro), "R", 0, "L");
                    $pdf->Ln();
                    $pdf->Cell(190, 8, utf8_decode("- Período de vigência (mês/ano):  de ".date('m/Y', strtotime($financeiros->inicioRecursoFinanceiro))." a ".date('m/Y', strtotime($financeiros->finalRecursoFinanceiro))), "LR", 0, "L");
                    $pdf->Ln();
            
                    $pdf->Cell(190, 8, "", "LR", 0, "L");
                    $pdf->Ln();
                }
                else
                {
                    $solicitar = ($financeiros->solicitarRecursoFinanceiro == 'S') ? 'Sim' : 'Não';
        
                    $pdf->Cell(110, 8, utf8_decode("SIM (  )   NÃO ( X )"), "R", 0, "J");
                    $pdf->Ln();
            
                    $pdf->Cell(190, 8, utf8_decode("- Deseja solicitar bolsa? {$solicitar}"), "LR", 0, "L");
                    $pdf->Ln();

                    if ($financeiros->solicitarRecursoFinanceiro == 'S')
                    {
                        $pdf->Cell(190, 8, utf8_decode("Agência: {$financeiros->agenciaRecursoFinanceiro} C/C: {$financeiros->agenciaRecursoFinanceiro} Local: {$financeiros->localRecursoFinanceiro}"), "LR", 0, "L");
                        $pdf->Ln();
                    }

                    $pdf->Cell(190, 8, "", "LR", 0, "L");
                    $pdf->Ln();
                }   
        
                $pdf->SetFont("Arial","B", 10);
                $pdf->MultiCell(190, 8, utf8_decode("Obs.: As bolsas da CAPES e do CNPq são concedidas competitivamente em número limitado. Não é permitido ao bolsista acumular bolsas ou ter vínculo empregatício com qualquer instituição ou empresa."), "LRB", "J", false);
                $pdf->SetFont("Arial","B", 10);
                $pdf->Cell(10, 8, utf8_decode("8."), 1, 0, "L", true);
                $pdf->Cell(180, 8, utf8_decode("QUAIS AS SUAS EXPECTATIVAS COM RELAÇÃO AO CURSO ?"), "1", 0, "J", true);
        
                $expectativas = Inscricao::obterExpectativaInscricao($codigoInscricao);
        
                $pdf->Ln();
                $pdf->SetFont("Arial","", 10);
                $pdf->MultiCell(190, 8, utf8_decode($expectativas->expectativasInscricao), 1, "J", false);
            }

            if ($nivel == 'ME')
            {            
                if ($edital->dataDoeEdital->format('m') < 7)
                {
                    $semestre  = 'segundo semestre de '.$edital->dataDoeEdital->format('Y');
                    $diretorio =  $edital->dataDoeEdital->format('Y').'2';
                }
                else
                {
                    $ano       = $edital->dataDoeEdital->format('Y') + 1;
                    $semestre  = 'primero semestre de '.$ano;
                    $diretorio = $ano.'1'; 
                }

                $assunto      = "MESTRADO - {$sigla}";
                $curso        = 'Seleção do Curso de Mestrado para ingresso no '.$semestre;
                $requerimento = 'Venho requerer minha inscrição para '.$curso.' conforme regulamenta o edital '.$sigla.' Nº '.$anosemestre.' (DOESP de '.$edital->dataDoeEdital->format('d/m/Y').').';
            }

            // if ($nivel == 'AE')
            // {         
            //     if ($edital->dataFinalEdital->format('m') < 7)
            //     {
            //         $semestre  = 'primero semestre de '.$edital->dataFinalEdital->format('Y');
            //         $diretorio = $edital->dataFinalEdital->format('Y').'1'; 
            //     }
            //     else
            //     {                
            //         $semestre  = 'segundo semestre de '.$edital->dataFinalEdital->format('Y');
            //         $diretorio =  $edital->dataFinalEdital->format('Y').'2';
            //     }
                
            //     $assunto      = "MESTRADO - {$sigla}";
            //     $curso        = 'Seleção do Curso de Mestrado para ingresso no '.$semestre;
            //     $requerimento = 'Venho requerer minha inscrição para aluno especial conforme regulamenta o edital '.$sigla.' Nº '.$anosemestre.'.';
            // }

            if ($nivel == 'DF')
            {            
                $ano       = $edital->dataDoeEdital->format('Y') + 1;
                $diretorio = $ano;

                $assunto      = "DOUTORADO FLUXO CONTINUO - {$sigla}";
                $curso        = 'Seleção do Curso de Doutorado Fluxo Contínuo para ingresso em '.$ano;
                $requerimento = 'Venho requerer minha inscrição para '.$curso.' conforme regulamenta o edital '.$sigla.' Nº '.$anosemestre.' (DOESP de '.$edital->dataDoeEdital->format('d/m/Y').').';
            }

            $pdf->SetFont('Arial', '', 10);
            $pdf->MultiCell(190, 8, utf8_decode($requerimento), 'LR', 'J', false);
            $pdf->Cell(190, 8, utf8_decode(''), 'LR', 0, 'L', false);
            $pdf->Ln();
            $pdf->Cell(140, 8, 'Assinatura do candidato:', 'LB', 0, 'L', false);
            $pdf->Cell(50, 8, 'Data:         /         /', 'BR', 0, 'L', false);
        }

        if ($nivel == 'ME' || $nivel == 'DD' || $nivel == 'AE')
        {
            $pdf->SetFont('Arial','B', 10);
            $pdf->SetFillColor(190,190,190);
            $pdf->Cell(10, 8, utf8_decode('1.'), 1, 0, 'L', true);
            $pdf->Cell(130, 8, utf8_decode('DADOS PESSOAIS:'), '1', 0, 'J', true);
            //$pdf->Image(asset("storage/{$foto->linkArquivo}"), 156, $eixofoto, 37, 50); 
            $pdf->Cell(50, 50, utf8_decode(''), 1, 0, 'R');
            $pdf->SetFont('Arial', 'B', 10);
    
            $pdf->SetY($eixoy);
            $pdf->Cell(13,  8, utf8_decode('Nome:'), 'LB',  0, 'C', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(127, 8, utf8_decode($pessoais->name), 'B',  0, 'L', false);
            $eixoy = $eixoy + 8;

            $pdf->SetY($eixoy);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(12, 8, utf8_decode('Sexo:'), 'L',  0, 'L', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(125, 8, utf8_decode($pessoais->sexoPessoal), 0,  0, 'L', false);
            $eixoy = $eixoy + 8;   

            $pdf->SetY($eixoy);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(12, 8, utf8_decode('CPF:'), 'L', 0, 'L', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(25, 8, $pessoais->cpf, 0, 0, 'L', false);
            
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(37, 8, utf8_decode('Data de Nascimento:'), 0,  0, 'L', false);    
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(20, 8, $pessoais->dataNascimentoPessoal->format('d/m/Y'), 0,  0, 'L', false);
            $eixoy = $eixoy + 8;

            $pdf->SetY($eixoy);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(30, 8, utf8_decode('Identidade ('.$pessoais->tipoDocumento.'):'), 'L',  0, 'L', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(107, 8, $pessoais->numeroDocumento, 0,  0, '', false);
            $eixoy = $eixoy + 8;

            $pais = Utils::obterPais($pessoais->paisPessoal);
            $localidade = Utils::obterLocalidade($pessoais->naturalidadePessoal);

            $pdf->SetY($eixoy);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(15, 8, utf8_decode('Cidade:'), 'L', 0, 'L', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(55, 8, utf8_decode($localidade["cidloc"]), 0,  0, 'L', false);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(23, 8, utf8_decode('Estado/País:'), 0,  0, 'L', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(40, 8, "{$localidade['sglest']}/{$pais['nompas']}", 0,  0, 'L', false);
            $eixoy = $eixoy + 8;

            if ($pessoais->especialPessoal == 'S')
            {
                $tipos = str_replace('|', ', ', $pessoais->tipoEspecialPessoal);
                $necessidades = utf8_decode("Sim - {$tipos}");
            }
            else
            {
                $necessidades = utf8_decode('Não');
            }

            $pdf->SetY($eixoy);
            $pdf->Cell(140, 2, '', 'LB',  0, 'L', false);
            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(20, 8, utf8_decode('Raça/Cor:'), 'LB',  0, 'L', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(30, 8, utf8_decode($pessoais->racaPessoal), 'B',  0, 'L', false);
            $pdf->SetFont('Arial', 'B', 10);        
            $pdf->Cell(70, 8, utf8_decode('É portador de Necessidades Especiais?'), 'LB',  0, 'L', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(70, 8, $necessidades, 'BR',  0, 'L', false);
            
            if ((substr($pessoais->codpes, 0, 2) == 88))
            {
                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(13, 8, utf8_decode('E-mail:'), 'LB',  0, 'L', false);
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(177, 8, $pessoais->email, 'BR',  0, 'L', false);
            }
            else
            {
                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(13, 8, utf8_decode('E-mail:'), 'LB',  0, 'L', false);
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(100, 8, $pessoais->email, 'BR',  0, 'L', false);
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(25, 8, utf8_decode('Número USP:'), 'LB',  0, 'L', false);
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(52, 8, $pessoais->codpes, 'BR',  0, 'L', false);
            }

            $enderecos = Inscricao::obterEnderecoInscricao($codigoInscricao);

            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(18, 8, utf8_decode('Endereço:'), 'LB',  0, 'L', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(172, 8, utf8_decode("{$enderecos->logradouroEndereco}, {$enderecos->numeroEndereco} {$enderecos->complementoEndereco}"), 'BR',  0, 'L', false);

            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(13, 8, utf8_decode('Bairro:'), 'LB',  0, 'L', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(60, 8, utf8_decode($enderecos->bairroEndereco), 'B',  0, 'L', false);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(17, 8, utf8_decode('Telefone:'), 'B',  0, 'L', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(100, 8, $pessoais->telefone, 'BR',  0, 'L', false);

            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(9, 8, utf8_decode('CEP:'), 'LB',  0, 'L', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(46, 8, $enderecos->cepEndereco, 'B',  0, 'L', false);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(18, 8, utf8_decode('Cidade:'), 'B',  0, 'R', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(52, 8, utf8_decode($enderecos->localidadeEndereco), 'B',  0, 'L', false);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(25, 8, utf8_decode('Estado:'), 'B',  0, 'R', false);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(40, 8, $enderecos->ufEndereco, 'BR',  0, 'L', false);

            if ($nivel == 'AE')
            {
                $temp = Inscricao::find($codigoInscricao);

                if ($temp->alunoEspecial == 'S')
                {
                    $cursou = 'Sim - '.$temp->dataAlunoEspecial;
                }
                else
                {
                    $cursou = utf8_decode('Não');
                }

                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(80, 8, utf8_decode('Já cursou Disciplina como Aluno Especial? '), 'LB',  0, 'L', false);
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(110, 8, $cursou, 'BR',  0, 'L', false);    
            }

            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(10, 8, utf8_decode('2.'), 1, 0, 'L', true);
            $pdf->Cell(180, 8, utf8_decode('RESUMO ESCOLAR'), '1', 0, 'J', true);

            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(70, 8, utf8_decode('ESCOLA'), 1, 0, 'C', false);
            $pdf->Cell(85, 8, utf8_decode('TÍTULO/ESPECIALIDADE'), 1, 0, 'C', false);    
            $pdf->Cell(35, 8, utf8_decode('ANO TITULAÇÃO'), 1, 0, 'C', false);
            //$pdf->Cell(25, 8, utf8_decode('SITUAÇÃO'), 1, 0, 'C', false);
            
            $pdf->SetFont('Arial', '', 10);

            $escolares = Inscricao::obterEscolarInscricao($codigoInscricao);

            foreach($escolares as $escolar)
            {
                /*$pdf->Ln();
                $pdf->CellFitScale(70, 8, utf8_decode($escolar->escolaResumoEscolar), 1, 0, 'J', false);
                $pdf->CellFitScale(70, 8, utf8_decode($escolar->especialidadeResumoEscolar), 1, 0, 'J', false);
                $pdf->CellFitScale(25, 8, utf8_decode($escolar->tipoResumoEscolar), 1, 0, 'C', false);
                            
                if ($escolar->inicioResumoEscolar == '')
                {
                    $pdf->Cell(25, 8, 'Em andamento', 1, 0, 'C', false);        
                }
                else
                {
                    $pdf->CellFitScale(25, 8, $escolar->inicioResumoEscolar->format('Y'), 1, 0, 'C', false);            
                }*/

                $pdf->Ln();
                $pdf->CellFitScale(70, 8, utf8_decode($escolar->escolaResumoEscolar), 1, 0, 'J', false);
                $pdf->CellFitScale(85, 8, utf8_decode($escolar->especialidadeResumoEscolar), 1, 0, 'J', false);
                //$pdf->CellFitScale(25, 8, utf8_decode($escolar->tipoResumoEscolar), 1, 0, 'C', false);
                
                if ($escolar->finalResumoEscolar == '')
                {
                    $pdf->Cell(35, 8, 'Em andamento', 1, 0, 'C', false);        
                }
                else
                {
                    $pdf->CellFitScale(35, 8, $escolar->finalResumoEscolar->format('Y'), 1, 0, 'C', false);            
                }

                //$pdf->CellFitScale(70, 8, '', 1, 0, 'J', false);
                //$pdf->CellFitScale(25, 8, '', 1, 0, 'C', false);
                //$pdf->CellFitScale(25, 8, '', 1, 0, 'C', false);
            }

            if ($nivel == 'AE')
            {
                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(10, 8, utf8_decode('3.'), 1, 0, 'L', true);
                $pdf->Cell(180, 8, utf8_decode('QUAL DISCIPLINA QUER CURSAR COMO ALUNO ESPECIAL?'), 1, 0, 'J', true);         
                $pdf->SetFont('Arial', '', 10);  

                $inscricao = Inscricao::obterDisciplinaInscricao($codigoInscricao);

                foreach($inscricao as $disciplina)
                {
                    $temp = Posgraduacao::disciplina($disciplina->codigoDisciplina);

                    $pdf->Ln();
                    $pdf->CellFitScale(190, 8, utf8_decode($temp['sgldis'].'-'.$temp['numseqdis'].' - '.$temp['nomdis'].' ('.$temp['numcretotdis'].' créditos)'), 1, 0, 'J', false);
                } 

                if($edital->codigoCurso == 97004)
                {
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', 10);
                    $pdf->Cell(10, 8, utf8_decode('4.'), 1, 0, 'L', true);
                    $pdf->Cell(180, 8, utf8_decode("POR QUE CURSAR DISCIPLINA COMO ALUNO ESPECIAL?"), '1', 0, 'J', true);
    
                    $expectativas = Inscricao::obterExpectativaInscricao($codigoInscricao);   
                }

                $pdf->Ln(); 
            }
            else
            {
                $pdf->Ln();
                $pdf->SetFont("Arial","B", 10);
                $pdf->Cell(10, 8, utf8_decode("3."), 1, 0, "L", true);
                $pdf->Cell(180, 8, utf8_decode("RECURSOS FINANCEIROS"), "1", 0, "J", true);
            
                $pdf->Ln();
                $pdf->SetFont("Arial","", 10);
                $pdf->Cell(80, 8, utf8_decode('Possui bolsa de estudos de alguma instituição?'), "L", 0, "L");
        
                $financeiros = Inscricao::obterFinanceiroInscricao($codigoInscricao);
        
                if ($financeiros->bolsaRecursoFinanceiro == 'S')
                {
                    $bolsa = true;
                    $pdf->Cell(110, 8, utf8_decode("SIM ( X )   NÃO (  )"), "R", 0, "J");
                    $pdf->Ln();
            
                    $pdf->Cell(100, 8, utf8_decode("- Nome do órgão financiador: ".$financeiros->orgaoRecursoFinanceiro), "L", 0, "L");
                    $pdf->Cell(90, 8, utf8_decode("- Tipo de Bolsa: ".$financeiros->tipoBolsaFinanceiro), "R", 0, "L");
                    $pdf->Ln();
                    $pdf->Cell(190, 8, utf8_decode("- Período de vigência (mês/ano):  de ".date('m/Y', strtotime($financeiros->inicioRecursoFinanceiro))." a ".date('m/Y', strtotime($financeiros->finalRecursoFinanceiro))), "LR", 0, "L");
                    $pdf->Ln();
            
                    $pdf->Cell(190, 8, "", "LR", 0, "L");
                    $pdf->Ln();
                }
                else
                {
                    $solicitar = ($financeiros->solicitarRecursoFinanceiro == 'S') ? 'Sim' : 'Não';
        
                    $pdf->Cell(110, 8, utf8_decode("SIM (  )   NÃO ( X )"), "R", 0, "J");
                    $pdf->Ln();
            
                    $pdf->Cell(190, 8, utf8_decode("- Deseja solicitar bolsa? {$solicitar}"), "LR", 0, "L");
                    $pdf->Ln();
            
                    $pdf->Cell(190, 8, "", "LR", 0, "L");
                    $pdf->Ln();
                }   
        
                $pdf->SetFont("Arial","B", 10);
                $pdf->MultiCell(190, 8, utf8_decode("Obs.: As bolsas da CAPES e do CNPq são concedidas competitivamente em número limitado. Não é permitido ao bolsista acumular bolsas ou ter vínculo empregatício com qualquer instituição ou empresa."), "LRB", "J", false);
            }

            if ($nivel == 'ME')
            {            
                if ($edital->dataDoeEdital->format('m') < 7)
                {
                    $semestre  = 'segundo semestre de '.$edital->dataDoeEdital->format('Y');
                    $diretorio =  $edital->dataDoeEdital->format('Y').'2';
                }
                else
                {
                    $ano       = $edital->dataDoeEdital->format('Y') + 1;
                    $semestre  = 'primero semestre de '.$ano;
                    $diretorio = $ano.'1'; 
                }

                $assunto      = "MESTRADO - {$sigla}";
                $curso        = 'Seleção do Curso de Mestrado para ingresso no '.$semestre;
                $requerimento = 'Venho requerer minha inscrição para '.$curso.' conforme regulamenta o edital '.$sigla.' Nº '.$anosemestre.' (DOESP de '.$edital->dataDoeEdital->format('d/m/Y').').';
            }

            if ($nivel == 'DF')
            {            
                $ano       = $edital->dataDoeEdital->format('Y') + 1;
                $diretorio = $ano;

                $assunto      = "DOUTORADO FLUXO CONTINUO - {$sigla}";
                $curso        = 'Seleção do Curso de Doutorado Fluxo Contínuo para ingresso em '.$ano;
                $requerimento = 'Venho requerer minha inscrição para '.$curso.' conforme regulamenta o edital '.$sigla.' Nº '.$anosemestre.' (DOESP de '.$edital->dataDoeEdital->format('d/m/Y').').';
            }

            if ($nivel == 'AE')
            {
                if ($edital->dataFinalEdital->format('m') <= 7)
                {
                    $diretorio =  $edital->dataFinalEdital->format('Y').'2';
                }
                else
                {
                    $ano       = $edital->dataFinalEdital->format('Y') + 1;
                    $diretorio = $ano.'1'; 
                }

                $requerimento = 'Venho requerer minha inscrição como Aluno Especial, conforme regulamenta os procedimentos publicados na página da Comissão de Pós-graduação (CPG).';
            }

            if ($nivel == 'ME' || $nivel == 'DD' || $nivel == 'DF')
            { 
                $pdf->SetFont('Arial', '', 10);
                $pdf->MultiCell(190, 8, utf8_decode($requerimento), 'LR', 'J', false);
                $pdf->Cell(190, 8, utf8_decode(''), 'LR', 0, 'L', false);

                $pdf->Ln();
                $pdf->Cell(140, 8, 'Assinatura do candidato:', 'LB', 0, 'L', false);
                $pdf->Cell(50, 8, 'Data:         /         /', 'BR', 0, 'L', false);
            }

            if ($nivel == 'AE')
            { 
                $pdf->SetFont('Arial', '', 10);
                $pdf->MultiCell(190, 8, utf8_decode($requerimento), 1, 'J', false);
            }
        }

        $sigla   = Str::lower($sigla);
        $arquivo = storage_path("app/public/{$sigla}/comprovante/{$diretorio}/{$pessoais->numeroInscricao}.pdf");
        $nome    = "{$sigla}/comprovante/{$diretorio}/{$pessoais->numeroInscricao}.pdf";

        if (!file_exists($arquivo))
        {
            $pdf->Output('F', $arquivo);
        }
        
        $pdf->Output('I', "{$pessoais->numeroInscricao}.pdf");
    }

    public static function matricula(\App\Models\Pdf\Matricula $pdf, $codigoInscricao)
    {
        $inscricao = Inscricao::obterDadosPessoaisInscricao($codigoInscricao);

        $pdf->setCabecalho('ppgem');

        $pdf->SetStyle('p', 'arial', 'N', 14, '0,0,0');
        $pdf->SetStyle('b', 'arial', 'B', 0, '0,0,0');
        $pdf->SetStyle('bu', 'arial', 'BU', 0, '0,0,0');
        $pdf->SetStyle('i', 'arial', 'I', 0, '0,0,0');
        
        $pdf->SetDisplayMode('real');
        $pdf->AliasNbPages();   

        $texto = "<p>Eu, {$inscricao->name}, Passaporte {$gcub->passaporteAluno}, e-mail {$inscricao->email}, venho requerer à <b><i>Comissão de Pós-Graduação</i></b>, matrícula como aluno(a) <b>REGULAR</b>, no Mestrado do <b>Programa de Pós-Graduação em Engenharia de Materiais</b> na área de concentração: <b>97134 - Materiais Convencionais e Avançados</b>, nas <b>Disciplinas</b> abaixo listadas:</p>";

        $pdf->AddPage();
        
        $pdf->SetFont('Arial','B', 16);
        $pdf->SetFillColor(190,190,190);
        $pdf->MultiCell(190, 8, utf8_decode('PÓS-GRADUAÇÃO EM ENGENHARIA DE MATERIAIS - PPGEM REQUERIMENTO DE PRIMEIRA MATRÍCULA REGULAR'), 1, 'C', true);
        $pdf->Ln();
        
        $pdf->SetFont('Arial', '', 14);
        $pdf->SetFillColor(255,255,255);
        $pdf->WriteTag(190,8, utf8_decode($texto), 0, 'J');
        $pdf->Ln(5);
                    
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor(190,190,190);
        $pdf->Cell(40,  8, utf8_decode('CÓDIGO'), 1, 0, 'C', true);
        $pdf->Cell(110, 8, utf8_decode('DISCIPLINA'), 1, 0, 'C', true);
        $pdf->Cell(40,  8, utf8_decode('Nº DE CRÉDITOS'), 1, 0, 'C', true);
        $pdf->Ln();    
        
        $pdf->SetFont('Arial', '', 10);

        foreach($dados->disciplinasGcub as $disciplina)
        {
            $temp = Posgraduacao::disciplina($disciplina);
            
            $pdf->Cell(40,  8, utf8_decode("{$temp['sgldis']}-{$temp['numseqdis']}"), 1, 0, 'C', false);
            $pdf->Cell(110, 8, utf8_decode(" {$temp['nomdis']}"), 1, 0, 'L', false);
            $pdf->Cell(40,  8, utf8_decode(" {$temp['numcretotdis']}"), 1, 0, 'C', false);
            $pdf->Ln();  
        }

        $pdf->Ln(5);
        $pdf->Cell(75,  8, utf8_decode('Lorena, _____/_____/_______'), 0, 0, 'L', false);
        $pdf->Cell(35, 8, utf8_decode(''), 0, 0, 'C', false);
        $pdf->Cell(75,  8, utf8_decode('____________________________________'), 0, 0, 'C', false);
        $pdf->Cell(5,  8, utf8_decode(''), 0, 0, 'C', false);
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(75,  5, utf8_decode(''), 0, 0, 'L', false);
        $pdf->Cell(35, 5, utf8_decode(''), 0, 0, 'C', false);
        $pdf->Cell(75,  5, utf8_decode('Assinatura do Aluno'), 0, 0, 'C', false);
        $pdf->Cell(5,  5, utf8_decode(''), 0, 0, 'C', false);
        $pdf->Ln(10);

        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(75,  8, utf8_decode(''), 0, 0, 'C', false);
        $pdf->Cell(35, 8, utf8_decode(''), 0, 0, 'C', false);
        
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(75,  8, utf8_decode('Orientação Acadêmica'), 0, 0, 'C', false);
        $pdf->Cell(5,  8, utf8_decode(''), 0, 0, 'C', false);
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(75,  5, utf8_decode('Nome / Carimbo do Orientador ou'), 'T', 0, 'C', false);
        $pdf->Cell(35, 5, utf8_decode(''), 0, 0, 'C', false);
        $pdf->Cell(75,  5, utf8_decode('Assinatura do Orientador'), 'T', 0, 'C', false);
        $pdf->Cell(5,  5, utf8_decode(''), 0, 0, 'C', false);
        $pdf->Ln();

        $pdf->Cell(75,  5, utf8_decode('Orientador Acadêmico'), 0, 0, 'C', false);
        $pdf->Cell(35, 5, utf8_decode(''), 0, 0, 'C', false);
        $pdf->Cell(75,  5, utf8_decode(''), 0, 0, 'C', false);
        $pdf->Cell(5,  5, utf8_decode(''), 0, 0, 'C', false);
        $pdf->Ln(20);

        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(60,  8, utf8_decode(''), 0, 0, 'L', false);
        
        $pdf->SetFont('Arial', '', 14);
        $pdf->Cell(80, 8, utf8_decode(''), 0, 0, 'C', false);
        $pdf->Cell(60,  8, utf8_decode(''), 0, 0, 'C', false);
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(60,  5, utf8_decode(''), 0, 0, 'L', false);
        $pdf->Cell(80, 5, utf8_decode('Coordenador do Programa'), 'T', 0, 'C', false);
        $pdf->Cell(60,  5, utf8_decode(''), 0, 0, 'C', false);
        $pdf->Ln();

        //$pdf->Output();

        return redirect(asset('storage/'.$matricula));
    }

    public function show($codigoInscricao, $tipo = '')
    {    
        /*if ($tipo == 'pessoal')
        {
            $inscricao   = Inscricao::obterDadosPessoaisInscricao($codigoInscricao);
            $endereco    = Inscricao::obterEnderecoInscricao($codigoInscricao);
            $emergencia  = Inscricao::obterEmergenciaInscricao($codigoInscricao);
            $edital      = Edital::join('niveis', 'editais.codigoNivel', '=', 'niveis.codigoNivel')->where('editais.codigoEdital', $inscricao->codigoEdital)->first();
            $sigla       = Str::lower(Utils::obterSiglaCurso($edital->codigoCurso));
            $anosemestre = Edital::obterSemestreAno($inscricao->codigoEdital);   

            return view('inscricao.visualizar',
            [
                'codigoInscricao' => $codigoInscricao,
                'inscricao'       => $inscricao,
                'endereco'        => $endereco,
                'emergencia'      => $emergencia,
                'arquivos'        => '',
                'tipo'            => "inscricao.visualizar.{$tipo}",
                'ficha'           => asset("storage/{$sigla}/comprovante/{$anosemestre}/{$inscricao->numeroInscricao}.pdf"),
            ]);
        }
        else if ($tipo == '')
        {
            $inscricao   = Inscricao::obterDadosPessoaisInscricao($codigoInscricao);
            $endereco    = Inscricao::obterEnderecoInscricao($codigoInscricao);
            $edital      = Edital::join('niveis', 'editais.codigoNivel', '=', 'niveis.codigoNivel')->where('editais.codigoEdital', $inscricao->codigoEdital)->first();
            $sigla       = Utils::obterSiglaCurso($edital->codigoCurso);
            $anosemestre = Edital::obterSemestreAno($inscricao->codigoEdital);
    
            $arquivos = Arquivo::join('tipo_documentos', 'arquivos.codigoTipoDocumento', '=', 'tipo_documentos.codigoTipoDocumento')
                               ->join('inscricoes_arquivos', 'arquivos.codigoArquivo', '=', 'inscricoes_arquivos.codigoArquivo')
                               ->where('inscricoes_arquivos.codigoInscricao', $codigoInscricao)->get();
    
            $sigla = Str::lower($sigla);

            return view('inscricao.visualizar',
            [
                'codigoInscricao' => $codigoInscricao,
                'inscricao'       => $inscricao,
                'endereco'        => $endereco,
                'arquivos'        => $arquivos,
                'tipo'            => "inscricao.visualizar.index",
                'ficha'           => asset("storage/{$sigla}/comprovante/{$anosemestre}/{$inscricao->numeroInscricao}.pdf"),
            ]);
        }

        $inscricao   = Inscricao::obterDadosPessoaisInscricao($codigoInscricao);
        $edital      = Edital::join('niveis', 'editais.codigoNivel', '=', 'niveis.codigoNivel')->where('editais.codigoEdital', $inscricao->codigoEdital)->first();
        $sigla       = Utils::obterSiglaCurso($edital->codigoCurso);
        $anosemestre = Edital::obterSemestreAno($inscricao->codigoEdital);

        $foto        = Inscricao::obterFotoInscricao($codigoInscricao);
        $historicos  = Arquivo::obterArquivosHistorico($codigoInscricao, true);
        $diplomas    = Arquivo::obterArquivosDiploma($codigoInscricao, true);
        $curriculo   = Arquivo::obterArquivosCurriculo($codigoInscricao);

        $cpf   = Arquivo::obterArquivosCpf($codigoInscricao);
        $rg    = Arquivo::obterArquivosRg($codigoInscricao);
        $rne   = Arquivo::obterArquivosRne($codigoInscricao);

        $requerimento = Arquivo::obterArquivosRequerimento($codigoInscricao);
        $projeto      = Arquivo::obterArquivosPreProjeto($codigoInscricao);

        $sigla = Str::lower($sigla);        

        if (file_exists(storage_path("app/public/{$sigla}/comprovante/{$anosemestre}/{$inscricao->numeroInscricao}.pdf")))
        {
            $ficha = asset("storage/{$sigla}/comprovante/{$anosemestre}/{$inscricao->numeroInscricao}.pdf");
        }
        else
        {
            $ficha = "";
        }
                return view('inscricao.visualizar.admin.index',
        [
            'codigoInscricao' => $codigoInscricao,
            'inscricao'       => $inscricao,
            'tipo'            => "inscricao.visualizar.index",
            'ficha'           => $ficha,
            'foto'            => (empty($foto) ? '' : $foto->linkArquivo),
            'cpf'             => (empty($cpf) ? '' : $cpf),
            'rg'              => (empty($rg) ? '' : $rg->linkArquivo),
            'rne'             => (empty($rne) ? '' : $rne->linkArquivo),
            'historicos'      => (count($historicos) == 0 ? '' : $historicos),
            'diplomas'        => (count($diplomas) == 0 ? '' : $diplomas),
            'curriculo'       => (empty($curriculo) ? '' : $curriculo->linkArquivo),
            'doutorado'       => (($edital->codigoNivel == 2) ? true : false),
            'projeto'         => (empty($projeto) ? '' : $projeto->linkArquivo),
            'requerimento'    => (empty($requerimento) ? '' : $requerimento->linkArquivo),
        ]);
        
        */

        $dados     = Inscricao::obterDadosPessoaisInscricao($codigoInscricao);
        $inscricao = Inscricao::obterObrigatorioInscricao($codigoInscricao,  array(26, 27, 28, 1, 2, 4, 3, 5, 9, 6, 29, 30, 9, 31, 32, 33, 34, 35, 36, 37, 38));

        $status    = Inscricao::obterStatusInscricao($codigoInscricao);
        $edital    = Inscricao::obterEditalInscricao($codigoInscricao);
      
        Utils::obterTotalInscricao($codigoInscricao);
        $total = Utils::obterTotalArquivos($codigoInscricao, array(26, 27, 28, 1, 2, 4, 3, 5, 9, 6, 29, 30, 9, 31, 32, 33, 34, 35, 36, 37, 38));

        $nivel = Edital::obterNivelEdital($edital);
        session(['nivel' => $nivel]);

        $voltar = "inscricao/{$edital}";

        $aprovados = array(211, 212, 213, 214, 219, 244, 272);

        if (in_array($codigoInscricao, $aprovados))
        {
            session(['aprovado' => 1]);
        }
        else
        {
            session(['aprovado' => 0]);
        }

        return view('inscricao.visualizar.admin.index',
        [
            'codigoInscricao'   => $codigoInscricao,
            'codigoEdital'      => $edital,
            'link_voltar'       => $voltar,
            'arquivos'          => $inscricao,
            'total'             => $total,
            'status'            => $status,
            'count'             => count($inscricao),
            'inscricao'         => $dados,
        ]); 
    }

    public function validar(Request $request, $codigoInscricao)
    {
        $inscricao = Inscricao::join('users', 'inscricoes.codigoUsuario', '=', 'users.id')->where('codigoInscricao', $codigoInscricao)->first();
        $nivel = Edital::obterNivelEdital($inscricao->codigoEdital);

        if ($nivel == 'AE')
        {
            $pdf = new Comprovante;
            $requerimento = Inscricao::gerarComprovante($pdf, '', $codigoInscricao);

            $arquivo = Arquivo::create([
                            'codigoUsuario'         => $inscricao->codigoUsuario,
                            'codigoTipoDocumento'   => 26,
                            'linkArquivo'           => "ppgem/comprovante/{$inscricao->numeroInscricao}.pdf",
                            'codigoPessoaAlteracao' => Auth::user()->codpes,
            ]);

            InscricoesArquivos::create([
                'codigoInscricao'       => $codigoInscricao,
                'codigoArquivo'         => $arquivo->codigoArquivo,
                'codigoPessoaAlteracao' => Auth::user()->codpes,
            ]);

            Mail::to($inscricao->email)->send(new ConfirmacaoEspecialMail($codigoInscricao, $requerimento));
        }
        else
        {
            Mail::to($inscricao->email)->send(new ConfirmacaoMail($codigoInscricao));
        }

        if (Mail::failures()) 
        {
            request()->session()->flash('alert-danger', "Ocorreu um erro na validação da inscrição Nº {$inscricao->numeroInscricao}.");
        }    
        else
        {
            Inscricao::where('codigoInscricao', $codigoInscricao)->where('statusInscricao', 'P')->update(['statusInscricao' => 'C']);

            request()->session()->flash('alert-success', "Inscrição Nº {$inscricao->numeroInscricao} validada com sucesso.");
        } 

        return redirect("/inscricao/visualizar/{$codigoInscricao}");
    }

    public function recusar($codigoInscricao)
    {
        $inscricao = Inscricao::obterDadosPessoaisInscricao($codigoInscricao);

        Inscricao::where('codigoInscricao', $codigoInscricao)->update(['statusInscricao' => 'R']);
        
        request()->session()->flash('alert-success', "Inscrição Nº {$codigoInscricao} recusada com sucesso.");

        return redirect("admin/listar-inscritos/{$inscricao->codigoEdital}");
    }

    public function devolver_create($codigoInscricao)
    {
        $inscricao = Inscricao::obterDadosPessoaisInscricao($codigoInscricao);
        
        return view('inscricao.devolver',
        [
            'codigoInscricao'   => $codigoInscricao,
            'id'                => $inscricao->codigoEdital,
            'inscricao'         => $inscricao,
        ]);

    }

    public function devolver(Request $request, $codigoInscricao)
    {
        $inscricao = Inscricao::join('users', 'inscricoes.codigoUsuario', '=', 'users.id')->where('codigoInscricao', $codigoInscricao)->first();

        Mail::to($inscricao->email)->send(new DevolucaoMail($codigoInscricao, $request->body));
        
        if (Mail::failures()) 
        {
            request()->session()->flash('alert-danger', "Ocorreu um erro na devolução da inscrição Nº {$inscricao->numeroInscricao}.");
        }    
        else
        {
            Inscricao::where('codigoInscricao', $codigoInscricao)->where('statusInscricao', 'P')->update(['statusInscricao' => 'N']);

            request()->session()->flash('alert-success', "Inscrição Nº {$inscricao->numeroInscricao} devolvida com sucesso.");
        } 

        return redirect("/inscricao/visualizar/{$codigoInscricao}");
    }

    public function matricula_create($codigoInscricao)
    {
        $aprovado = ProcessoSeletivo::obterAprovado();

        if (empty($aprovado))
        {
            $item = array();
            $item['title'] = 'Aviso';
            $item['story'] = 'Você não tem permissão de acessar essa página.';

            return view('components.modal',
            [
                'item' => $item,                
            ]);
        }
        else
        {
            $seletivo = ProcessoSeletivo::obterInscricaoAprovado($aprovado);

            if (!empty($seletivo->codigoInscricaoDisciplina))
            {
                $item = array();
                $item['title'] = 'Aviso';
                $item['story'] = 'Você já enviou o Requerimento de Primeira Matrícula.<br/>Aguarde novas informações no seu e-mail cadastrado';
    
                return view('components.modal',
                [
                    'item' => $item,                
                ]);
            }
        }

        $disciplinas = Utils::listarOferecimentoPos(97002, '04/03/2024', '16/06/2024');                        

        return view('inscricao.matricula',
        [
            'codigoInscricao' => $codigoInscricao,
            'disciplinas'     => $disciplinas
        ]);
    }

    public function matricula_store(Request $request, \App\Models\Pdf\Matricula $pdf)
    {
        $disciplinas = InscricoesDisciplinas::where('codigoInscricao', '=', $request->codigoInscricao)->count();
        $edital      = Edital::obterEditalInscricao($request->codigoInscricao);

        if ($disciplinas == 0)
        {
            foreach($request->disciplinasGcub as $disciplina)
            {
                $inscricaoDisciplinas = InscricoesDisciplinas::create([
                    'codigoInscricao'       => $request->codigoInscricao,
                    'codigoDisciplina'      => $disciplina,
                    'statusDisciplina'      => 'A',
                    'codigoPessoaAlteracao' => Auth::user()->codpes,
                ]);
            }
        }

        if ($edital->codigoCurso == 97002)
        {
            $anexo = Inscricao::gerarMatricula($pdf, 'ppgem', $request->codigoInscricao);
        }
        
        Mail::to($edital->email)->send(new MatriculaMail($request->codigoInscricao, $anexo));
        
        if (Mail::failures()) 
        {
            request()->session()->flash('alert-danger', "Ocorreu um erro no cadastrado do Requerimento de Primeira Matrícula. Tente novamente mais tarde.");
        }    
        else
        {
            request()->session()->flash('alert-success', 'Requerimento de Primeira Matrícula cadastrado com sucesso. Aguarde informações no seu e-mail cadastrado.'); 
        } 
    
        return redirect('dashboard'); 
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
            'status'          => $status,
            'pessoal'         => $inscricao->pessoal,
            'arquivo'         => $inscricao->arquivo,
            'endereco'        => $inscricao->endereco,
            'total'           => 0,
            //'arquivo'         => $arquivo,
            //'endereco'        => $endereco,
            //'total'           => $total,
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
            'status'          => $status,
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
            'status'          => $status,
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

    public function proficiencia_imprimir(\App\Models\Comprovante $pdf, $codigoInscricao)
    { 
        $pos = Posgraduacao::obterVinculoAtivo(Auth::user()->codpes);

        if ($pos['nivpgm'] == 'DO') 
        {
            $doutorado = 'X';
            $mestrado = ' ';
        }
        else
        {
            $doutorado = ' ';
            $mestrado = 'X';
        }

        //

        if(Auth::user()->codpes == 13556853)
        {
            $texto = "<p>Eu Ana Carolina B. da Silva, N.ºUSP: ".Auth::user()->codpes.", aluno(a) regularmente matriculado(a) no Programa de Pós-Graduação em Engenharia de Materiais da Escola de Engenharia de Lorena - EEL/USP, em nível de (".$mestrado.") Mestrado / (".$doutorado.") Doutorado, sob orientação do(a) Prof(a). Dr(a). ".$pos['nompesori'].", venho requerer <b>Exame de Proficiência em Língua Estrangeira, no idioma Inglês.</b></p>";
        }
        else
        {
            $texto = "<p>Eu ".Auth::user()->name.", - N.ºUSP: ".Auth::user()->codpes.", aluno(a) regularmente matriculado(a) no Programa de Pós-Graduação em Engenharia de Materiais da Escola de Engenharia de Lorena - EEL/USP, em nível de (".$mestrado.") Mestrado / (".$doutorado.") Doutorado, sob orientação do(a) Prof(a). Dr(a). ".$pos['nompesori'].", venho requerer <b>Exame de Proficiência em Língua Estrangeira, no idioma Inglês.</b></p>";
        }
       
        $pdf->setCabecalhoPresenca('ppgem');
        $pdf->setPresenca('ppgem');
      
        $pdf->SetStyle('p', 'arial', 'N', 14, '0,0,0');
        $pdf->SetStyle('b', 'arial', 'B', 0, '0,0,0');
        $pdf->SetStyle('bu', 'arial', 'BU', 0, '0,0,0');
        $pdf->SetStyle('i', 'arial', 'I', 0, '0,0,0');
        
        $pdf->SetDisplayMode('real');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial','B', 14);
        $pdf->Ln(5);
        $pdf->MultiCell(190, 8, utf8_decode('REQUERIMENTO PARA EXAME DE PROFICIÊNCIA EM LÍNGUA ESTRANGEIRA'), 0, 'C');            
        $pdf->Ln();

        $pdf->SetFont('Arial','', 12);
        $pdf->WriteTag(190, 8, utf8_decode($texto), 0, 'J');            
        $pdf->Ln(5);

        $data = Carbon::now();

        $pdf->SetFont('Arial','', 12);
        $pdf->Cell(190, 8, utf8_decode('Lorena, '.$data->format('d').'/'.$data->format('m').'/'.$data->format('Y')), 0, 0, 'R');
        $pdf->Ln(15);

        $pdf->Cell(190, 8, utf8_decode('___________________________________'), 0, 0, 'C');
        $pdf->Ln();
        $pdf->Cell(190, 8, utf8_decode('Assinatura do Aluno'), 0, 0, 'C');
        $pdf->Ln(20);

        $pdf->Cell(20, 8, utf8_decode(''), 0, 0, 'L');
        $pdf->Cell(170, 8, utf8_decode('De acordo:'), 0, 0, 'L');
        $pdf->Ln();
        $pdf->Cell(190, 8, utf8_decode('___________________________________'), 0, 0, 'C');
        $pdf->Ln();
        $pdf->Cell(190, 8, utf8_decode('Orientador'), 0, 0, 'C');
        $pdf->Ln(15);

        $pdf->SetFont('Arial','', 12);
        $pdf->setY(-80);
        $pdf->Cell(20, 8, utf8_decode(''), 0, 0, 'L');
        $pdf->WriteTag(170, 8, utf8_decode('<p><i>Para uso da Secretaria:</i></p>'), 0, 'L');
        $pdf->Ln();
        
        $pdf->SetFont('Arial','', 12);
        $pdf->Cell(20, 8, utf8_decode(''), 0, 0, 'L');
        $pdf->Cell(150, 8, utf8_decode('Ciente:'), 'LTR', 0, 'L');
        $pdf->Cell(20, 8, utf8_decode(''), 0, 0, 'L');
        $pdf->Ln();
        $pdf->Cell(20, 8, utf8_decode(''), 0, 0, 'L');
        $pdf->Cell(150, 8, utf8_decode(''), 'LR', 0, 'L');
        $pdf->Cell(20, 8, utf8_decode(''), 0, 0, 'L');
        $pdf->Ln();
        $pdf->Cell(20, 8, utf8_decode(''), 0, 0, 'L');
        $pdf->Cell(150, 8, utf8_decode('_______________________________'), 'LR', 0, 'C');
        $pdf->Cell(20, 8, utf8_decode(''), 0, 0, 'L');
        $pdf->Ln();
        $pdf->Cell(20, 8, utf8_decode(''), 0, 0, 'L');
        $pdf->Cell(150, 8, utf8_decode('Coordenador do PPGEM'), 'LBR', 0, 'C');
        $pdf->Cell(20, 8, utf8_decode(''), 0, 0, 'L');
        $pdf->Ln();
  
        $pdf->Output();
    }

    public function proficiencia_store(Request $request)
    { 
        $codigoEdital = Inscricao::obterEditalInscricao($request->codigoInscricao);
        $edital       = Edital::join('niveis', 'editais.codigoNivel', '=', 'niveis.codigoNivel')
                              ->join('users', 'editais.codigoUsuario', '=', 'users.id')
                              ->where('editais.codigoEdital', $codigoEdital)->first();

        $path = $request->file('arquivo')->store('arquivos', 'public');

        $arquivo = Arquivo::create([
            'codigoUsuario'         => Auth::user()->id,
            'codigoTipoDocumento'   => $request->codigoTipoDocumento,
            'linkArquivo'           => $path,
            'codigoPessoaAlteracao' => Auth::user()->codpes,
        ]);

        $inscricao = InscricoesArquivos::create([
            'codigoInscricao'       => $request->codigoInscricao,
            'codigoArquivo'         => $arquivo->codigoArquivo,
            'codigoPessoaAlteracao' => Auth::user()->codpes,
        ]);

        $inscricao = InscricoesProficiencia::create([
            'codigoInscricao'        => $request->codigoInscricao,
            'codigoPessoaOrientador' => $request->codigoPessoaOrientador,
            'codigoPessoaAlteracao'  => Auth::user()->codpes,
        ]);

        Mail::to($edital->email)->send(new ComprovanteMail($request->codigoInscricao));
            
        if (Mail::failures()) 
        {
            request()->session()->flash('alert-danger', "Ocorreu um erro no cadastrado do Requerimento.");
        }    
        else
        {
            Inscricao::where('codigoInscricao', $request->codigoInscricao)->where('statusInscricao', 'N')->update(['statusInscricao' => 'P']);

            request()->session()->flash('alert-success', 'Requerimento cadastrado com sucesso'); 
        } 

        return redirect("inscricao/{$edital->codigoEdital}"); 
    }

    public static function proficiencia_presenca(\App\Models\Comprovante $pdf, $codigoEdital)
    {
        //$inscricao = Inscricao::obterDadosPessoaisInscricao($codigoInscricao);

        $pdf->setCabecalhoPresenca('ppgem');
        $pdf->setPresenca('ppgem');
      
        $pdf->SetStyle('p', 'arial', 'N', 14, '0,0,0');
        $pdf->SetStyle('b', 'arial', 'B', 0, '0,0,0');
        $pdf->SetStyle('bu', 'arial', 'BU', 0, '0,0,0');
        $pdf->SetStyle('i', 'arial', 'I', 0, '0,0,0');
        
        $pdf->SetDisplayMode('real');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial','B', 14);
        $pdf->Ln(5);
        $pdf->Cell(190, 8, utf8_decode('Alunos Inscritos para Exame de Proficiência em Língua - Inglês'), 0, 0, 'C');            
        $pdf->Ln();
        $pdf->Cell(190, 8, utf8_decode('16/05/2024 - 1º Exame 2024'), 0, 0, 'C'); 
        $pdf->Ln(10);

        $pdf->SetFont('Arial','B', 12);
        $pdf->Cell(5, 8, utf8_decode(''), 0, 0, 'C');
        $pdf->WriteTag(175, 8, utf8_decode('<p><bu><i>MESTRADO</i></bu></p>'), 0, 'J'); 
        $pdf->Cell(10, 8, utf8_decode(''), 0, 0, 'C');
        $pdf->Ln(1);

        $pdf->SetFont('Arial','B', 12);
        $pdf->SetFillColor(190,190,190);
        $pdf->Cell(5, 8, utf8_decode(''), 0, 0, 'C');
        $pdf->Cell(15, 8, utf8_decode('Nº'), 1, 0, 'C', true);
        $pdf->Cell(25, 8, utf8_decode('Nº USP'), 1, 0, 'C', true);
        $pdf->Cell(80, 8, utf8_decode('Nome'), 1, 0, 'C', true);
        $pdf->Cell(60, 8, utf8_decode('Assinatura'), 1, 0, 'C', true);
        $pdf->Cell(5, 8, utf8_decode(''), 0, 0, 'C');
        $pdf->Ln();

        //\DB::enableQueryLog();

        $inscricoes = Inscricao::select(\DB::raw('users.*'))
                                ->join('inscricoes_proficiencia', 'inscricoes.codigoInscricao', '=', 'inscricoes_proficiencia.codigoInscricao')
                                ->join('users', 'users.id', '=', 'inscricoes.codigoUsuario')
                                ->where('inscricoes.codigoEdital',  $codigoEdital)
                                ->where('inscricoes.statusInscricao',  'C')
                                ->where('inscricoes_proficiencia.nivelProficiencia',  'ME')
                                ->orderBy('users.name')
                                ->get();

        //dd(\DB::getQueryLog());

        $pdf->SetFont('Arial', '', 12);
        $i = 1;

        foreach($inscricoes as $inscricao)
        {
            $pdf->Cell(5, 8, utf8_decode(''), 0, 0, 'C');
            $pdf->Cell(15, 8, utf8_decode($i), 1, 0, 'C');
            $pdf->Cell(25, 8, utf8_decode($inscricao->codpes), 1, 0, 'C');
            $pdf->Cell(80, 8, utf8_decode($inscricao->name), 1, 0, 'L');
            $pdf->Cell(60, 8, utf8_decode(''), 1, 0, 'C');
            $pdf->Cell(5, 8, utf8_decode(''), 0, 0, 'C');
            $pdf->Ln();
            $i++;
        }

        $pdf->Ln(10);
        $pdf->SetFont('Arial','B', 12);
        $pdf->Cell(5, 8, utf8_decode(''), 0, 0, 'C');
        $pdf->WriteTag(175, 8, utf8_decode('<p><bu><i>DOUTORADO</i></bu></p>'), 0, 'J'); 
        $pdf->Cell(10, 8, utf8_decode(''), 0, 0, 'C');
        $pdf->Ln(1);

        $pdf->SetFont('Arial','B', 12);
        $pdf->SetFillColor(190,190,190);
        $pdf->Cell(5, 8, utf8_decode(''), 0, 0, 'C');
        $pdf->Cell(15, 8, utf8_decode('Nº'), 1, 0, 'C', true);
        $pdf->Cell(25, 8, utf8_decode('Nº USP'), 1, 0, 'C', true);
        $pdf->Cell(80, 8, utf8_decode('Nome'), 1, 0, 'C', true);
        $pdf->Cell(60, 8, utf8_decode('Assinatura'), 1, 0, 'C', true);
        $pdf->Cell(5, 8, utf8_decode(''), 0, 0, 'C');
        $pdf->Ln();

        //\DB::enableQueryLog();

        $inscricoes = Inscricao::select(\DB::raw('users.*'))
                                ->join('inscricoes_proficiencia', 'inscricoes.codigoInscricao', '=', 'inscricoes_proficiencia.codigoInscricao')
                                ->join('users', 'users.id', '=', 'inscricoes.codigoUsuario')
                                ->where('inscricoes.codigoEdital',  $codigoEdital)
                                ->where('inscricoes.statusInscricao',  'C')
                                ->where('inscricoes_proficiencia.nivelProficiencia',  'DD')
                                ->orderBy('users.name')
                                ->get();

        //dd(\DB::getQueryLog());

        $pdf->SetFont('Arial', '', 12);
        $i = 1;

        foreach($inscricoes as $inscricao)
        {
            $pdf->Cell(5, 8, utf8_decode(''), 0, 0, 'C');
            $pdf->Cell(15, 8, utf8_decode($i), 1, 0, 'C');
            $pdf->Cell(25, 8, utf8_decode($inscricao->codpes), 1, 0, 'C');
            $pdf->Cell(80, 8, utf8_decode($inscricao->name), 1, 0, 'L');
            $pdf->Cell(60, 8, utf8_decode(''), 1, 0, 'C');
            $pdf->Cell(5, 8, utf8_decode(''), 0, 0, 'C');
            $pdf->Ln();
            $i++;
        }

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Ln(10);
        $pdf->Cell(50, 8, utf8_decode(''), 0, 0, 'L');
        $pdf->Cell(100, 8, utf8_decode(''), 'B', 0, 'C');
        $pdf->Cell(50, 8, utf8_decode(''), 0, 0, 'L');
        $pdf->Ln();

        $pdf->Cell(50, 8, utf8_decode(''), 0, 0, 'L');
        $pdf->Cell(100, 8, utf8_decode('Prof. Dr. Clodoaldo Saron'), 0, 0, 'C');
        $pdf->Cell(50, 8, utf8_decode(''), 0, 0, 'L');
        $pdf->Ln();

        $pdf->Cell(50, 8, utf8_decode(''), 0, 0, 'L');
        $pdf->Cell(100, 8, utf8_decode('Coordenador da CCP-PPGEM, EEL/USP'), 0, 0, 'C');
        $pdf->Cell(50, 8, utf8_decode(''), 0, 0, 'L');
        $pdf->Ln();


        $pdf->Output();
    }

    public static function mestrado_presenca(\App\Models\Comprovante $pdf, $codigoEdital)
    {
        //$inscricao = Inscricao::obterDadosPessoaisInscricao($codigoInscricao);

        $pdf->setCabecalhoPresenca('ppgem');
        $pdf->setPresenca('ppgem');
      
        $pdf->SetStyle('p', 'arial', 'N', 14, '0,0,0');
        $pdf->SetStyle('b', 'arial', 'B', 0, '0,0,0');
        $pdf->SetStyle('bu', 'arial', 'BU', 0, '0,0,0');
        $pdf->SetStyle('i', 'arial', 'I', 0, '0,0,0');
        
        $pdf->SetDisplayMode('real');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial','B', 14);
        $pdf->Ln(5);
        $pdf->Cell(190, 8, utf8_decode('Processo Seletivo Mestrado em Engenharia de Materiais'), 0, 0, 'C');            
        $pdf->Ln();
        $pdf->Cell(190, 8, utf8_decode('02/07/2024'), 0, 0, 'C'); 
        $pdf->Ln();
        $pdf->Ln();

        $pdf->SetFont('Arial','B', 12);
        $pdf->SetFillColor(190,190,190);
        $pdf->Cell(5, 10, utf8_decode(''), 0, 0, 'C');
        $pdf->Cell(15, 10, utf8_decode('Nº'), 1, 0, 'C', true);
        $pdf->Cell(80, 10, utf8_decode('Nome'), 1, 0, 'C', true);
        $pdf->Cell(35, 10, utf8_decode('Documento'), 1, 0, 'C', true);
        $pdf->Cell(55, 10, utf8_decode('Assinatura'), 1, 0, 'C', true);
        $pdf->Cell(5, 10, utf8_decode(''), 0, 0, 'C');
        $pdf->Ln();

        //\DB::enableQueryLog();

        $inscricoes = Inscricao::select(\DB::raw('users.*, documentos.*'))
                                ->join('inscricoes_documentos', 'inscricoes.codigoInscricao', '=', 'inscricoes_documentos.codigoInscricao')
                                ->join('documentos', 'documentos.codigoDocumento', '=', 'inscricoes_documentos.codigoDocumento')
                                ->join('users', 'users.id', '=', 'inscricoes.codigoUsuario')
                                ->where('inscricoes.codigoEdital',  $codigoEdital)
                                ->where('inscricoes.statusInscricao',  'C')
                                ->orderBy('users.name')
                                ->get();

        //dd(\DB::getQueryLog());

        $pdf->SetFont('Arial', '', 12);
        $i = 1;

        foreach($inscricoes as $inscricao)
        {
            $pdf->Cell(5, 10, utf8_decode(''), 0, 0, 'C');
            $pdf->Cell(15, 10, utf8_decode($i), 1, 0, 'C');
            $pdf->Cell(80, 10, utf8_decode($inscricao->name), 1, 0, 'L');
            $pdf->Cell(35, 10, utf8_decode($inscricao->numeroDocumento), 1, 0, 'L');
            $pdf->Cell(55, 10, utf8_decode(''), 1, 0, 'C');
            $pdf->Cell(5, 10, utf8_decode(''), 0, 0, 'C');
            $pdf->Ln();
            $i++;
        }

       /* $pdf->SetFont('Arial', 'B', 12);
        $pdf->Ln(10);
        $pdf->Cell(50, 8, utf8_decode(''), 0, 0, 'L');
        $pdf->Cell(100, 8, utf8_decode(''), 'B', 0, 'C');
        $pdf->Cell(50, 8, utf8_decode(''), 0, 0, 'L');
        $pdf->Ln();

        $pdf->Cell(50, 8, utf8_decode(''), 0, 0, 'L');
        $pdf->Cell(100, 8, utf8_decode('Prof. Dr. Clodoaldo Saron'), 0, 0, 'C');
        $pdf->Cell(50, 8, utf8_decode(''), 0, 0, 'L');
        $pdf->Ln();

        $pdf->Cell(50, 8, utf8_decode(''), 0, 0, 'L');
        $pdf->Cell(100, 8, utf8_decode('Coordenador da CCP-PPGEM, EEL/USP'), 0, 0, 'C');
        $pdf->Cell(50, 8, utf8_decode(''), 0, 0, 'L');
        $pdf->Ln();*/


        $pdf->Output();
    }

    public function primeira_matricula($codigoEdital)
    {
        $aprovados = array(211, 212, 213, 214, 219, 244, 272);

        foreach($aprovados as $aprovado)
        {
            $edital = Edital::obterEditalInscricao($aprovado);

            $pdf = new Matricula();

            if ($edital->codigoCurso == 97002)
            {
                $anexo = Inscricao::gerarMatricula($pdf, 'ppgem', $aprovado);
            }

            if ($edital->codigoCurso == 97004)
            {
                $anexo = Inscricao::gerarMatricula($pdf, 'ppgpe', $aprovado);
            }
        }
    }
}