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
        //return 

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

    public static function obterDadosPessoaisInscricao($user_id, $codigoInscricao)
    {
        $pessoal = User::select(\DB::raw('inscricoes.codigoEdital, inscricoes.statusInscricao, pessoais.*, users.*, documentos.*, inscricoes_pessoais.codigoInscricaoPessoal, inscricoes_documentos.codigoInscricaoDocumento'))
                                ->join('inscricoes', 'users.id', '=', 'inscricoes.codigoUsuario')
                                ->leftjoin('pessoais', 'users.id', '=', 'pessoais.codigoUsuario')
                                ->leftJoin('documentos', 'users.id', '=', 'documentos.codigoUsuario')
                                ->leftJoin('inscricoes_pessoais', 'inscricoes_pessoais.codigoPessoal', '=', 'pessoais.codigoPessoal')
                                ->leftJoin('inscricoes_documentos', 'inscricoes_documentos.codigoDocumento', '=', 'documentos.codigoDocumento')
                                ->where('users.id', $user_id)
                                ->where('inscricoes.codigoInscricao', $codigoInscricao)
                                ->first();                              
        return $pessoal;                                 
    }

    public static function obterInscricaoPae($user_id, $codigoEdital)
    {
        $pae = User::select(\DB::raw('inscricoes.*, pae.* '))
                       ->join('inscricoes', 'users.id', '=', 'inscricoes.codigoUsuario')
                       ->leftJoin('pae', 'inscricoes.codigoInscricao', '=', 'pae.codigoInscricao')        
                       ->where('users.id', $user_id)
                       ->where('inscricoes.codigoEdital', $codigoEdital)
                       ->first();                              
        return $pae;
    }

    public static function obterEnderecoInscricao($user_id, $codigoInscricao)
    {
        $endereco = User::select(\DB::raw('inscricoes.codigoEdital, inscricoes.statusInscricao, enderecos.*, users.*, inscricoes_enderecos.codigoEndereco, inscricoes_enderecos.codigoInscricaoEndereco'))
                        ->join('inscricoes', 'users.id', '=', 'inscricoes.codigoUsuario')
                        ->leftjoin('enderecos', 'users.id', '=', 'enderecos.codigoUsuario')                                
                        ->leftJoin('inscricoes_enderecos', 'inscricoes_enderecos.codigoEndereco', '=', 'enderecos.codigoEndereco')
                        ->where('users.id', $user_id)
                        ->where('inscricoes.codigoInscricao', $codigoInscricao)
                        ->first();                              
        return $endereco;                                 
    }

    public static function obterEmergenciaInscricao($user_id, $codigoInscricao)
    {
        $emergencia = User::select(\DB::raw('inscricoes.codigoEdital, inscricoes.statusInscricao, enderecos.*, emergencias.*, users.*, inscricoes_enderecos.codigoInscricaoEndereco'))
                          ->join('inscricoes', 'users.id', '=', 'inscricoes.codigoUsuario')
                          ->leftjoin('emergencias', 'users.id', '=', 'emergencias.codigoUsuario')                                
                          ->leftJoin('inscricoes_enderecos', 'inscricoes_enderecos.codigoEmergencia', '=', 'emergencias.codigoEmergencia')
                          ->leftjoin('enderecos', 'enderecos.codigoEndereco', '=', 'inscricoes_enderecos.codigoEndereco')   
                          ->where('users.id', $user_id)
                          ->where('inscricoes.codigoInscricao', $codigoInscricao)
                          ->first();                                               
        return $emergencia;                                 
    }
    
    public static function obterEscolarInscricao($user_id, $codigoInscricao, $codigoResumoEscolar = '')
    {
        if(empty($codigoResumoEscolar))
        {
            $escolar = User::select(\DB::raw('inscricoes.codigoEdital, inscricoes.statusInscricao, resumo_escolar.*, inscricoes_resumo_escolar.codigoInscricoesResumoEscolar'))
                           ->join('inscricoes', 'users.id', '=', 'inscricoes.codigoUsuario')
                           ->leftjoin('resumo_escolar', 'users.id', '=', 'resumo_escolar.codigoUsuario')                                
                           ->leftJoin('inscricoes_resumo_escolar', 'inscricoes_resumo_escolar.codigoResumoEscolar', '=', 'resumo_escolar.codigoResumoEscolar')                             
                           ->where('users.id', $user_id)
                           ->where('inscricoes.codigoInscricao', $codigoInscricao)
                           ->get();
        }
        else
        {
            $escolar = User::select(\DB::raw('inscricoes.codigoEdital, inscricoes.statusInscricao, resumo_escolar.*, inscricoes_resumo_escolar.codigoInscricoesResumoEscolar'))
                           ->join('inscricoes', 'users.id', '=', 'inscricoes.codigoUsuario')
                           ->leftjoin('resumo_escolar', 'users.id', '=', 'resumo_escolar.codigoUsuario')                                
                           ->leftJoin('inscricoes_resumo_escolar', 'inscricoes_resumo_escolar.codigoResumoEscolar', '=', 'resumo_escolar.codigoResumoEscolar')                             
                           ->where('users.id', $user_id)
                           ->where('inscricoes.codigoInscricao', $codigoInscricao)
                           ->where('resumo_escolar.codigoResumoEscolar', $codigoResumoEscolar)
                           ->first();
        }

        return $escolar;                                 
    }

    public static function obterIdiomaInscricao($user_id, $codigoInscricao)
    {
        $idioma = User::select(\DB::raw('inscricoes.codigoEdital, inscricoes.statusInscricao, idiomas.*, users.*, inscricoes_idiomas.codigoInscricaoIdioma'))
                      ->join('inscricoes', 'users.id', '=', 'inscricoes.codigoUsuario')
                      ->leftjoin('idiomas', 'users.id', '=', 'idiomas.codigoUsuario')                                
                      ->leftJoin('inscricoes_idiomas', 'inscricoes_idiomas.codigoIdioma', '=', 'idiomas.codigoIdioma')
                      ->where('users.id', $user_id)
                      ->where('inscricoes.codigoInscricao', $codigoInscricao)
                      ->get();                                               
        return $idioma;                                 
    }
    
    public static function obterProfissionalInscricao($user_id, $codigoInscricao)
    {
        $profissional = User::select(\DB::raw('inscricoes.codigoEdital, inscricoes.statusInscricao, experiencias.*, users.*, inscricoes_experiencias.codigoInscricaoExperiencia'))
                            ->join('inscricoes', 'users.id', '=', 'inscricoes.codigoUsuario')                            
                            ->leftJoin('experiencias', function($join)
                            {
                                $join->on('users.id', '=', 'experiencias.codigoUsuario');
                                $join->on('experiencias.codigoTipoExperiencia', '=', \DB::raw(2));
                            })                          
                            ->leftJoin('inscricoes_experiencias', 'inscricoes_experiencias.codigoExperiencia', '=', 'experiencias.codigoExperiencia')
                            ->where('users.id', $user_id)
                            ->where('inscricoes.codigoInscricao', $codigoInscricao)
                            ->get();

        return $profissional;                                 
    }
    
    public static function obterEnsinoInscricao($user_id, $codigoInscricao)
    {
        $ensino = User::select(\DB::raw('inscricoes.codigoEdital, inscricoes.statusInscricao, experiencias.*, users.*, inscricoes_experiencias.codigoInscricaoExperiencia, tipo_entidade.*'))
                        ->join('inscricoes', 'users.id', '=', 'inscricoes.codigoUsuario')                            
                        ->leftJoin('experiencias', function($join)
                        {
                            $join->on('users.id', '=', 'experiencias.codigoUsuario');
                            $join->on('experiencias.codigoTipoExperiencia', '=', \DB::raw(1));
                        })                          
                        ->leftJoin('inscricoes_experiencias', 'inscricoes_experiencias.codigoExperiencia', '=', 'experiencias.codigoExperiencia')
                        ->join('tipo_entidade', 'tipo_entidade.codigoTipoEntidade', '=', 'experiencias.codigoTipoEntidade')
                        ->where('users.id', $user_id)
                        ->where('inscricoes.codigoInscricao', $codigoInscricao)
                        ->get();                                               
        return $ensino;                                 
    }     
}