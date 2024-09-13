<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Uspdev\Replicado\Pessoa;
use Uspdev\Replicado\Posgraduacao;
use Illuminate\Support\Str;
use App\Models\Utils;

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
        $total = Inscricao::where('codigoEdital', $codigoEdital)->where('codigoUsuario', $user_id)->first();

        if (empty($total))
        {
            return 0;
        }
        else
        {
            return $total->codigoInscricao;
        }
    }

    public static function obterStatusInscricao($codigoInscricao)
    {
        $inscricao = Inscricao::select('statusInscricao')->where('codigoInscricao', $codigoInscricao)->first();
    
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
                              ->join('users', 'users.id', '=', 'inscricoes.codigoUsuario')
                              ->leftJoin('pessoais', 'users.id', '=', 'pessoais.codigoUsuario')
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

    public static function obterUltimaInscricao($user_id, $codigoEdital)
    {
        $inscricao = Inscricao::select('inscricoes.codigoInscricao')                            
                              ->where('inscricoes.codigoUsuario', $user_id)                              
                              ->where('inscricoes.codigoEdital', '<>', $codigoEdital)
                              ->latest('inscricoes.created_at')
                              ->first();

        return $inscricao->codigoInscricao;
    }

    public static function obterNomeInscricao($codigoInscricao)
    {
        $inscricao = Inscricao::select('inscricoes.numeroInscricao', 'users.name')    
                              ->join('users', 'users.id', '=', 'inscricoes.codigoUsuario')                        
                              ->where('inscricoes.codigoInscricao', $codigoInscricao)
                              ->first();

        return $inscricao;                              
    }

    public static function obterDadosPessoaisInscricao($codigoInscricao)
    {   
        $pessoal = DadosPessoais::select(\DB::raw('inscricoes.codigoEdital, inscricoes.statusInscricao, inscricoes.numeroInscricao, inscricoes.alunoEspecial, pessoais.*, users.*, documentos.*, inscricoes_pessoais.codigoInscricaoPessoal, inscricoes_documentos.codigoInscricaoDocumento'))
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
        $emergencias = Emergencia::select(\DB::raw('inscricoes.codigoEdital, inscricoes.statusInscricao, emergencias.*, users.*, inscricoes_enderecos.codigoInscricaoEndereco, inscricoes_enderecos.codigoEndereco AS codigoEmergenciaEndereco, inscricoes_enderecos.codigoEmergencia AS codigoEmergenciaInscricao, inscricoes_enderecos.mesmoEndereco'))
                                ->rightJoin('users', 'users.id', '=', 'emergencias.codigoUsuario')                     
                                ->rightJoin('inscricoes', 'users.id', '=', 'inscricoes.codigoUsuario')
                                ->leftJoin('inscricoes_enderecos', 'inscricoes_enderecos.codigoInscricao', '=', 'inscricoes.codigoInscricao')                                
                                ->where('inscricoes.codigoInscricao', $codigoInscricao)
                                ->first();  
                                
        /*if (count($emergencias) > 1)
        {
            foreach($emergencias as $emergencia)
            {
                if (!empty($emergencia->codigoEmergencia))
                {
                    return $emergencia;
                }
            }
        }
        else
        {
            return $emergencias[0]; 
        } */  
        
        return $emergencias;
    }
    
    public static function obterEscolarInscricao($codigoInscricao, $codigoResumoEscolar = '')
    {
        if(empty($codigoResumoEscolar))
        {
            $escolar = ResumoEscolar::select(\DB::raw('inscricoes.codigoEdital, inscricoes.statusInscricao, resumo_escolar.*, inscricoes_resumo_escolar.codigoInscricaoResumoEscolar, inscricoes_resumo_escolar.codigoHistorico, inscricoes_resumo_escolar.codigoDiploma'))
                                    ->rightJoin('users', 'users.id', '=', 'resumo_escolar.codigoUsuario')                                                                    
                                    ->rightJoin('inscricoes', 'users.id', '=', 'inscricoes.codigoUsuario')
                                    ->leftJoin('inscricoes_resumo_escolar', function($join)
                                    {
                                        $join->on('inscricoes_resumo_escolar.codigoInscricao', '=', 'inscricoes.codigoInscricao');
                                        $join->on('inscricoes_resumo_escolar.codigoResumoEscolar', '=', 'resumo_escolar.codigoResumoEscolar');
                                    })    
                                    ->where('inscricoes.codigoInscricao', $codigoInscricao)
                                    ->get();
        }
        else
        {
            $escolar = ResumoEscolar::select(\DB::raw('inscricoes.codigoEdital, inscricoes.statusInscricao, resumo_escolar.*, inscricoes_resumo_escolar.codigoInscricaoResumoEscolar'))
                                    ->rightJoin('users', 'users.id', '=', 'resumo_escolar.codigoUsuario')                                                                    
                                    ->rightJoin('inscricoes', 'users.id', '=', 'inscricoes.codigoUsuario')
                                    ->leftJoin('inscricoes_resumo_escolar', function($join)
                                    {
                                        $join->on('inscricoes_resumo_escolar.codigoInscricao', '=', 'inscricoes.codigoInscricao');
                                        $join->on('inscricoes_resumo_escolar.codigoResumoEscolar', '=', 'resumo_escolar.codigoResumoEscolar');
                                    })    
                                    ->where('inscricoes.codigoInscricao', $codigoInscricao)
                                    ->where('resumo_escolar.codigoResumoEscolar', $codigoResumoEscolar)
                                    ->first();
        }

        return $escolar;                                 
    }

    public function obterUltimaTitulacao($codigoInscricao)
    {
        $escolar = ResumoEscolar::select(\DB::raw('inscricoes.codigoEdital, inscricoes.statusInscricao, resumo_escolar.*, inscricoes_resumo_escolar.codigoInscricaoResumoEscolar, inscricoes_resumo_escolar.codigoHistorico, inscricoes_resumo_escolar.codigoDiploma'))
                                ->rightJoin('users', 'users.id', '=', 'resumo_escolar.codigoUsuario')                                                                    
                                ->rightJoin('inscricoes', 'users.id', '=', 'inscricoes.codigoUsuario')
                                ->leftJoin('inscricoes_resumo_escolar', function($join)
                                {
                                    $join->on('inscricoes_resumo_escolar.codigoInscricao', '=', 'inscricoes.codigoInscricao');
                                    $join->on('inscricoes_resumo_escolar.codigoResumoEscolar', '=', 'resumo_escolar.codigoResumoEscolar');
                                })    
                                ->where('inscricoes.codigoInscricao', $codigoInscricao)
                                ->latest('resumo_escolar.finalResumoEscolar')
                                ->first();
        return $escolar;
    }

    public static function obterIdiomaInscricao($codigoInscricao, $codigoIdioma = '')
    {
        if (empty($codigoIdioma))
        {
            $idioma = Idioma::select(\DB::raw('inscricoes.codigoEdital, inscricoes.statusInscricao, idiomas.*, users.*, inscricoes_idiomas.codigoInscricaoIdioma'))
                            ->rightJoin('users', 'users.id', '=', 'idiomas.codigoUsuario')                  
                            ->rightJoin('inscricoes', 'users.id', '=', 'inscricoes.codigoUsuario')   
                            ->leftJoin('inscricoes_idiomas', function($join)
                            {
                                $join->on('inscricoes_idiomas.codigoInscricao', '=', 'inscricoes.codigoInscricao');
                                $join->on('inscricoes_idiomas.codigoIdioma', '=', 'idiomas.codigoIdioma');
                            }) 
                            ->where('inscricoes.codigoInscricao', $codigoInscricao)
                            ->get();         
        }
        else
        {
            $idioma = Idioma::select(\DB::raw('inscricoes.codigoEdital, inscricoes.statusInscricao, idiomas.*, users.*, inscricoes_idiomas.codigoInscricaoIdioma'))
                            ->rightJoin('users', 'users.id', '=', 'idiomas.codigoUsuario')                  
                            ->rightJoin('inscricoes', 'users.id', '=', 'inscricoes.codigoUsuario')              
                            ->leftJoin('inscricoes_idiomas', function($join)
                            {
                                $join->on('inscricoes_idiomas.codigoInscricao', '=', 'inscricoes.codigoInscricao');
                                $join->on('inscricoes_idiomas.codigoIdioma', '=', 'idiomas.codigoIdioma');
                            })    
                            ->where('idiomas.codigoIdioma', $codigoIdioma)
                            ->where('inscricoes.codigoInscricao', $codigoInscricao)
                            ->first();  
        }
                                            
        return $idioma;                                 
    }
    
    public static function obterProfissionalInscricao($codigoInscricao, $codigoExperiencia = '')
    {
        if (empty($codigoExperiencia))
        {
            $profissional = Experiencia::select(\DB::raw('inscricoes.codigoEdital, inscricoes.statusInscricao, experiencias.*, users.*, inscricoes_experiencias.codigoInscricaoExperiencia'))                        
                                    ->rightJoin('users', function($join)
                                    {
                                        $join->on('users.id', '=', 'experiencias.codigoUsuario');
                                        $join->on('experiencias.codigoTipoExperiencia', '=', \DB::raw(2));
                                    })       
                                    ->rightJoin('inscricoes', 'users.id', '=', 'inscricoes.codigoUsuario')                      
                                    ->leftJoin('inscricoes_experiencias', function($join)
                                    {
                                        $join->on('inscricoes_experiencias.codigoInscricao', '=', 'inscricoes.codigoInscricao');
                                        $join->on('inscricoes_experiencias.codigoExperiencia', '=', 'experiencias.codigoExperiencia');
                                    })                                 
                                    ->where('inscricoes.codigoInscricao', $codigoInscricao)
                                    ->get();
        }
        else
        {
            $profissional = Experiencia::select(\DB::raw('inscricoes.codigoEdital, inscricoes.statusInscricao, experiencias.*, users.*, inscricoes_experiencias.codigoInscricaoExperiencia'))                        
                                        ->rightJoin('users', function($join)
                                        {
                                            $join->on('users.id', '=', 'experiencias.codigoUsuario');
                                            $join->on('experiencias.codigoTipoExperiencia', '=', \DB::raw(2));
                                        })       
                                        ->rightJoin('inscricoes', 'users.id', '=', 'inscricoes.codigoUsuario')                                                              
                                        ->leftJoin('inscricoes_experiencias', function($join)
                                        {
                                            $join->on('inscricoes_experiencias.codigoInscricao', '=', 'inscricoes.codigoInscricao');
                                            $join->on('inscricoes_experiencias.codigoExperiencia', '=', 'experiencias.codigoExperiencia');
                                        })    
                                        ->where('experiencias.codigoExperiencia', $codigoExperiencia)
                                        ->where('inscricoes.codigoInscricao', $codigoInscricao)
                                        ->first();
        }

        return $profissional;                                 
    }
    
    public static function obterEnsinoInscricao($codigoInscricao, $codigoExperiencia = '')
    {
        if (empty($codigoExperiencia))
        {
            $ensino = Experiencia::select(\DB::raw('inscricoes.codigoEdital, inscricoes.statusInscricao, experiencias.*, users.*, tipo_entidade.*, inscricoes_experiencias.codigoInscricaoExperiencia'))
                                ->join('tipo_entidade', 'tipo_entidade.codigoTipoEntidade', '=', 'experiencias.codigoTipoEntidade')
                                ->leftjoin('users', function($join)
                                {
                                    $join->on('users.id', '=', 'experiencias.codigoUsuario');
                                    $join->on('experiencias.codigoTipoExperiencia', '=', \DB::raw(1));
                                })       
                                ->leftjoin('inscricoes', 'users.id', '=', 'inscricoes.codigoUsuario')                      
                                ->leftJoin('inscricoes_experiencias', function($join)
                                {
                                    $join->on('inscricoes_experiencias.codigoInscricao', '=', 'inscricoes.codigoInscricao');
                                    $join->on('inscricoes_experiencias.codigoExperiencia', '=', 'experiencias.codigoExperiencia');
                                })                                 
                                ->where('inscricoes.codigoInscricao', $codigoInscricao)
                                ->get();    
        }
        else
        {
            $ensino = Experiencia::select(\DB::raw('inscricoes.codigoEdital, inscricoes.statusInscricao, experiencias.*, users.*, tipo_experiencia.*, inscricoes_experiencias.codigoInscricaoExperiencia'))                        
                                ->join('tipo_experiencia', 'tipo_experiencia.codigoTipoExperiencia', '=', 'experiencias.codigoTipoExperiencia')
                                ->leftjoin('users', function($join)
                                {
                                    $join->on('users.id', '=', 'experiencias.codigoUsuario');
                                    $join->on('experiencias.codigoTipoExperiencia', '=', \DB::raw(1));
                                })       
                                ->leftjoin('inscricoes', 'users.id', '=', 'inscricoes.codigoUsuario')                      
                                ->leftJoin('inscricoes_experiencias', function($join)
                                {
                                    $join->on('inscricoes_experiencias.codigoInscricao', '=', 'inscricoes.codigoInscricao');
                                    $join->on('inscricoes_experiencias.codigoExperiencia', '=', 'experiencias.codigoExperiencia');
                                })                                 
                                ->where('experiencias.codigoExperiencia', $codigoExperiencia)
                                ->where('inscricoes.codigoInscricao', $codigoInscricao)
                                ->first(); 
        }
        return $ensino;                                 
    }   
    
    public static function obterFinanceiroInscricao($codigoInscricao)
    {
        $financeiro = RecursoFinanceiro::select(\DB::raw('inscricoes.codigoEdital, inscricoes.statusInscricao, recursos_financeiros.*, users.*, inscricoes_recursos_financeiros.codigoInscricaoRecursoFinanceiro'))                                       
                                       ->rightJoin('users', 'users.id', '=', 'recursos_financeiros.codigoUsuario')                     
                                       ->rightJoin('inscricoes', 'users.id', '=', 'inscricoes.codigoUsuario')                                                                           
                                       ->leftJoin('inscricoes_recursos_financeiros', 'inscricoes_recursos_financeiros.codigoInscricao', '=', 'inscricoes.codigoInscricao')
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
        $curriculo = Arquivo::select(\DB::raw('inscricoes.codigoEdital, inscricoes.statusInscricao, inscricoes.expectativasInscricao, arquivos.*, tipo_documentos.*, inscricoes_arquivos.codigoInscricaoArquivo'))
                            ->join('tipo_documentos', 'tipo_documentos.codigoTipoDocumento', '=', 'arquivos.codigoTipoDocumento')
                            ->leftJoin('users', 'users.id', '=', 'arquivos.codigoUsuario')                                
                            ->leftJoin('inscricoes', 'users.id', '=', 'inscricoes.codigoUsuario')
                            ->leftJoin('inscricoes_arquivos', function($join)
                            {
                                $join->on('inscricoes_arquivos.codigoInscricao', '=', 'inscricoes.codigoInscricao');
                                $join->on('inscricoes_arquivos.codigoArquivo', '=', 'arquivos.codigoArquivo');
                            })
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

    public static function obterDisciplinaInscricao($codigoInscricao, $deferido = 'N')
    {
        if ($deferido == 'N')
        {
            $disciplinas = Inscricao::select('inscricoes.codigoEdital', 'inscricoes.statusInscricao', 'editais.*', 'users.*', 'inscricoes_disciplinas.codigoInscricaoDisciplina', 'inscricoes_disciplinas.codigoDisciplina', 'inscricoes_disciplinas.codigoPessoaAlteracao AS codigoPessoaDeferimento')
                                    ->join('users', 'users.id', '=', 'inscricoes.codigoUsuario')
                                    ->join('editais', 'editais.codigoEdital', '=', 'inscricoes.codigoEdital')
                                    ->join('inscricoes_disciplinas', 'inscricoes_disciplinas.codigoInscricao', '=', 'inscricoes.codigoInscricao')
                                    ->where('inscricoes.codigoInscricao', $codigoInscricao)
                                    ->whereNull('inscricoes_disciplinas.deleted_at')
                                    ->get(); 
        }
        else
        {
            $disciplinas = Inscricao::select('inscricoes.codigoEdital', 'inscricoes.statusInscricao', 'editais.*', 'users.*', 'inscricoes_disciplinas.codigoInscricaoDisciplina', 'inscricoes_disciplinas.codigoDisciplina', 'inscricoes_disciplinas.codigoPessoaAlteracao AS codigoPessoaDeferimento')
                                    ->join('users', 'users.id', '=', 'inscricoes.codigoUsuario')
                                    ->join('editais', 'editais.codigoEdital', '=', 'inscricoes.codigoEdital')
                                    ->join('inscricoes_disciplinas', 'inscricoes_disciplinas.codigoInscricao', '=', 'inscricoes.codigoInscricao')
                                    ->where('inscricoes.codigoInscricao', $codigoInscricao)
                                    ->where('inscricoes_disciplinas.statusDisciplina', 'D')
                                    ->whereNull('inscricoes_disciplinas.deleted_at')
                                    ->get(); 
        }

        return $disciplinas;                                 
    } 

    public static function obterAnexoInscricao($codigoInscricao, $codigoTipoDocumento)
    {
        $anexo = Arquivo::select(\DB::raw('inscricoes.codigoEdital, inscricoes.statusInscricao, inscricoes.expectativasInscricao, inscricoes_arquivos.codigoInscricaoArquivo, arquivos.*'))
                        ->leftJoin('users', 'users.id', '=', 'arquivos.codigoUsuario')
                        ->leftJoin('inscricoes', 'users.id', '=', 'inscricoes.codigoUsuario')                         
                        ->leftJoin('inscricoes_arquivos', function($join)
                        {
                            $join->on('inscricoes_arquivos.codigoInscricao', '=', 'inscricoes.codigoInscricao');
                            $join->on('inscricoes_arquivos.codigoArquivo', '=', 'arquivos.codigoArquivo');
                        })
                        ->where('inscricoes.codigoInscricao', $codigoInscricao)
                        ->whereIn('arquivos.codigoTipoDocumento', $codigoTipoDocumento)
                        ->whereNull('inscricoes_arquivos.deleted_at')
                        ->latest('inscricoes_arquivos.created_at')
                        ->first();

        return $anexo;   
    }

    public static function obterObrigatorioInscricao($codigoInscricao, $codigoTipoDocumento)
    {  
        $anexo = Arquivo::select(\DB::raw('inscricoes.codigoEdital, inscricoes.statusInscricao, inscricoes.expectativasInscricao, inscricoes_arquivos.codigoInscricaoArquivo, arquivos.*, tipo_documentos.tipoDocumento, editais_tipo_documentos.ordemTipoDocumento'))
                        ->join('users', 'users.id', '=', 'arquivos.codigoUsuario')                                
                        ->join('inscricoes_arquivos', 'inscricoes_arquivos.codigoArquivo', '=', 'arquivos.codigoArquivo')
                        ->join('inscricoes', 'inscricoes_arquivos.codigoInscricao', '=', 'inscricoes.codigoInscricao')
                        ->join('tipo_documentos', 'arquivos.codigoTipoDocumento', '=', 'tipo_documentos.codigoTipoDocumento')
                        ->join('editais_tipo_documentos', function($join)
                        {
                            $join->on('editais_tipo_documentos.codigoEdital', '=', 'inscricoes.codigoEdital');
                            $join->on('editais_tipo_documentos.codigoTipoDocumento', '=', 'tipo_documentos.codigoTipoDocumento');
                        })
                        ->where('inscricoes.codigoInscricao', $codigoInscricao)
                        ->whereIn('arquivos.codigoTipoDocumento', $codigoTipoDocumento)
                        ->whereNull('inscricoes_arquivos.deleted_at')
                        ->orderBy('editais_tipo_documentos.ordemTipoDocumento', 'asc')
                        //->groupBy('inscricoes_arquivos.codigoInscricaoArquivo')
                        ->get();  

        return $anexo;                                   
    }
    
    public static function obterFotoInscricao($codigoInscricao)
    {        
        $foto = Arquivo::select(\DB::raw('inscricoes.codigoEdital, inscricoes.statusInscricao, inscricoes.expectativasInscricao, inscricoes_arquivos.codigoInscricaoArquivo, arquivos.*'))
                                   ->leftJoin('users', 'users.id', '=', 'arquivos.codigoUsuario')                                
                                   ->leftJoin('inscricoes_arquivos', 'inscricoes_arquivos.codigoArquivo', '=', 'arquivos.codigoArquivo')
                                   ->leftJoin('inscricoes', 'users.id', '=', 'inscricoes.codigoUsuario')
                                   ->where('inscricoes.codigoInscricao', $codigoInscricao)
                                   ->whereIn('arquivos.codigoTipoDocumento', [27])
                                   ->whereNull('inscricoes_arquivos.deleted_at')
                                   ->first();
        return $foto;                                   
    }

    /*public static function obterCpfInscricao($codigoInscricao)
    {        
        $foto = Arquivo::select(\DB::raw('inscricoes.codigoEdital, inscricoes.statusInscricao, inscricoes.expectativasInscricao, inscricoes_arquivos.codigoInscricaoArquivo, arquivos.*'))
                                   ->leftJoin('users', 'users.id', '=', 'arquivos.codigoUsuario')                                
                                   ->leftJoin('inscricoes_arquivos', 'inscricoes_arquivos.codigoArquivo', '=', 'arquivos.codigoArquivo')
                                   ->leftJoin('inscricoes', 'users.id', '=', 'inscricoes.codigoUsuario')
                                   ->where('inscricoes.codigoInscricao', $codigoInscricao)
                                   ->whereIn('arquivos.codigoTipoDocumento', [])
                                   ->whereNull('inscricoes_arquivos.deleted_at')
                                   ->first();
        return $foto;                                   
    }*/
    

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

    public static function gerarMatricula($pdf, $tipo, $codigoInscricao)
    {
        $dados    = Inscricao::obterDadosPessoaisInscricao($codigoInscricao);
        $endereco = Inscricao::obterEnderecoInscricao($codigoInscricao);

        $edital       = Edital::join('niveis', 'editais.codigoNivel', '=', 'niveis.codigoNivel')->where('editais.codigoEdital', $dados->codigoEdital)->first();
        $anosemestre  = Edital::obterSemestreAno($dados->codigoEdital, true);
        $sigla        = Utils::obterSiglaCurso($edital->codigoCurso);
        $nivel        = Edital::obterNivelEdital($dados->codigoEdital);

        $pdf->setCabecalho($tipo);

        $pdf->SetStyle('p', 'arial', 'N', 14, '0,0,0');
        $pdf->SetStyle('b', 'arial', 'B', 0, '0,0,0');
        $pdf->SetStyle('b2', 'arial', 'B', 0, '0,0,0');
        $pdf->SetStyle('bu', 'arial', 'BU', 0, '0,0,0');
        $pdf->SetStyle('i', 'arial', 'I', 0, '0,0,0');
        
        $pdf->SetDisplayMode('real');
        $pdf->AliasNbPages();   
        $pdf->AddPage();

        if ($tipo == 'ppgem')
        {
            if ($nivel == 'ME')
            {
                if ($edital->dataDoeEdital->format('m') < 7)
                {
                    $diretorio = $edital->dataDoeEdital->format('Y').'2/mestrado';
                }
                else
                {
                    $ano       = $edital->dataDoeEdital->format('Y') + 1;
                    $diretorio = $ano.'1/mestrado'; 
                }

                $texto = "<p>Eu, {$dados->name}, RG {$dados->numeroRG}, e-mail {$dados->email}, residente à {$endereco->logradouroEndereco}, {$endereco->numeroEndereco} {$endereco->complementoEndereco} {$endereco->bairroEndereco}, na cidade de {$endereco->localidadeEndereco}/{$endereco->ufEndereco}, CEP {$endereco->cepEndereco}, telefone {$dados->telefone}, venho requerer à <b><i>Comissão de Pós-Graduação</i></b>, matrícula como aluno(a) <b>REGULAR</b>, no Mestrado do <b>Programa de Pós-Graduação em Engenharia de Materiais</b> na área de concentração: <b>97134 - Materiais Convencionais e Avançados</b>, nas <b>Disciplinas</b> abaixo listadas:</p>";

                $pdf->SetFont('Arial','B', 16);
                $pdf->SetFillColor(190,190,190);
                $pdf->MultiCell(190, 8, utf8_decode('PÓS-GRADUAÇÃO EM ENGENHARIA DE MATERIAIS - PPGEM REQUERIMENTO DE PRIMEIRA MATRÍCULA REGULAR'), 1, 'C', true);
                $pdf->Ln();                
            }
            
            if($nivel == 'AE')
            {
                if ($edital->dataFinalEdital->format('m') < 7)
                {
                    $diretorio = $edital->dataInicioEdital->format('Y').'1/especial';
                    $semestre  = '1º Semestre de '. $edital->dataInicioEdital->format('Y');
                }
                else
                {
                    $ano       = $edital->dataInicioEdital->format('Y');
                    $diretorio = $ano.'2/especial'; 
                    $semestre  = '2º Semestre de '.$ano;
                }
                
                $texto = "<p>Eu, {$dados->name}, RG {$dados->numeroRG}, e-mail {$dados->email}, residente à {$endereco->logradouroEndereco}, {$endereco->numeroEndereco} {$endereco->complementoEndereco} {$endereco->bairroEndereco}, na cidade de {$endereco->localidadeEndereco}/{$endereco->ufEndereco}, CEP {$endereco->cepEndereco}, telefone {$dados->telefone}, venho requerer à <b><i>Comissão de Pós-Graduação</i></b>, matrícula como aluno(a) <b>ESPECIAL</b>, no <b>{$semestre}</b> do Programa de Pós-Graduação em Engenharia de Materiais</p>";

                $pdf->SetFont('Arial','B', 16);
                $pdf->SetFillColor(190,190,190);
                $pdf->MultiCell(190, 8, utf8_decode('REQUERIMENTO DE MATRÍCULA ALUNO ESPECIAL'), 1, 'C', true);
                $pdf->Ln();   
            }
        }

        if ($tipo == 'ppgpe')
        {
            if (empty($dados->numeroRG))
            {
                $documento = $dados->numeroDocumento;
            }
            else
            {
                $documento = $dados->numeroRG;
            }

            if ($nivel == 'ME')
            {
                if ($edital->dataInicioEdital->format('m') < 7)
                {
                    $diretorio = $edital->dataDoeEdital->format('Y').'2';
                }
                else
                {
                    $ano       = $edital->dataDoeEdital->format('Y') + 1;
                    $diretorio = $ano.'1'; 
                }

                $texto = "<p>Eu, {$dados->name}, RG {$dados->numeroRG}, e-mail {$dados->email}, residente à {$endereco->logradouroEndereco}, {$endereco->numeroEndereco} {$endereco->complementoEndereco} {$endereco->bairroEndereco}, na cidade de {$endereco->localidadeEndereco}/{$endereco->ufEndereco}, CEP {$endereco->cepEndereco}, telefone {$dados->telefone}, venho requerer à <b><i>Comissão de Pós-Graduação</i></b>, matrícula como aluno(a) <b>REGULAR</b>, no Mestrado do <b>Programa de Pós-Graduação em Engenharia de Materiais</b> na área de concentração: <b>97134 - Materiais Convencionais e Avançados</b>, nas <b>Disciplinas</b> abaixo listadas:</p>";

                $pdf->SetFont('Arial','B', 16);
                $pdf->SetFillColor(190,190,190);
                $pdf->MultiCell(190, 8, utf8_decode('PÓS-GRADUAÇÃO EM ENGENHARIA DE MATERIAIS - PPGPE REQUERIMENTO DE PRIMEIRA MATRÍCULA REGULAR'), 1, 'C', true);
                $pdf->Ln();
            }

            if($nivel == 'AE')
            {
                if ($edital->dataInicioEdital->format('m') < 6)
                {
                    $diretorio = $edital->dataInicioEdital->format('Y').'1/especial';
                    $semestre  = '1º Semestre de '. $edital->dataInicioEdital->format('Y');
                }
                else
                {
                    $ano       = $edital->dataInicioEdital->format('Y');
                    $diretorio = $ano.'2/especial'; 
                    $semestre  = '2º Semestre de '.$ano;
                }

                $texto = "<p>Eu, {$dados->name}, RG {$documento}, e-mail {$dados->email}, residente à {$endereco->logradouroEndereco}, {$endereco->numeroEndereco} {$endereco->complementoEndereco} {$endereco->bairroEndereco}, na cidade de {$endereco->localidadeEndereco}/{$endereco->ufEndereco}, CEP {$endereco->cepEndereco}, telefone {$dados->telefone}, venho requerer à <b><i>Comissão de Pós-Graduação</i></b>, matrícula como aluno(a) <b>ESPECIAL</b>, no <b>{$semestre}</b> do Programa de Pós-Graduação em Projetos Educacionais de Ciências</p>";

                $pdf->SetFont('Arial','B', 16);
                $pdf->SetFillColor(190,190,190);
                $pdf->MultiCell(190, 8, utf8_decode('REQUERIMENTO DE MATRÍCULA ALUNO ESPECIAL'), 1, 'C', true);
                $pdf->Ln();   
            }   
        }

        $pdf->SetFont('Arial', '', 14);
        $pdf->SetFillColor(255,255,255);
        $pdf->WriteTag(190,8, utf8_decode($texto), 0, 'J');
        $pdf->Ln(5);

        if($nivel == 'ME')
        { 
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->SetFillColor(190,190,190);
            $pdf->Cell(40,  8, utf8_decode('CÓDIGO'), 1, 0, 'C', true);
            $pdf->Cell(110, 8, utf8_decode('DISCIPLINA'), 1, 0, 'C', true);
            $pdf->Cell(40,  8, utf8_decode('Nº DE CRÉDITOS'), 1, 0, 'C', true);
            $pdf->Ln();           
            
            $pdf->SetFont('Arial', '', 10);

            $disciplinas = Inscricao::obterDisciplinaInscricao($codigoInscricao);

            foreach($disciplinas as $disciplina)
            {
                $temp1 = explode('-', $disciplina->codigoDisciplina);

                $temp = Posgraduacao::disciplina($temp1[0]);

                //$pdf->CellFitScale(70, 8, utf8_decode($escolar->escolaResumoEscolar), 1, 0, 'J', false);
                
                $pdf->Cell(40,  8, utf8_decode("{$disciplina->codigoDisciplina}"), 1, 0, 'C', false);
                $pdf->CellFitScale(110, 8, utf8_decode(" {$temp['nomdis']}"), 1, 0, 'L', false);
                $pdf->Cell(40,  8, utf8_decode(" {$temp['numcretotdis']}"), 1, 0, 'C', false);
                $pdf->Ln();  
            }

            $pdf->Ln(5);
            $pdf->Cell(75,  8, utf8_decode('Lorena, _____/_____/_______'), 0, 0, 'L', false);
            $pdf->Cell(35, 8, utf8_decode(''), 0, 0, 'C', false);
            $pdf->Cell(75,  8, utf8_decode(''), 0, 0, 'C', false);
            $pdf->Cell(5,  8, utf8_decode(''), 0, 0, 'C', false);
            $pdf->Ln();
    
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(75,  5, utf8_decode(''), 0, 0, 'L', false);
            $pdf->Cell(35, 5, utf8_decode(''), 0, 0, 'C', false);
            $pdf->Cell(75,  5, utf8_decode('Assinatura do Aluno'), 'T', 0, 'C', false);
            $pdf->Cell(5,  5, utf8_decode(''), 0, 0, 'C', false);
            $pdf->Ln(10);
    
            /*$pdf->SetFont('Arial', '', 12);
            $pdf->Cell(75,  8, utf8_decode(''), 0, 0, 'C', false);
            $pdf->Cell(35, 8, utf8_decode(''), 0, 0, 'C', false);*/
            
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(75,  5, utf8_decode('Orientação Acadêmica'), 0, 0, 'C', false);
            $pdf->Cell(35, 5, utf8_decode(''), 0, 0, 'C', false);
            $pdf->Cell(75,  5, utf8_decode('"OAc"'), 0, 0, 'C', false);
            $pdf->Cell(5,  5, utf8_decode(''), 0, 0, 'C', false);
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
            $pdf->Ln(5);
    
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(60,  8, utf8_decode(''), 0, 0, 'L', false);
            
            $pdf->SetFont('Arial', '', 14);
            $pdf->Cell(80, 8, utf8_decode('Prof. Dr. Clodoaldo Saron'), 0, 0, 'C', false);
            $pdf->Cell(60,  8, utf8_decode(''), 0, 0, 'C', false);
            $pdf->Ln();
    
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(60,  5, utf8_decode(''), 0, 0, 'L', false);
            $pdf->Cell(80, 5, utf8_decode('Coordenador do Programa'), 'T', 0, 'C', false);
            $pdf->Cell(60,  5, utf8_decode(''), 0, 0, 'C', false);
            $pdf->Ln();

            $sigla   = Str::lower($sigla);
            $arquivo = storage_path("app/public/{$sigla}/{$diretorio}/matricula/{$dados->name}.pdf");
            $nome    = "{$sigla}/{$diretorio}/matricula/{$dados->name}.pdf";
    
            if (!file_exists($arquivo))
            {
                $pdf->Output('F', $arquivo);
            }
    
            return $arquivo;
        }

        if($nivel == 'AE')
        { 
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->SetFillColor(190,190,190);
            $pdf->Cell(25,  8, utf8_decode('CÓDIGO'), 1, 0, 'C', true);
            $pdf->Cell(90, 8, utf8_decode('DISCIPLINA'), 1, 0, 'C', true);
            $pdf->Cell(75,  8, utf8_decode('RESPONSÁVEL'), 1, 0, 'C', true);
            $pdf->Ln();         
            
            $pdf->SetFont('Arial', '', 10);

            $disciplinas = Inscricao::obterDisciplinaInscricao($codigoInscricao, 'S');

            foreach($disciplinas as $disciplina)
            {   
                $temp = Utils::obterOferecimentoPos($disciplina->codigoDisciplina, '05/08/2024', '30/11/2024');                
                $docente = User::where('codpes', $disciplina->codigoPessoaDeferimento)->first();
                
                $pdf->Cell(25,  8, utf8_decode("{$temp['sgldis']}-{$temp['numseqdis']}/{$temp['numofe']}"), 1, 0, 'C', false);
                $pdf->Cell(90, 8, utf8_decode($temp['nomdis']), 1, 0, 'L', false);
                $pdf->Cell(75,  8, utf8_decode($docente->name), 1, 0, 'C', false);
                $pdf->Ln();  
            }

            if ($dados->alunoEspecial == 'S')
            {
                $sim = '(  X  ) SIM';
                $nao = '(     ) NÃO';
            }
            else
            {
                $nao = '(  X  ) NÃO';
                $sim = '(     ) SIM';
            }

            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(130, 8, utf8_decode('JÁ CURSOU DISCIPLINA COMO ALUNO ESPECIAL NA EEL '), 1, 0, 'L', false);
            $pdf->Cell(30, 8,  utf8_decode($sim), 1, 0, 'C', false);
            $pdf->Cell(30, 8,  utf8_decode($nao), 1, 0, 'C', false);
            $pdf->Ln(); 

            $pdf->SetFont('Arial', 'I', 10);
            $pdf->Cell(190, 8, utf8_decode('Ex-alunos especiais, estão dispensados de apresentar documentação pessoal no ato da matrícula'), 0, 0, 'C', false);
            $pdf->Ln(); 
            $pdf->Ln();        
            $pdf->Ln();

            $pdf->Ln(5);
            $pdf->Cell(75,  8, utf8_decode('Lorena, _____/_____/_______'), 0, 0, 'L', false);
            $pdf->Cell(35, 8, utf8_decode(''), 0, 0, 'C', false);
            $pdf->Cell(75,  8, utf8_decode(''), 'B', 0, 'C', false);
            $pdf->Cell(5,  8, utf8_decode(''), 0, 0, 'C', false);
            $pdf->Ln();

            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(75, 8, '', 0, 0, 'L', false);
            $pdf->Cell(35, 8, '', 0, 0, 'L', false);
            $pdf->Cell(75, 8, 'Assinatura do Aluno', 0, 0, 'C', false);
            $pdf->Cell(5, 8, '', 0, 0, 'L', false); 

            $pdf->Ln(); 
            $pdf->Ln();
            $pdf->Ln();  

            $y = $pdf->GetY() - 15;
            
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(56, 8, '', 0, 0, 'L', false);
            $pdf->Cell(75, 8, '', 'B', 0, 'C', false);            
            $pdf->Cell(54, 8, '', 0, 0, 'L', false);
            $pdf->Ln();  

            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(56, 8, '', 0, 0, 'L', false);
            $pdf->Cell(75, 8, 'Coordenador do Programa', 0, 0, 'C', false);
            $pdf->Cell(54, 8, '', 0, 0, 'L', false);      
            $pdf->Ln(); 
            $pdf->Ln();
    
            $sigla   = Str::lower($sigla);
            $arquivo = storage_path("app/public/{$sigla}/{$diretorio}/matricula/{$dados->name}.pdf");
            $nome    = "{$sigla}/{$diretorio}/matricula/{$dados->name}.pdf";
    
            if (!file_exists($arquivo))
            {
                $pdf->Output('F', $arquivo);
            }
        }
    }

    public static function gerarComprovante(\App\Models\Comprovante $pdf, $tipo, $codigoInscricao)
    {
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

        if ($nivel == 'ME' || $nivel == 'DD' || $nivel == 'AE')
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
            $pdf->SetFont('Arial', '', 10);

            $escolares = Inscricao::obterEscolarInscricao($codigoInscricao);

            foreach($escolares as $escolar)
            {
                $pdf->Ln();
                $pdf->CellFitScale(70, 8, utf8_decode($escolar->escolaResumoEscolar), 1, 0, 'J', false);
                $pdf->CellFitScale(85, 8, utf8_decode($escolar->especialidadeResumoEscolar), 1, 0, 'J', false);
                
                if ($escolar->finalResumoEscolar == '')
                {
                    $pdf->Cell(35, 8, 'Em andamento', 1, 0, 'C', false);        
                }
                else
                {
                    $pdf->CellFitScale(35, 8, $escolar->finalResumoEscolar->format('Y'), 1, 0, 'C', false);            
                }
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
        
        return $arquivo;
    }
}