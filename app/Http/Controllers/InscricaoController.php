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
use Codedge\Fpdf\Fpdf\Fpdf as Fpdf;
use Carbon\Carbon;
use Mail;
use App\Mail\ConfirmacaoMail;
use App\Mail\ComprovanteMail;
use App\Models\InscricoesArquivos;

class InscricaoController extends Controller
{
    /*protected $pdf;

    public function __construct(\App\Models\Comprovante $pdf)
    {
        $this->pdf = $pdf;
    }*/

    public function index()
    {
        if (Auth::user()->cpf == '99999999999')
        {    
            return redirect('admin/dados'); 
        }

        $editais = Edital::join('niveis', 'editais.codigoNivel', '=', 'niveis.codigoNivel')
                         ->whereRaw('NOW() > `dataInicioEdital` AND NOW() < `dataFinalEdital`')
                         //->whereRaw('`dataInicioEdital` > NOW()')
                         ->get();
        
        $liberados = array();

        if (empty(session('level')))
        {
            Utils::setSession(Auth::user()->id);
        }

        return view('dashboard',
        [
            'editais'   => $editais,
            'utils'     => new Utils,
            'inscricao' => new Inscricao,
            'user_id'   => Auth::user()->id,
            'liberado'  => (in_array(Auth::user()->id, $liberados) ? true : false),
            'level'     => session('level'),
            'total'     => count($editais),
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

        Utils::obterTotalInscricao($inscricao->codigoInscricao);
        $total = Utils::obterTotalArquivos($inscricao->codigoInscricao);

        $edital = Edital::join('niveis', 'editais.codigoNivel', '=', 'niveis.codigoNivel')
                        ->join('users', 'editais.codigoUsuario', '=', 'users.id')
                        ->where('editais.codigoEdital', $codigoEdital)->first();

        $sigla       = Utils::obterSiglaCurso($edital->codigoCurso);
        $anosemestre = Edital::obterSemestreAno($codigoEdital, true);

        session(['nivel' => $inscricao->codigoNivel]);

        return view('inscricao',
        [
            'codigoInscricao' => $inscricao->codigoInscricao,
            'status'          => $inscricao->statusInscricao,
            'codigoEdital'    => '',  
            'total'           => $total,
            'sigla'           => $sigla,
            'anosemestre'     => $anosemestre,
            'email'           => $edital->email,  
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
        $total = Utils::obterTotalArquivos($inscricao->codigoInscricao);

        $voltar = "inscricao/{$inscricao->codigoEdital}/pessoal";
    
        return view('pessoal',
        [
            'codigoInscricao'   => $codigoInscricao,
            'codigoEdital'      => $inscricao->codigoEdital,
            'link_voltar'       => $voltar,
            'arquivos'          => $arquivos,
            'pessoais'          => $inscricao,
            'status'            => $inscricao->statusInscricao,
            'arquivo_inscricao' => '',
            'nivel'             => session(['nivel']),
            'total'             => $total,
        ]); 
    }

    public function pessoal_create($codigoInscricao)
    {
        $inscricao = Inscricao::obterDadosPessoaisInscricao($codigoInscricao);

        if ($inscricao->statusInscricao == 'P')
        {
            return redirect("inscricao/{$inscricao->codigoEdital}"); 
        }
        
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
        $inscricao = Inscricao::obterEnderecoInscricao($codigoInscricao);
        Utils::obterTotalInscricao($codigoInscricao);
        $total = Utils::obterTotalArquivos($inscricao->codigoInscricao);

        $voltar = "inscricao/{$inscricao->codigoEdital}/endereco";
    
        return view('endereco',
        [
            'codigoInscricao'   => $codigoInscricao,
            'codigoEdital'      => $inscricao->codigoEdital,
            'link_voltar'       => $voltar,
            'enderecos'         => $inscricao,
            'nivel'             => session(['nivel']),
            'status'            => $inscricao->statusInscricao,
            'total'             => $total,
        ]); 
    }  
    
    public function endereco_create($codigoInscricao)
    {
        $inscricao = Inscricao::obterEnderecoInscricao($codigoInscricao);
        $update    = false;

        if ($inscricao->statusInscricao == 'P')
        {
            return redirect("inscricao/{$inscricao->codigoEdital}"); 
        }

        if (!empty($inscricao->codigoEndereco))
        {
            $update = true;
        }
        
        $estados = Utils::listarEstado(1);

        return view('inscricao.endereco',
        [
            'codigoInscricao'   => $codigoInscricao, 
            'codigoEdital'      => $inscricao->codigoEdital,    
            'enderecos'         => $inscricao,
            'estados'           => $estados,
            'update'            => $update,
        ]); 
    }

    public function emergencia($codigoInscricao)
    {         
        $inscricao = Inscricao::obterEmergenciaInscricao($codigoInscricao);
        Utils::obterTotalInscricao($codigoInscricao);
        $total = Utils::obterTotalArquivos($inscricao->codigoInscricao);
        $endereco = '';

        if (!empty($inscricao->codigoEmergenciaEndereco))
        {
            $endereco = Endereco::find($inscricao->codigoEmergenciaEndereco);
        }

        $voltar = "inscricao/{$inscricao->codigoEdital}/emergencia";
    
        return view('emergencia',
        [
            'codigoInscricao'   => $codigoInscricao,
            'codigoEdital'      => $inscricao->codigoEdital,
            'link_voltar'       => $voltar,
            'emergencia'        => $inscricao,
            'nivel'             => session(['nivel']),
            'status'            => $inscricao->statusInscricao,
            'total'             => $total,
            'endereco'          => $endereco,
        ]); 
    } 
    
    public function emergencia_create($codigoInscricao)
    {
        $inscricao = Inscricao::obterEmergenciaInscricao($codigoInscricao);
        $endereco  = Endereco::find($inscricao->codigoEmergenciaEndereco);

        return view('inscricao.emergencia',
        [
            'codigoInscricao'           => $codigoInscricao, 
            'codigoEdital'              => $inscricao->codigoEdital,
            'codigoInscricaoEndereco'   => $inscricao->codigoInscricaoEndereco,                     
            'emergencia'                => $inscricao,
            'endereco'                  => $endereco,
        ]); 
    }   
    
    public function escolar($codigoInscricao)
    {         
        $codigoEdital = Inscricao::obterEditalInscricao($codigoInscricao);
        $escolares    = Inscricao::obterEscolarInscricao($codigoInscricao);
        Utils::obterTotalInscricao($codigoInscricao);
        $total = Utils::obterTotalArquivos($codigoInscricao);

        $voltar = "inscricao/{$codigoEdital}/escolar";
    
        return view('escolar',
        [
            'codigoInscricao'   => $codigoInscricao,
            'codigoEdital'      => $codigoEdital,
            'link_voltar'       => $voltar,
            'escolares'         => $escolares,
            'nivel'             => session(['nivel']),
            'status'            => $escolares[0]->statusInscricao,
            'total'             => $total,
        ]); 
    } 
    
    public function escolar_create($codigoInscricao, $codigoResumoEscolar = '')
    {
        $codigoEdital = Inscricao::obterEditalInscricao($codigoInscricao);
        $escolares    = Inscricao::obterEscolarInscricao($codigoInscricao, $codigoResumoEscolar);

        if ($escolares[0]->statusInscricao == 'P')
        {
            return redirect("inscricao/{$codigoEdital}"); 
        }

        return view('inscricao.escolar',
        [
            'codigoInscricao'       => $codigoInscricao, 
            'codigoEdital'          => $codigoEdital,
            'escolar'               => $escolares[0],
            'codigoResumoEscolar'   => $codigoResumoEscolar,
        ]); 
    } 

    public function idioma($codigoInscricao)
    {         
        $inscricao = Inscricao::obterIdiomaInscricao($codigoInscricao);
        Utils::obterTotalInscricao($codigoInscricao);
        $total = Utils::obterTotalArquivos($codigoInscricao);
        $voltar = "inscricao/{$inscricao[0]->codigoEdital}/idioma";
    
        return view('idioma',
        [
            'codigoInscricao'   => $codigoInscricao,
            'codigoEdital'      => $inscricao[0]->codigoEdital,
            'link_voltar'       => $voltar,
            'idiomas'           => $inscricao,
            'nivel'             => session(['nivel']),
            'status'            => $inscricao[0]->statusInscricao,
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
        $total = Utils::obterTotalArquivos($codigoInscricao);

        $voltar = "inscricao/{$codigoEdital}/profissional";
    
        return view('profissional',
        [
            'codigoInscricao'   => $codigoInscricao,
            'codigoEdital'      => $codigoEdital,
            'link_voltar'       => $voltar,
            'profissionais'     => $inscricao,
            'nivel'             => session(['nivel']),
            'status'            => $inscricao[0]->statusInscricao,
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
        ]); 
    } 

    public function ensino($codigoInscricao)
    {         
        $codigoEdital = Inscricao::obterEditalInscricao($codigoInscricao);
        $inscricao    = Inscricao::obterEnsinoInscricao($codigoInscricao); 

        Utils::obterTotalInscricao($codigoInscricao);
        $total = Utils::obterTotalArquivos($codigoInscricao);

        $voltar = "inscricao/{$codigoEdital}/ensino";
    
        return view('ensino',
        [
            'codigoInscricao'   => $codigoInscricao,
            'codigoEdital'      => $codigoEdital,
            'link_voltar'       => $voltar,
            'ensinos'           => $inscricao,
            'nivel'             => session(['nivel']),
            'status'            => (isset($inscricao[0]->statusInscricao) ? $inscricao[0]->statusInscricao : 'N'),
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
        ]); 
    } 

    public function financeiro($codigoInscricao)
    {         
        $inscricao = Inscricao::obterFinanceiroInscricao($codigoInscricao);
      
        Utils::obterTotalInscricao($codigoInscricao);
        $total = Utils::obterTotalArquivos($codigoInscricao);

        $voltar = "inscricao/{$inscricao->codigoEdital}/financeiro";
    
        return view('financeiro',
        [
            'codigoInscricao'   => $codigoInscricao,
            'codigoEdital'      => $inscricao->codigoEdital,
            'link_voltar'       => $voltar,
            'financeiros'       => $inscricao,
            'nivel'             => session(['nivel']),
            'status'            => $inscricao->statusInscricao,
            'total'             => $total,
        ]); 
    } 
    
    public function financeiro_create($codigoInscricao)
    {
        $inscricao = Inscricao::obterFinanceiroInscricao($codigoInscricao);

        if ($inscricao->statusInscricao == 'P')
        {
            return redirect("inscricao/{$inscricao->codigoEdital}"); 
        }

        return view('inscricao.financeiro',
        [
            'codigoInscricao'   => $codigoInscricao, 
            'codigoEdital'      => $inscricao->codigoEdital,
            'financeiros'       => $inscricao, 
        ]); 
    } 

    public function expectativas($codigoInscricao)
    {         
        $inscricao = Inscricao::obterExpectativaInscricao($codigoInscricao);
      
        Utils::obterTotalInscricao($codigoInscricao);
        $total = Utils::obterTotalArquivos($codigoInscricao);

        $voltar = "inscricao/{$inscricao->codigoEdital}/expectativas";
    
        return view('expectativas',
        [
            'codigoInscricao'   => $codigoInscricao,
            'codigoEdital'      => $inscricao->codigoEdital,
            'link_voltar'       => $voltar,
            'expectativas'      => $inscricao,
            'nivel'             => session(['nivel']),
            'status'            => $inscricao->statusInscricao,
            'total'             => $total,
        ]); 
    } 

    public function expectativas_create($codigoInscricao)
    {         
        $inscricao = Inscricao::obterExpectativaInscricao($codigoInscricao);

        if ($inscricao->statusInscricao == 'P')
        {
            return redirect("inscricao/{$inscricao->codigoEdital}"); 
        }
      
        Utils::obterTotalInscricao($codigoInscricao);
        $total = Utils::obterTotalArquivos($inscricao->codigoInscricao);

        $voltar = "inscricao/{$inscricao->codigoEdital}/expectativas";
    
        return view('inscricao.expectativas',
        [
            'codigoInscricao'   => $codigoInscricao,
            'codigoEdital'      => $inscricao->codigoEdital,
            'link_voltar'       => $voltar,
            'expectativas'      => $inscricao,
            'total'             => $total,
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
        $codigoEdital = Inscricao::obterEditalInscricao($codigoInscricao);
        $inscricao = Inscricao::obterCurriculoInscricao($codigoInscricao);
      
        Utils::obterTotalInscricao($codigoInscricao);
        $total = Utils::obterTotalArquivos($codigoInscricao);

        $voltar = "inscricao/{$codigoEdital}/curriculo";
    
        return view('curriculo',
        [
            'codigoInscricao'   => $codigoInscricao,
            'codigoEdital'      => $codigoEdital,
            'link_voltar'       => $voltar,
            'curriculo'         => $inscricao,
            'nivel'             => session(['nivel']),
            'total'             => $total,
        ]); 
    } 

    public function curriculo_create($codigoInscricao)
    {         
        $inscricao = Inscricao::obterExpectativaInscricao($codigoInscricao);
        $tipos     = TipoDocumento::whereIn('codigoTipoDocumento', [8,9])->get();

        if ($inscricao->statusInscricao == 'P')
        {
            return redirect("inscricao/{$inscricao->codigoEdital}"); 
        }
      
        Utils::obterTotalInscricao($codigoInscricao);

        $voltar = "inscricao/{$inscricao->codigoEdital}/curriculo";
    
        return view('inscricao.curriculo',
        [
            'codigoInscricao'   => $codigoInscricao,
            'codigoEdital'      => $inscricao->codigoEdital,
            'link_voltar'       => $voltar,
            'curriculo'         => $inscricao,
            'tipos'             => $tipos,
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
        $total = Utils::obterTotalArquivos($codigoInscricao);

        $voltar = "inscricao/{$codigoEdital}/pre-projeto";
    
        return view('pre_projeto',
        [
            'codigoInscricao'   => $codigoInscricao,
            'codigoEdital'      => $codigoEdital,
            'link_voltar'       => $voltar,
            'projeto'           => $inscricao,
            'nivel'             => session(['nivel']),
            'total'             => $total,
        ]); 
    } 

    public function projeto_create($codigoInscricao)
    {         
        $inscricao = Inscricao::obterExpectativaInscricao($codigoInscricao);

        if ($inscricao->statusInscricao == 'P')
        {
            return redirect("inscricao/{$inscricao->codigoEdital}"); 
        }
      
        Utils::obterTotalInscricao($codigoInscricao);

        $voltar = "inscricao/{$inscricao->codigoEdital}/pre-projeto";
    
        return view('inscricao.pre_projeto',
        [
            'codigoInscricao'   => $codigoInscricao,
            'codigoEdital'      => $inscricao->codigoEdital,
            'link_voltar'       => $voltar,
            'projeto'           => $inscricao,
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

    public function requerimento($codigoInscricao)
    {         
        $codigoEdital = Inscricao::obterEditalInscricao($codigoInscricao);
        $inscricao    = Inscricao::obterRequerimentoInscricao($codigoInscricao);
      
        Utils::obterTotalInscricao($codigoInscricao);
        $total = Utils::obterTotalArquivos($codigoInscricao);

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

    public function requerimento_store(Request $request)
    { 
        $codigoEdital = Inscricao::obterEditalInscricao($request->codigoInscricao);
        $edital       = Edital::join('niveis', 'editais.codigoNivel', '=', 'niveis.codigoNivel')->where('editais.codigoEdital', $codigoEdital)->first();

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
       
        Mail::to('ppgem@eel.usp.br')->send(new ComprovanteMail($request->codigoInscricao));
        
        if (Mail::failures()) 
        {
            request()->session()->flash('alert-danger', "Ocorreu um erro no cadastrado do Requerimento.");
        }    
        else
        {
            Inscricao::where('codigoInscricao', $request->codigoInscricao)->where('statusInscricao', 'N')->update(['statusInscricao' => 'P']);

            request()->session()->flash('alert-success', 'Requerimento cadastrado com sucesso'); 
        } 
    
        return redirect("inscricao/{$request->codigoInscricao}/requerimento"); 
    }

    public function bolsista($codigoInscricao)
    {         
        $codigoEdital = Inscricao::obterEditalInscricao($codigoInscricao);
           
        Utils::obterTotalInscricao($codigoInscricao);
        $total = Utils::obterTotalArquivos($codigoInscricao);

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


    public static function comprovante(\App\Models\Comprovante $pdf, $codigoInscricao)
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
            $eixoy    = 77;            
            $eixofoto = 71; 
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

        $pdf->Ln();
        $pdf->SetFont("Arial","B", 10);
        $pdf->Cell(10, 8, utf8_decode("2."), 1, 0, "L", true);
        $pdf->Cell(180, 8, utf8_decode("PESSOA A SER NOTIFICADA EM CASO DE EMERGÊNCIA:"), "1", 0, "J", true);

        $emergencias = Inscricao::obterEmergenciaInscricao($codigoInscricao);

        $pdf->Ln();
        $pdf->SetFont("Arial", "B", 10);
        $pdf->Cell(13, 8, utf8_decode("Nome:"), "LB",  0, "L", false);
        $pdf->SetFont("Arial", "", 10);
        $pdf->Cell(177, 8, utf8_decode($emergencias->nomePessoaEmergencia), "BR",  0, "L", false);

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(18, 8, utf8_decode('Endereço:'), 'LB',  0, 'L', false);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(172, 8, utf8_decode("{$emergencias->logradouroEndereco}, {$emergencias->numeroEndereco} {$emergencias->complementoEndereco}"), 'BR',  0, 'L', false);

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(13, 8, utf8_decode('Bairro:'), 'LB',  0, 'L', false);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(60, 8, utf8_decode($emergencias->bairroEndereco), 'B',  0, 'L', false);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(17, 8, utf8_decode('Telefone:'), 'B',  0, 'L', false);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(100, 8, $emergencias->telefonePessoaEmergencia, 'BR',  0, 'L', false);

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(9, 8, utf8_decode('CEP:'), 'LB',  0, 'L', false);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(46, 8, $emergencias->cepEndereco, 'B',  0, 'L', false);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(18, 8, utf8_decode('Cidade:'), 'B',  0, 'R', false);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(52, 8, utf8_decode($emergencias->localidadeEndereco), 'B',  0, 'L', false);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(25, 8, utf8_decode('Estado:'), 'B',  0, 'R', false);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(40, 8, $emergencias->ufEndereco, 'BR',  0, 'L', false);

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

        if (!empty($ensinos->codigoExperiencia))
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

        if ($edital->codigoNivel == 4)
        {            
            $assunto      = "MESTRADO - {$sigla}";
            $curso        = 'Seleção do Curso de Mestrado para ingresso no '.$semestre;
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
                    'codigoInscricao'       => $codigoInscricao,
                    'codigoArquivo'         => $arquivo->codigoArquivo,
                    'codigoPessoaAlteracao' => Auth::user()->codpes,
                ]);
            }
        }

        $pdf->Output('I', "{$pessoais->numeroInscricao}.pdf");
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
        }*/

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

        return view('inscricao.visualizar.index',
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
    }

    public function validar(Request $request, $codigoInscricao)
    {
        $inscricao = Inscricao::join('users', 'inscricoes.codigoUsuario', '=', 'users.id')->where('codigoInscricao', $codigoInscricao)->first();

        Mail::to($inscricao->email)->send(new ConfirmacaoMail($codigoInscricao));
        
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