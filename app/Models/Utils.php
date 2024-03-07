<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use App\Models\Inscricao;
use App\Models\InscricoesPessoais;
use App\Models\InscricoesDocumentos;
use App\Models\InscricoesEnderecos;
use App\Models\InscricoesArquivos;
use App\Models\InscricoesResumoEscolar;
use App\Models\InscricoesIdiomas;
use App\Models\InscricoesExperiencias;
use App\Models\InscricoesRecursosFinanceiros;
use App\Models\InscricoesDisciplinas;
use App\Models\Arquivo;
use Uspdev\Replicado\Posgraduacao;

class Utils extends Model
{
    use HasFactory;

    public static function listarCurso()
    {
        $link = env('URL_API_EEL')."posgraduacao/curso/listar";

        $response = Http::withHeaders(
        [
            'x-api-key' => env('KEY_API_EEL')
        ])->post($link);

        return $response->json();
    }

    public static function obterCurso($codcur)
    {
        $link = env('URL_API_EEL')."posgraduacao/curso/obter/";

        $response = Http::asForm()->withHeaders(
        [
            'x-api-key' => env('KEY_API_EEL')
        ])->post($link,
        [
            'codcur' => $codcur
        ]);

        $temp = $response->json(); 
 
        return $temp[0];
    }

    public static function obterSiglaCurso($codcur)
    {
        switch ($codcur) 
        {
            case 97001:
                return 'PPGBI';
                break;
            case 97002:
                return 'PPGEM';
                break;
            case 97003:
                return 'PPGEQ';
                break;
            case 97004:
                return 'PPGPE';
                break;                                                
        }
    } 
    
    public static function obterCodigoCursoPorEmail($email)
    {
        switch ($email) 
        {
            case 'ppgbi@eel.usp.br':
                return 97001;
                break;
            case 'ppgem-eel@usp.br':
                return 97002;
                break;
            case 'ppgeq@eel.usp.br':
                return 97003;
                break;
            case 'ppgpe@eel.usp.br':
                return 97004;
                break;                                                
        }
    } 

    public static function obterNivelEdital($nivel)
    {
        switch ($nivel) 
        {
            case 'AE':
                return 'Aluno Especial';
                break;
            case 'DD':
                return 'Doutorado Direto';
                break;
            case 'DF':
                return 'Doutorado Fluxo Direto';
                break;
            case 'ME':
                return 'Mestrado';
                break; 
            case 'PA':
                return 'PAE';
                break;                                              
        }
    }

    public static function obterEndereco($cep)
    {
        $cep = preg_replace('/[^0-9]/', '', $cep);

        $link = "https://viacep.com.br/ws/{$cep}/json/";

        $response = Http::get($link);

        $temp = $response->json(); 
 
        return $temp;
    }

    public static function obterDadosSysUtils($tipo)
    {
        if($tipo == 'sexo')
        {
            $coluna = 'dadosSexo';
        }
        
        if ($tipo == 'raça')
        {
            $coluna = 'dadosRaca';
        }

        if ($tipo == 'civil')
        {
            $coluna = 'dadosEstadoCivil';
        }

        if ($tipo == 'especial')
        {
            $coluna = 'dadosNecessidadeEspecial';
        }

        if ($tipo == 'idioma')
        {
            $coluna = 'dadosIdioma';
        }

        $dados = DB::table('sys_utils')->select($coluna)->first();
        return Str::of($dados->$coluna)->explode('|');
    }

    public static function listarPais()
    {
        $link = env('URL_API_EEL')."/comum/pais/listar";

        $response = Http::withHeaders(
        [
            'x-api-key' => env('KEY_API_EEL')
        ])->post($link);

        return $response->json();
    }

    public static function obterPais($codpas)
    {
        $link = env('URL_API_EEL')."/comum/pais/obter";

        $response = Http::asForm()->withHeaders(
        [
            'x-api-key' => env('KEY_API_EEL')
        ])->post($link,
        [
            'codpas' => $codpas
        ]);

        return $response[0];
    }

    public static function listarEstado($codpas)
    {
        $link = env('URL_API_EEL')."/comum/estado/listar";

        $response = Http::asForm()->withHeaders(
        [
            'x-api-key' => env('KEY_API_EEL')
        ])->post($link,
        [
            'codpas' => $codpas
        ]);

        return $response->json();
    }

    public static function listarLocalidades($codpas, $sglest)
    {
        $link = env('URL_API_EEL')."/comum/localidade/listar";

        $response = Http::asForm()->withHeaders(
        [
            'x-api-key' => env('KEY_API_EEL')
        ])->post($link,
        [
            'codpas' => $codpas,
            'sglest' => $sglest
        ]);

        return $response->json();
    }

    public static function obterLocalidade($codloc)
    {
        $link = env('URL_API_EEL')."/comum/localidade/obter";

        $response = Http::asForm()->withHeaders(
        [
            'x-api-key' => env('KEY_API_EEL')
        ])->post($link,
        [
            'codloc' => $codloc
        ]);

        return $response[0];
    }

    public static function listarOferecimentoPos($codcur, $dtainiofe, $dtafimofe, $especial = 'N')
    {
        $link = env('URL_API_EEL')."posgraduacao/oferecimento/listar";

        $response = Http::asForm()->withHeaders(
        [
            'x-api-key' => env('KEY_API_EEL')
        ])->post($link,
        [
            'codcur'    => $codcur,
            'dtainiofe' => $dtainiofe,
            'dtafimofe' => $dtafimofe,
            'especial'  => $especial
        ]);

        return $response->json();
    }

    public static function obterOferecimentoPos($sgldis, $dtainiofe, $dtafimofe)
    {
        $link = env('URL_API_EEL')."posgraduacao/oferecimento/obter";

        $response = Http::asForm()->withHeaders(
        [
            'x-api-key' => env('KEY_API_EEL')
        ])->post($link,
        [
            'sgldis'    => $sgldis,
            'dtainiofe' => $dtainiofe,
            'dtafimofe' => $dtafimofe,
        ]);

        return $response[0];
    }

    public static function listarOferecimentoPosDocente($codcur, $codpes, $dtainiofe, $dtafimofe, $especial = 'N')
    {
        $link = env('URL_API_EEL')."posgraduacao/oferecimento/docente";

        $response = Http::asForm()->withHeaders(
        [
            'x-api-key' => env('KEY_API_EEL')
        ])->post($link,
        [
            'codcur'    => $codcur,
            'codpes'    => $codpes,
            'dtainiofe' => $dtainiofe,
            'dtafimofe' => $dtafimofe,
            'especial'  => $especial
        ]);

        return $response->json();
    }

    public static function setSession($id)
    {
        $level = User::obterLevel($id);        
        session(['level' => $level]);

        $vinculos = User::obterVinculos($id);
        session(['vinculos' => $vinculos]);
    }

    public static function obterTotalInscricao($codigoInscricao)
    {        
        $total = array();
        $expectativas = Inscricao::obterExpectativaInscricao($codigoInscricao);
        $requerimento = Arquivo::verificarArquivo($codigoInscricao, [28]);
        /*$curriculo    = Inscricao::obterCurriculoInscricao($codigoInscricao);
        $pre_projeto  = Arquivo::verificarArquivo($codigoInscricao, [10]);
        $requerimento = Arquivo::verificarArquivo($codigoInscricao, [28]);
        $foto         = Arquivo::verificarArquivo($codigoInscricao, [27]); */

        $total['pessoal']      = InscricoesPessoais::obterTotal($codigoInscricao);
        $total['documento']    = InscricoesDocumentos::obterTotal($codigoInscricao);
        $total['endereco']     = InscricoesEnderecos::obterTotal($codigoInscricao);
        $total['arquivo']      = InscricoesArquivos::obterTotal($codigoInscricao);
        $total['emergencia']   = InscricoesEnderecos::obterTotalEmergencia($codigoInscricao);
        $total['escolar']      = InscricoesResumoEscolar::obterTotal($codigoInscricao);
        $total['idioma']       = InscricoesIdiomas::obterTotal($codigoInscricao);
        $total['profissional'] = InscricoesExperiencias::obterTotal($codigoInscricao, 2);
        $total['ensino']       = InscricoesExperiencias::obterTotal($codigoInscricao, 1);
        $total['financeiro']   = InscricoesRecursosFinanceiros::obterTotal($codigoInscricao);
        $total['disciplina']   = InscricoesDisciplinas::obterTotal($codigoInscricao);
        $total['expectativas'] = (empty($expectativas->expectativasInscricao) ? 0 : 1);
        $total['requerimento'] = $requerimento;
        /*$total['curriculo']    = (empty($curriculo->codigoArquivo) ? 0 : 1);
        $total['foto']         = (empty($foto) ? 0 : 1);
        $total['pre-projeto']  = $pre_projeto;
        $total['requerimento'] = $requerimento;*/
        $total['especial']     = $total['pessoal'] + $total['endereco'] + $total['emergencia'] + $total['escolar'] + $total['idioma'] + $total['profissional'] + $total['ensino'] + $total['expectativas'] + /*$total['curriculo'] +*/ $total['disciplina'];

        session(['total' => $total]);
    }   
    
    /*public static function obterTotalArquivos($codigoUsuario, $codigoEdital = '', $criterio = '')
    {
        $total = ViewInscricaoTotal::select($criterio)->where('codigoUsuario', $codigoUsuario)->where('codigoEdital', $codigoEdital)->first();
        return $total;
    } */

    public static function obterTotalArquivos($codigoInscricao, $codigoTipoDocumento)
    {
        $total      = Arquivo::verificarArquivo($codigoInscricao, $codigoTipoDocumento);
        //$documento = Arquivo::verificarArquivo($codigoInscricao, [1,2]);
        //$historico = Arquivo::verificarArquivo($codigoInscricao, [5]);
        //$curriculo = Arquivo::verificarArquivo($codigoInscricao, [8,9]);
        //$diploma   = Arquivo::verificarArquivo($codigoInscricao, [6]);

        //return $foto + $documento + $historico + $curriculo;

        return $total;
    }

    public static function obterTipoEspecial($tipoEspecial)
    {
        $i = 0;
        $tipos = '';

        foreach($tipoEspecial as $tipo)
        {
            if ($i < count($tipoEspecial) - 1)
            {
                $tipos .= "{$tipo}|";
            }
            else
            {
                $tipos .= "{$tipo}";
            }
            
            $i++;
        }

        return $tipos;
    }

    public static function listaSecaoCnae()
    {
        $link = "https://servicodados.ibge.gov.br/api/v2/cnae/secoes";

        $response = Http::get($link);

        $temp = $response->json(); 

        dd($temp);
 
        return $temp;
    }

    public static function listaDivisaoCnae($secao)
    {
        $link = "https://servicodados.ibge.gov.br/api/v2/cnae/secoes/{$secao}/divisoes";

        $response = Http::get($link);

        $temp = $response->json(); 

        dd($temp);
 
        return $temp;
    }

    public static function verificarMinistrante($codigoEdital, $codpes)
    {
        $disciplinas = InscricoesDisciplinas::listarDisciplinas($codigoEdital);

        foreach($disciplinas as $disciplina)
        {
            dd($disciplina);

            //Posgraduacao::ministrante();
        }
    }
}
