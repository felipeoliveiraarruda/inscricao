<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Inscricao extends Model
{
    use \Spatie\Permission\Traits\HasRoles;
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'codigoInscricao';
    protected $table      = 'inscricoes';

    protected $fillable = [
        'codigoEdital',
        'codigoUsuario',    
        'numeroInscricao',    
        'statusInscricao',
        'expectativasInscricao',
        'codigoPessoaAlteracao'
    ];

    public function user()
    {
        return $this->belongsTo(App\Models\User::class);
    }

    public function editais()
    {
        return $this->belongsTo(App\Models\Edital::class);
    }

    public function pae()
    {
        return $this->belongsTo(\App\Models\PAE\Pae::class);
    }
    
    public static function gerarNumeroInscricao($codigoEdital)
    {
        $total = Inscricao::where('codigoEdital', $codigoEdital)->count() + 1;
        return $total;
    }

    public static function verificarInscricao($codigoEdital, $user_id)
    {
        $total = Inscricao::where('codigoEdital', $codigoEdital)->where('codigoUsuario', $user_id)->count();
        return $total;
    }

    public static function obterStatusInscricao($codigoEdital, $user_id)
    {
        $inscricao = Inscricao::select('statusInscricao')->where('codigoEdital', $codigoEdital)->where('codigoUsuario', $user_id)->first();
    
        if (empty($inscricao))
        {
            return '';
        }
        else
        {
            return $inscricao->statusInscricao;
        }
    }

    public static function obterInscricao($user_id, $codigoEdital)
    {
        $inscricao = Inscricao::join('editais', 'editais.codigoEdital', '=', 'inscricoes.codigoEdital')
                              ->where('inscricoes.codigoUsuario', $user_id)
                              ->where('inscricoes.codigoEdital', $codigoEdital)
                              ->first();

        return $inscricao;                              
    }

    public static function obterEditalInscricao($codigoInscricao)
    {
        $inscricao = Inscricao::select('inscricoes.codigoEdital')                            
                              ->where('inscricoes.codigoInscricao', $codigoInscricao)
                              ->first();

        return $inscricao->codigoEdital;                              
    }

    public static function obterDadosPessoaisInscricao($codigoInscricao)
    {   
        $pessoal = DadosPessoais::select(\DB::raw('inscricoes.codigoEdital, inscricoes.statusInscricao, inscricoes.numeroInscricao, pessoais.*, users.*, documentos.*, inscricoes_pessoais.codigoInscricaoPessoal, inscricoes_documentos.codigoInscricaoDocumento'))
                                ->rightJoin('users', 'users.id', '=', 'pessoais.codigoUsuario')
                                ->rightJoin('inscricoes', 'users.id', '=', 'inscricoes.codigoUsuario')
                                ->leftJoin('documentos', 'users.id', '=', 'documentos.codigoUsuario')
                                ->leftJoin('inscricoes_pessoais', 'inscricoes_pessoais.codigoInscricao', '=', 'inscricoes.codigoInscricao')
                                ->leftJoin('inscricoes_documentos', 'inscricoes_documentos.codigoInscricao', '=', 'inscricoes.codigoInscricao')                                    
                                ->where('inscricoes.codigoInscricao', $codigoInscricao)
                                ->first();
        
        return $pessoal;                                 
    }

    public static function obterInscricaoPae($user_id, $codigoEdital)
    {
        $pae = User::select(\DB::raw('inscricoes.*, pae.*, users.* '))
                       ->join('inscricoes', 'users.id', '=', 'inscricoes.codigoUsuario')
                       ->leftJoin('pae', 'inscricoes.codigoInscricao', '=', 'pae.codigoInscricao')        
                       ->where('users.id', $user_id)
                       ->where('inscricoes.codigoEdital', $codigoEdital)
                       ->first();                              
        return $pae;
    }

    public static function obterEnderecoInscricao($codigoInscricao)
    {
        $endereco = Endereco::select(\DB::raw('inscricoes.codigoEdital, inscricoes.statusInscricao, enderecos.*, users.*, inscricoes_enderecos.codigoInscricaoEndereco'))
                            ->rightJoin('users', 'users.id', '=', 'enderecos.codigoUsuario')
                            ->rightJoin('inscricoes', 'users.id', '=', 'inscricoes.codigoUsuario')                         
                            ->leftJoin('inscricoes_enderecos', 'inscricoes_enderecos.codigoInscricao', '=', 'inscricoes.codigoInscricao')
                            ->where('inscricoes.codigoInscricao', $codigoInscricao)
                            ->first();                         
        return $endereco;                                 
    }

    public static function obterEmergenciaInscricao($codigoInscricao)
    {
        $emergencia = Emergencia::select(\DB::raw('inscricoes.codigoEdital, inscricoes.statusInscricao, enderecos.*, emergencias.*, users.*, inscricoes_enderecos.codigoInscricaoEndereco'))
                          ->rightJoin('users', 'users.id', '=', 'emergencias.codigoUsuario')                                
                          ->leftJoin('inscricoes_enderecos', 'inscricoes_enderecos.codigoEmergencia', '=', 'emergencias.codigoEmergencia')
                          ->leftjoin('enderecos', 'enderecos.codigoEndereco', '=', 'inscricoes_enderecos.codigoEndereco')   
                          ->rightJoin('inscricoes', 'users.id', '=', 'inscricoes.codigoUsuario')
                          ->where('inscricoes.codigoInscricao', $codigoInscricao)
                          ->first();                                               
        return $emergencia;                                 
    }
    
    public static function obterEscolarInscricao($codigoInscricao, $codigoResumoEscolar = '')
    {
        if(empty($codigoResumoEscolar))
        {
            $escolar = ResumoEscolar::select(\DB::raw('inscricoes.codigoEdital, inscricoes.statusInscricao, resumo_escolar.*, inscricoes_resumo_escolar.codigoInscricaoResumoEscolar'))
                           ->rightJoin('users', 'users.id', '=', 'resumo_escolar.codigoUsuario')                                
                           ->leftJoin('inscricoes_resumo_escolar', 'inscricoes_resumo_escolar.codigoResumoEscolar', '=', 'resumo_escolar.codigoResumoEscolar')                             
                           ->rightJoin('inscricoes', 'users.id', '=', 'inscricoes.codigoUsuario')
                           ->where('inscricoes.codigoInscricao', $codigoInscricao)
                           ->get();
        }
        else
        {
            $escolar = ResumoEscolar::select(\DB::raw('inscricoes.codigoEdital, inscricoes.statusInscricao, resumo_escolar.*, inscricoes_resumo_escolar.codigoInscricaoResumoEscolar'))
                                    ->rightJoin('users', 'users.id', '=', 'resumo_escolar.codigoUsuario')                                
                                    ->leftJoin('inscricoes_resumo_escolar', 'inscricoes_resumo_escolar.codigoResumoEscolar', '=', 'resumo_escolar.codigoResumoEscolar')                             
                                    ->rightJoin('inscricoes', 'users.id', '=', 'inscricoes.codigoUsuario')
                                    ->where('inscricoes.codigoInscricao', $codigoInscricao)
                                    ->where('resumo_escolar.codigoResumoEscolar', $codigoResumoEscolar)
                                    ->get();
        }

        return $escolar;                                 
    }

    public static function obterIdiomaInscricao($codigoInscricao)
    {
        $idioma = Idioma::select(\DB::raw('inscricoes.codigoEdital, inscricoes.statusInscricao, idiomas.*, users.*, inscricoes_idiomas.codigoInscricaoIdioma'))
                        ->rightJoin('users', 'users.id', '=', 'idiomas.codigoUsuario')                                
                        ->leftJoin('inscricoes_idiomas', 'inscricoes_idiomas.codigoIdioma', '=', 'idiomas.codigoIdioma')
                        ->rightJoin('inscricoes', 'users.id', '=', 'inscricoes.codigoUsuario')
                        ->where('inscricoes.codigoInscricao', $codigoInscricao)
                        ->get();                                               
        return $idioma;                                 
    }
    
    public static function obterProfissionalInscricao($codigoInscricao)
    {
        $profissional = Experiencia::select(\DB::raw('inscricoes.codigoEdital, inscricoes.statusInscricao, experiencias.*, users.*, inscricoes_experiencias.codigoInscricaoExperiencia'))                        
                                   ->rightJoin('users', function($join)
                                   {
                                       $join->on('users.id', '=', 'experiencias.codigoUsuario');
                                       $join->on('experiencias.codigoTipoExperiencia', '=', \DB::raw(2));
                                   })                          
                                   ->leftJoin('inscricoes_experiencias', 'inscricoes_experiencias.codigoExperiencia', '=', 'experiencias.codigoExperiencia')
                                   ->rightJoin('inscricoes', 'users.id', '=', 'inscricoes.codigoUsuario')    
                                   ->where('inscricoes.codigoInscricao', $codigoInscricao)
                                   ->get();

        return $profissional;                                 
    }
    
    public static function obterEnsinoInscricao($codigoInscricao)
    {
        $ensino = Experiencia::select(\DB::raw('inscricoes.codigoEdital, inscricoes.statusInscricao, experiencias.*, users.*, inscricoes_experiencias.codigoInscricaoExperiencia, tipo_entidade.*'))
                            ->rightJoin('users', function($join)
                            {
                                $join->on('users.id', '=', 'experiencias.codigoUsuario');
                                $join->on('experiencias.codigoTipoExperiencia', '=', \DB::raw(1));
                            })                          
                            ->leftJoin('inscricoes_experiencias', 'inscricoes_experiencias.codigoExperiencia', '=', 'experiencias.codigoExperiencia')
                            ->rightJoin('inscricoes', 'users.id', '=', 'inscricoes.codigoUsuario')  
                            ->rightJoin('tipo_entidade', 'tipo_entidade.codigoTipoEntidade', '=', 'experiencias.codigoTipoEntidade')  
                            ->where('inscricoes.codigoInscricao', $codigoInscricao)
                            ->get();                                             
        return $ensino;                                 
    }   
    
    public static function obterFinanceiroInscricao($codigoInscricao)
    {
        $financeiro = RecursoFinanceiro::select(\DB::raw('inscricoes.codigoEdital, inscricoes.statusInscricao, recursos_financeiros.*, users.*, inscricoes_recursos_financeiros.codigoInscricaoRecursoFinanceiro'))
                                       ->rightJoin('users', 'users.id', '=', 'recursos_financeiros.codigoUsuario')                                
                                       ->leftJoin('inscricoes_recursos_financeiros', 'inscricoes_recursos_financeiros.codigoRecursoFinanceiro', '=', 'recursos_financeiros.codigoRecursoFinanceiro')
                                       ->rightJoin('inscricoes', 'users.id', '=', 'inscricoes.codigoUsuario')
                                       ->where('inscricoes.codigoInscricao', $codigoInscricao)
                                       ->first();                                              
        return $financeiro;                                 
    }    

    public static function obterExpectativaInscricao($codigoInscricao)
    {
        $expectativas = Inscricao::select('inscricoes.codigoEdital', 'inscricoes.statusInscricao', 'inscricoes.expectativasInscricao')
                                 ->join('users', 'users.id', '=', 'inscricoes.codigoUsuario')
                                 ->where('inscricoes.codigoInscricao', $codigoInscricao)
                                 ->first(); 

        return $expectativas;                                 
    } 
    
    public static function obterCurriculoInscricao($codigoInscricao)
    {
        $curriculo = Arquivo::select(\DB::raw('inscricoes.codigoEdital, inscricoes.statusInscricao, inscricoes.expectativasInscricao, arquivos.*'))
                            ->rightJoin('users', 'users.id', '=', 'arquivos.codigoUsuario')                                
                            ->leftJoin('inscricoes_arquivos', 'inscricoes_arquivos.codigoArquivo', '=', 'arquivos.codigoArquivo')
                            ->rightJoin('inscricoes', 'users.id', '=', 'inscricoes.codigoUsuario')
                            ->where('inscricoes.codigoInscricao', $codigoInscricao)
                            ->whereIn('arquivos.codigoTipoDocumento', [8,9])
                            ->first(); 
        return $curriculo;                                 
    }

    public static function obterProjetoInscricao($codigoInscricao)
    {
        $projeto = Arquivo::select(\DB::raw('inscricoes.codigoEdital, inscricoes.statusInscricao, inscricoes.expectativasInscricao, arquivos.*'))
                          ->rightJoin('users', 'users.id', '=', 'arquivos.codigoUsuario')                                
                          ->leftJoin('inscricoes_arquivos', 'inscricoes_arquivos.codigoArquivo', '=', 'arquivos.codigoArquivo')
                          ->rightJoin('inscricoes', 'users.id', '=', 'inscricoes.codigoUsuario')
                          ->where('inscricoes.codigoInscricao', $codigoInscricao)
                          ->whereIn('arquivos.codigoTipoDocumento', [10])
                          ->first(); 
        return $projeto;                                 
    } 
    
    public static function obterFotoInscricao($codigoInscricao)
    {        
        $foto = Arquivo::select(\DB::raw('inscricoes.codigoEdital, inscricoes.statusInscricao, inscricoes.expectativasInscricao, arquivos.*'))
                                   ->rightJoin('users', 'users.id', '=', 'arquivos.codigoUsuario')                                
                                   ->leftJoin('inscricoes_arquivos', 'inscricoes_arquivos.codigoArquivo', '=', 'arquivos.codigoArquivo')
                                   ->rightJoin('inscricoes', 'users.id', '=', 'inscricoes.codigoUsuario')
                                   ->where('inscricoes.codigoInscricao', $codigoInscricao)
                                   ->whereIn('arquivos.codigoTipoDocumento', [27])
                                   ->first(); 
        return $foto;                                   
    }

    public static function obterRequerimentoInscricao($codigoInscricao)
    {
        $requerimento = Arquivo::select(\DB::raw('inscricoes.codigoEdital, inscricoes.statusInscricao, inscricoes.expectativasInscricao, arquivos.*'))
                               ->rightJoin('users', 'users.id', '=', 'arquivos.codigoUsuario')                                
                               ->leftJoin('inscricoes_arquivos', 'inscricoes_arquivos.codigoArquivo', '=', 'arquivos.codigoArquivo')
                               ->rightJoin('inscricoes', 'users.id', '=', 'inscricoes.codigoUsuario')
                               ->where('inscricoes.codigoInscricao', $codigoInscricao)
                               ->whereIn('arquivos.codigoTipoDocumento', [28])
                               ->first(); 
        return $requerimento;                                 
    }
}