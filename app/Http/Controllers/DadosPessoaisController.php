<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DadosPessoais;
use App\Models\Utils;
use App\Models\Inscricao;
use App\Models\Endereco;
use App\Models\Arquivo;
use App\Models\User;
use App\Models\Documento;
use App\Models\TipoDocumento;
use App\Models\InscricoesPessoais;
use App\Models\InscricoesArquivos;
use App\Models\InscricoesDocumentos;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ArquivoRequest;
use App\Models\Views\ViewArquivos;

class DadosPessoaisController extends Controller
{
    public function index()
    {
        $pessoais = DadosPessoais::where('codigoUsuario', Auth::user()->id);
        
        return view('pessoal.index',
        [
            'pessoais' => $pessoais,
            'utils'    => new Utils
        ]);        
    }

    public function create($id = '')
    {
        $arquivos = '';
        $voltar = '/pessoal';
        $paises  = Utils::listarPais();
        $estados = Utils::listarEstado(1);
        $tipos   = TipoDocumento::listarTipoDocumentosPessoal();

        if (!empty($id))
        {
            $inscricao = Inscricao::where('codigoUsuario', Auth::user()->id)->where('codigoInscricao', $id)->first();         
            $arquivos  = Arquivo::listarArquivos(Auth::user()->id, array(1,2,3,4), $id);
            $voltar = "inscricao/{$inscricao->codigoEdital}/pessoal";
        }
    
        return view('pessoal.create',
        [
            'codigoInscricao'   => $id,
            'link_voltar'       => $voltar,
            'sexos'             => Utils::obterDadosSysUtils('sexo'),
            'racas'             => Utils::obterDadosSysUtils('raça'),
            'estados_civil'     => Utils::obterDadosSysUtils('civil'),
            'especiais'         => Utils::obterDadosSysUtils('especial'),
            'arquivos'          => $arquivos,
            'paises'            => $paises,
            'estados'           => $estados,
        ]); 
    }

    public function store(Request $request)
    {
        $temp = '';
        $voltar = '/pessoal';
        $inscricaoPessoal    = true;
        $inscricaoDocumentos = true;

        \DB::beginTransaction();

        /* Atualiza os dados do Usuário */
        $user = User::find(Auth::user()->id);
        $user->name     = $request->name;
        /*$user->email    = $request->email;*/
        $user->rg       = $request->rg;
        $user->telefone = $request->telefone;
        
        if(Auth::user()->cpf == Auth::user()->codpes)
        {
            $user->cpf = $request->cpf;
        }
        
        $user->save();

        /* Cadastra os dados pessoais */
        //$pais = Utils::obterPais($request->paisPessoal);

        if($request->especialPessoal == 'S')
        {
            $request->tipoEspecialPessoal = Utils::obterTipoEspecial($request->tipoEspecialPessoal);
        }

        $pessoal = DadosPessoais::create([
            'codigoUsuario'         => Auth::user()->id,
            'dataNascimentoPessoal' => $request->dataNascimentoPessoal,
            'sexoPessoal'           => $request->sexoPessoal,
            //'estadoCivilPessoal'    => $request->estadoCivilPessoal,
            'naturalidadePessoal'   => $request->naturalidadePessoal,
            'estadoPessoal'         => $request->estadoPessoal,
            'paisPessoal'           => $request->paisPessoal,
            //'dependentePessoal'     => $request->dependentePessoal,
            'racaPessoal'           => $request->racaPessoal,
            'especialPessoal'       => $request->especialPessoal,
            'tipoEspecialPessoal'   => $request->tipoEspecialPessoal,
            'codigoPessoaAlteracao' => Auth::user()->codpes,
        ]);

        /* Cadastra o documento */
        $documento = Documento::create([
            'codigoUsuario'         => Auth::user()->id,
            'tipoDocumento'         => $request->tipoDocumento,
            //'numeroRG'              => $request->rg,
            //'ufEmissorRG'           => $request->ufEmissorRG,
            //'orgaoEmissorRG'        => $request->orgaoEmissorRG,
            'dataEmissaoRG'         => $request->dataEmissaoRG,
            'numeroDocumento'       => $request->numeroDocumento,
            'codigoPessoaAlteracao' => Auth::user()->codpes,
        ]);

        if(!empty($request->codigoInscricao))
        {
            $inscricaoPessoal = InscricoesPessoais::create([
                'codigoInscricao'       => $request->codigoInscricao,
                'codigoPessoal'         => $pessoal->codigoPessoal,
                'codigoPessoaAlteracao' => Auth::user()->codpes,
            ]);

            $inscricaoDocumentos = InscricoesDocumentos::create([
                'codigoInscricao'       => $request->codigoInscricao,
                'codigoDocumento'       => $documento->codigoDocumento,
                'codigoPessoaAlteracao' => Auth::user()->codpes,
            ]);

            /*if (!empty($request->codigoArquivoInscricao))
            {
                $temp = explode('|', $request->codigoArquivoInscricao);

                for($i = 0; $i < count($temp) - 1; $i++)
                {
                    $inscricaoDocumentos = InscricoesArquivos::create([
                        'codigoInscricao'       => $request->codigoInscricao,
                        'codigoArquivo'         => $temp[$i],
                        'codigoPessoaAlteracao' => Auth::user()->codpes,
                    ]);
                }
            }*/

            $voltar = "inscricao/{$request->codigoInscricao}/pessoal";
        }

        if($user && $pessoal && $documento && $inscricaoPessoal && $inscricaoDocumentos) 
        {
            \DB::commit();
        } 
        else 
        {
            \DB::rollBack();
        }

        request()->session()->flash('alert-success', 'Dados pessoais cadastrado com sucesso');    
        
        return redirect($voltar);
    }
 
    public function show(DadosPessoais $dadosPessoais)
    {

    }
    
    function edit($id, $inscricao = '')
    {
        if (!empty($inscricao))
        {
            $temp = Inscricao::where('codigoUsuario', Auth::user()->id)->where('codigoInscricao', $inscricao)->first();
            $voltar = "inscricao/{$inscricao}/pessoal/";
        }
        else
        {
            $voltar = 'pessoal';
        }

        $pessoal = DadosPessoais::where('codigoPessoal', $id)->first();
        $paises  = Utils::listarPais();
        $estados = Utils::listarEstado(1);
        $tipos   = TipoDocumento::listarTipoDocumentosPessoal();

        $arquivos = Arquivo::join('tipo_documentos', 'arquivos.codigoTipoDocumento', '=', 'tipo_documentos.codigoTipoDocumento')
                                    ->where('arquivos.codigoUsuario', Auth::user()->id)
                                    ->where('arquivos.codigoTipoDocumento', '<=', 4)->get();

        return view('pessoal.edit',
        [
            'codigoInscricao'   => $inscricao,
            'link_voltar'       => $voltar,
            'pessoais'          => $pessoal,
            'sexos'             => Utils::obterDadosSysUtils('sexo'),
            'racas'             => Utils::obterDadosSysUtils('raça'),
            'estados_civil'     => Utils::obterDadosSysUtils('civil'),
            'especiais'         => Utils::obterDadosSysUtils('especial'),            
            'paises'            => $paises,
            'estados'           => $estados,
            'arquivos'          => $arquivos,
            'arquivo_inscricao' => '',
        ]);
    }

    public function update(Request $request, DadosPessoais $dadosPessoais)
    {
        $temp = '';
        $voltar = '/pessoal';
        $inscricaoPessoal    = true;
        $inscricaoDocumentos = true;

        \DB::beginTransaction();

        /* Atualiza os dados do Usuário */
        $user = User::find(Auth::user()->id);
        $user->name  = $request->name;
        /*$user->email = $request->email;*/
        $user->telefone = $request->telefone;
        
        if(Auth::user()->cpf == Auth::user()->codpes)
        {
            $user->cpf = $request->cpf;
        }
        
        $user->save();

        /* Atualiza os dados pessoais */
        if($request->especialPessoal == 'S')
        {
            $request->tipoEspecialPessoal = Utils::obterTipoEspecial($request->tipoEspecialPessoal);
        }
        
        $pessoal = DadosPessoais::find($request->codigoPessoal);
        $pessoal->codigoUsuario         = Auth::user()->id;
        $pessoal->dataNascimentoPessoal = $request->dataNascimentoPessoal;
        $pessoal->sexoPessoal           = $request->sexoPessoal;
        //$pessoal->estadoCivilPessoal    = $request->estadoCivilPessoal;
        $pessoal->naturalidadePessoal   = $request->naturalidadePessoal;
        $pessoal->estadoPessoal         = $request->estadoPessoal;
        $pessoal->paisPessoal           = $request->paisPessoal;
       // $pessoal->dependentePessoal     = $request->dependentePessoal;
        $pessoal->racaPessoal           = $request->racaPessoal;
        $pessoal->especialPessoal       = $request->especialPessoal;
        $pessoal->tipoEspecialPessoal   = $request->tipoEspecialPessoal;
        $pessoal->codigoPessoaAlteracao = Auth::user()->codpes;
        $pessoal->save();

        $documento = Documento::find($request->codigoDocumento);

        if(empty($documento))
        {
            $documento = Documento::create([
                'codigoUsuario'         => Auth::user()->id,
                'tipoDocumento'         => $request->tipoDocumento,
                //'numeroRG'              => $request->rg,
                //'ufEmissorRG'           => $request->ufEmissorRG,
                //'orgaoEmissorRG'        => $request->orgaoEmissorRG,
                'dataEmissaoRG'         => $request->dataEmissaoRG,
                'numeroDocumento'       => $request->numeroDocumento,
                'codigoPessoaAlteracao' => Auth::user()->codpes,
            ]);
        }
        else
        {
            $documento->codigoUsuario         = Auth::user()->id;
            $documento->tipoDocumento         = $request->tipoDocumento;
            //$documento->numeroRG              = $request->rg;
           // $documento->ufEmissorRG           = $request->ufEmissorRG;
           // $documento->orgaoEmissorRG        = $request->orgaoEmissorRG;
            $documento->dataEmissaoRG         = $request->dataEmissaoRG;
            $documento->numeroDocumento       = $request->numeroDocumento;
            $documento->codigoPessoaAlteracao = Auth::user()->codpes;
            $documento->save();
        }

        if(!empty($request->codigoInscricao))
        {
            if(empty($request->codigoInscricaoPessoal))
            {
                $inscricaoPessoal = InscricoesPessoais::create([
                    'codigoInscricao'       => $request->codigoInscricao,
                    'codigoPessoal'         => $pessoal->codigoPessoal,
                    'codigoPessoaAlteracao' => Auth::user()->codpes,
                ]);
            }
            else
            {
                $inscricaoPessoal = InscricoesPessoais::find($request->codigoInscricaoPessoal);                
                $inscricaoPessoal->codigoInscricao       = $request->codigoInscricao;
                $inscricaoPessoal->codigoPessoal         = $request->codigoPessoal;
                $inscricaoPessoal->codigoPessoaAlteracao = Auth::user()->codpes;
                $inscricaoPessoal->save();
            }

            if(empty($request->codigoInscricaoDocumento))
            {                
                $inscricaoDocumentos = InscricoesDocumentos::create([
                    'codigoInscricao'       => $request->codigoInscricao,
                    'codigoDocumento'       => $documento->codigoDocumento,
                    'codigoPessoaAlteracao' => Auth::user()->codpes,
                ]);
            }
            else
            {
                $inscricaoDocumento = InscricoesDocumentos::find($request->codigoInscricaoDocumento);
                        
                $inscricaoDocumento->codigoInscricao       = $request->codigoInscricao;
                $inscricaoDocumento->codigoDocumento       = $request->codigoDocumento;
                $inscricaoDocumento->codigoPessoaAlteracao = Auth::user()->codpes;
                $inscricaoDocumento->save();
            }

            $voltar = "inscricao/{$request->codigoInscricao}/pessoal";
        }

        if($user && $pessoal && $documento && $inscricaoPessoal && $inscricaoDocumentos) 
        {
            \DB::commit();
        } 
        else 
        {
            \DB::rollBack();
        }

        request()->session()->flash('alert-success', 'Dados pessoais cadastrado com sucesso');    
        
        return redirect($voltar);
    }

    public function anexo($id = '')
    {
        $arquivos = '';

        if (!empty($id))
        {
            $inscricao = Inscricao::where('codigoUsuario', Auth::user()->id)->where('codigoInscricao', $id)->first();
            $voltar = "/inscricao/{$id}/pessoal";
        }
        else
        {
            $voltar = 'pessoal';
        }

        $tipos   = TipoDocumento::listarTipoDocumentosPessoal();
    
        return view('pessoal.anexos',
        [
            'codigoInscricao'   => $id,
            'link_voltar'       => $voltar,
            'tipos'             => $tipos,
        ]);
    }

    public function anexo_salvar(ArquivoRequest $request)
    {
        $validated = $request->validated();

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

        request()->session()->flash('alert-success', 'Documento cadastrado com sucesso');    
        return redirect($request->linkVoltar);
    }

    public function inscricao(Request $request)
    {
        dd($request);
    }
}
