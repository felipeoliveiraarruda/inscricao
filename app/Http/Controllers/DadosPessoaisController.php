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
use App\Models\InscricoesPessoais;
use App\Models\InscricoesArquivos;
use App\Models\InscricoesDocumentos;
use Illuminate\Support\Facades\Auth;

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

        if (!empty($id))
        {
            $inscricao = Inscricao::where('codigoUsuario', Auth::user()->id)->where('codigoInscricao', $id)->first();
            $voltar = "inscricao/{$inscricao->codigoEdital}";

            $arquivos = Arquivo::join('tipo_documentos', 'arquivos.codigoTipoDocumento', '=', 'tipo_documentos.codigoTipoDocumento')
                               ->where('arquivos.codigoUsuario', Auth::user()->id)
                               ->where('arquivos.codigoTipoDocumento', '<=', 4)->get();
        }
        else
        {
            $voltar = 'pessoal';
        }

        $paises  = Utils::listarPais();
        $estados = Utils::listarEstado(1);
    
        return view('pessoal.create',
        [
            'codigoInscricao'   => $id,
            'link_voltar'       => $voltar,
            'sexos'             => Utils::obterDadosSysUtils('sexo'),
            'racas'             => Utils::obterDadosSysUtils('raça'),
            'estados_civil'     => Utils::obterDadosSysUtils('civil'),
            'especiais'         => Utils::obterDadosSysUtils('especial'),
            'arquivos'          => $arquivos,
            'arquivo_inscricao' => '',
            'paises'            => $paises,
            'estados'           => $estados,
        ]); 
    }

    public function store(Request $request)
    {
        $voltar = '/pessoal';

        /* Atualiza os dados do Usuário */
        $user = User::find(Auth::user()->id);
        $user->name  = $request->name;
        $user->email = $request->email;
        $user->rg    = $request->rg;
        $user->save();

        /* Cadastra os dados pessoais */
        $pais = Utils::obterPais($request->paisPessoal);

        $pessoal = DadosPessoais::create([
            'codigoUsuario'         => Auth::user()->id,
            'dataNascimentoPessoal' => $request->dataNascimentoPessoal,
            'sexoPessoal'           => $request->sexoPessoal,
            'estadoCivilPessoal'    => $request->estadoCivilPessoal,
            'natualidadePessoal'    => $request->natualidadePessoal,
            'estadoPessoal'         => $request->estadoPessoal,
            'paisPessoal'           => $pais['nompas'],
            'dependentePessoal'     => $request->dependentePessoal,
            'racaPessoal'           => $request->racaPessoal,
            'especialPessoal'       => $request->especialPessoal,
            'tipoEspecialPessoal'   => $request->tipoEspecialPessoal,
            'codigoPessoaAlteracao' => Auth::user()->codpes,
        ]);

        /* Cadastra o documento */
        $documento = Documento::create([
            'codigoUsuario'         => Auth::user()->id,
            'numeroRG'              => $request->rg,
            'ufEmissorRG'           => $request->ufEmissorRG,
            'orgaoEmissorRG'        => $request->orgaoEmissorRG,
            'dataEmissaoRG'         => $request->dataEmissaoRG,
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
                'codigoDocumento'       => $pessoal->codigoDocumento,
                'codigoPessoaAlteracao' => Auth::user()->codpes,
            ]);

            for($i = 0; $i < count($temp) - 1; $i++)
            {
                $inscricaoDocumentos = InscricoesArquivos::create([
                    'codigoInscricao'       => $request->codigoInscricao,
                    'codigoArquivo'         => $temp[$i],
                    'codigoPessoaAlteracao' => Auth::user()->codpes,
                ]);
            }

            $voltar = "inscricao/{$request->codigoInscricao}/pessoal";
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

        return view('pessoal.edit',
        [
            'codigoInscricao'   => $inscricao,
            'link_voltar'       => $voltar,
            'sexos'             => Utils::obterDadosSysUtils('sexo'),
            'racas'             => Utils::obterDadosSysUtils('raça'),
            'estados_civil'     => Utils::obterDadosSysUtils('civil'),
        ]);
    }

    public function update(Request $request, DadosPessoais $dadosPessoais)
    {
        dd($request);
    }
}
